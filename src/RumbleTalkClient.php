<?php

namespace RumbleTalk;

use Exception;

class RumbleTalkClient
{
    /** @var string regular expression for password validation */
    const VALIDATION_PASSWORD = '/[^,]{6,50}/';

    /** @var int chat hash (aka public id) allowed characters */
    const CHAT_HASH_CHARACTERS = '/^[\w*:@~!\-]{8}$/';

    /** @var int {ENUM} different user levels */
    const UL_USER_GLOBAL = 3;
    const UL_MODERATOR = 4;
    const UL_MODERATOR_GLOBAL = 5;
    const UL_USER = 6;

    /** @var int interval (in seconds) before token renewal is necessary */
    const TOKEN_RENEWAL_INTERVAL = 300;

    /** @var string the API root URL */
    private $host = 'https://api.rumbletalk.com/';

    /** @var boolean verify SSL Certificate */
    private $sslVerifyPeer = false;

    /** @var int timeout default */
    private $timeout = 30;

    /** @var int connect timeout */
    private $connectTimeout = 30;

    /** @var string the SDK user agent */
    private $useragent = 'rumbletalk-sdk-php-v0.5.2';

    /** @var string the app key */
    private $key;

    /** @var string the app secret */
    private $secret;

    /** @var string current access token */
    private $accessToken;

    /** @var int last call http code */
    private $lastHttpCode;

    /** @var array last call headers */
    private $lastHeaders = [];

    /** @var string last call cURL error */
    private $lastError;

    /** @var int last call cURL error number */
    private $lastErrorNumber;

    /** @var  array additional headers (as full value) to add to the requests */
    private $additionalHeaders = [];

    /**
     * RumbleTalkSDK constructor.
     * @param string|null $key - the token key
     * @param string|null $secret - the token secret
     */
    public function __construct($key = null, $secret = null)
    {
        $this->setToken($key, $secret);
    }

    /**
     * validates a room hash structure
     * @param string $hash
     * @return bool
     */
    public static function validateHashStructure($hash)
    {
        return preg_match(self::CHAT_HASH_CHARACTERS, $hash);
    }

    /**
     * sets the instance's token; also removes the instance's access token
     * @param $key - the token key
     * @param $secret - the token secret
     */
    public function setToken($key, $secret)
    {
        $this->key = $key;
        $this->secret = $secret;
        $this->accessToken = null;
    }

    /**
     * retrieves the current instance's token
     * @return array - the instance's token
     */
    public function getToken()
    {
        return [
            'key' => $this->key,
            'secret' => $this->secret
        ];
    }

    /**
     * Extracts the expiration date from a given access token
     * @param string $token a JWT issued by the RumbleTalk server
     * @return string expiration timestamp
     */
    public static function getTokenExpiration($token)
    {
        $expiration = explode('.', $token);
        $expiration = json_decode(base64_decode($expiration[1]), true);
        return $expiration['exp'];
    }

    /**
     * Checks whether an expiration date of a token has passed.
     * @param string $accessToken the access token
     * @param int $leeway (optional) the minimum number of seconds a token must be valid for. default: self::TOKEN_RENEWAL_INTERVAL
     * @return bool true if the token should be renewed; false otherwise
     */
    public static function renewalNeeded($accessToken, $leeway = null)
    {
        $accessToken = self::getTokenExpiration($accessToken);

        if ($leeway === null) {
            $leeway = self::TOKEN_RENEWAL_INTERVAL;
        }

        return $accessToken - time() < $leeway;
    }

    /**
     * Get an access token to an account
     * This functions is for enterprise accounts and third party connections only
     * @param int $accountId the id of the account to get access to
     * @return string access token
     * @throws Exception
     */
    public function fetchAccountAccessToken($accountId = null)
    {
        $data = [
            'key' => $this->key,
            'secret' => $this->secret
        ];
        $extendRoute = '';

        if ($accountId) {
            $data['account_id'] = $accountId;
            $extendRoute = 'parent/';
        }

        $response = $this->httpRequest('POST', "{$extendRoute}token", null, $data);

        if (@$response['status'] != true) {
            $errorMessage = 'Error receiving access token.';
            if (!empty($response['message'])) {
                $errorMessage .= ' ' . $response['message'];
            }
            throw new Exception($errorMessage, 400);
        }
        $this->accessToken = $response['token'];

        return $this->accessToken;
    }

    /**
     * Get an access token
     * This function also sets the access token for the instance; there's no need to call the 'setAccessToken' function
     * @return string access token
     * @throws Exception
     */
    public function fetchAccessToken()
    {
        return $this->fetchAccountAccessToken(null);
    }

    /**
     * Sets the access token.
     * This functions is used when tokens are stored in your server.
     * It is required to save your tokens until they expire, because there's a limit on the number of tokens you can
     * create within a certain time
     * @param string $token - the access token
     */
    public function setAccessToken($token)
    {
        $this->accessToken = $token;
    }

    /**
     * retrieves the current instance's access token
     * @return string - the access token
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * Perform a POST request to the API
     * @param string $url the API route
     * @param array $data the data to pass to the server
     * @return array the response from the server
     * @throws Exception
     */
    public function post($url, $data)
    {
        return $this->httpRequest('POST', $url, $this->accessToken, $data);
    }

    /**
     * Perform a GET request to the API
     * @param string $url the API route (including query parameters)
     * @return array the response from the server
     * @throws Exception
     */
    public function get($url)
    {
        return $this->httpRequest('GET', $url, $this->accessToken);
    }

    /**
     * Perform a PUT request to the API
     * @param string $url the API route
     * @param array $data the data to pass to the server
     * @return array the response from the server
     * @throws Exception
     */
    public function put($url, $data)
    {
        return $this->httpRequest('PUT', $url, $this->accessToken, $data);
    }

    /**
     * Perform a DELETE request to the API
     * @param string $url the API route (including query parameters)
     * @return array the response from the server
     * @throws Exception
     */
    public function delete($url)
    {
        return $this->httpRequest('DELETE', $url, $this->accessToken);
    }

    /**
     * Returns the last request HTTP status code
     * @return int the last request's HTTP status code
     */
    public function getLastRequestStatusCode()
    {
        return $this->lastHttpCode;
    }

    /**
     * Returns the last request headers
     * @return array the last request's headers
     */
    public function getLastRequestHeaders()
    {
        return $this->lastHeaders;
    }

    /**
     * Returns the last request cURL error
     * @return string the last request's cURL error
     */
    public function getLastRequestError()
    {
        return $this->lastError;
    }

    /**
     * Returns the last request cURL error number
     * @return int the last request's cURL error number
     */
    public function getLastRequestErrorNumber()
    {
        return $this->lastErrorNumber;
    }

    /**
     * Inner function that creates the request
     * @param string $method the method of the call
     * @param string $url the API route (including query parameters)
     * @param string|null $token a bearer token for authenticated requests
     * @param array|null $data the data to pass to the server
     * @return array|string the response from the server
     * @throws Exception
     */
    private function httpRequest($method, $url, $token = null, $data = null)
    {
        # make sure the method is in upper case for comparison
        $method = strtoupper($method);

        $this->validateMethod($method);

        # in case of a relative URL, prefix the host and encode the parts
        if (strrpos($url, 'https://') !== 0) {
            if (strpos($url, '?') !== false) {
                # detach the "path" part from the "search" part of the URL
                list($url, $search) = explode('?', $url, 2);

                # if there is a "search" part, encode it's KVPs
                if ($search) {
                    # separate each pair
                    $search = explode('&', $search);
                    foreach ($search as &$pair) {
                        # encode the key, and the value if exists.
                        $pair = explode('=', $pair);
                        $pair[0] = urlencode($pair[0]);
                        if ($pair[1]) {
                            $pair[1] = urlencode($pair[1]);
                        }
                        $pair = implode('=', $pair);
                    }
                    $search = implode('&', $search);
                }
                $url .= '?' . $search;
            }
            $url = $this->host . $url;
        }

        $headers = [];

        if ($token) {
            $headers[] = "Authorization: Bearer $token";
        }

        if (count($this->additionalHeaders) > 0) {
            $headers = array_merge($headers, $this->additionalHeaders);
        }

        $ch = curl_init();

        if (!empty($data) && $this->methodWithData($method)) {
            $data = json_encode($data);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

            $headers[] = 'Content-Type: application/json';
            $headers[] = 'Content-Length: ' . strlen($data);
        }

        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        curl_setopt_array(
            $ch,
            [
                CURLOPT_USERAGENT => $this->useragent,
                CURLOPT_CONNECTTIMEOUT => $this->connectTimeout,
                CURLOPT_TIMEOUT => $this->timeout,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => $this->sslVerifyPeer,
                CURLOPT_HEADER => false,
                CURLOPT_HEADERFUNCTION => [$this, '_getHeader'],
                CURLOPT_URL => $url
            ]
        );

        switch ($method) {
            case 'POST':
                curl_setopt($ch, CURLOPT_POST, true);
                break;

            case 'PUT':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                break;

            case 'DELETE':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
                break;
        }

        $response = curl_exec($ch);

        # set the last http code; headers are set automatically by cURL
        $this->lastHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($response === false) {
            $this->lastError = curl_error($ch);
            $this->lastErrorNumber = curl_errno($ch);
        }

        curl_close($ch);

        $result = json_decode($response, true);

        return $result ?: "$this->lastHttpCode: $response";
    }

    /**
     * used by cURL
     * retrieve the returned headers;
     * @param resource $ch
     * @param string $header
     * @return int
     */
    private function _getHeader($ch, $header)
    {
        $i = strpos($header, ':');
        if (!empty($i)) {
            $key = str_replace('-', '_', strtolower(substr($header, 0, $i)));
            $value = trim(substr($header, $i + 2));
            $this->lastHeaders[$key] = $value;
        }

        return strlen($header);
    }

    /**
     * validates the method
     * @param string $method the method to be validated
     * @throws Exception if the method supplied is invalid
     */
    private function validateMethod($method)
    {
        if (!in_array($method, ['POST', 'GET', 'PUT', 'DELETE'])) {
            throw new Exception("Invalid method supplied: $method", 405);
        }
    }

    /**
     * checks if the method can hold data
     * @param string $method the HTTP method in question
     * @return bool true if the HTTP method can hold data
     */
    private function methodWithData($method)
    {
        return in_array($method, ['POST', 'PUT']);
    }

    /**
     * Validates an email address
     * @param string $email the email address to validate
     * @return bool true if the email is valid, false otherwise
     */
    public function validateEmail(&$email)
    {
        if (!is_string($email)) {
            return false;
        }
        $email = trim($email);

        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Validates that a password meets our demands
     * @param string $password the password to validate
     * @return bool true if the password is valid, false otherwise
     */
    public function validatePassword($password)
    {
        return is_string($password) && preg_match(self::VALIDATION_PASSWORD, $password);
    }

    /**
     * Adds the given headers to the requests
     * @param array $headers - the given headers in full format; e.g. ['Location: /', 'x-example: value']
     * @return bool - true on success, false on failure
     */
    public function setAdditionalHeaders($headers)
    {
        if (is_array($headers)) {
            $this->additionalHeaders = $headers;

            return true;
        }

        return false;
    }
}
