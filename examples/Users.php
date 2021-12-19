<?php

/**
 * See more about the users routes here:
 * https://dev.rumbletalk.com/rest/users
 */

namespace Examples;

require_once '../vendor/autoload.php';

use RumbleTalk\RumbleTalkClient;

$appKey = 'YOUR_TOKEN_KEY';
$appSecret = 'YOUR_TOKEN_SECRET';

# create the RumbleTalk SDK instance using the key and secret
$rumbletalk = new RumbleTalkClient($appKey, $appSecret);

# fetch (and set) the access token for the account (tokens lasts for 30 days)
$rumbletalk->fetchAccessToken();


# create a chat user
//$data = [
//    'username' => 'John Smith',
//    'password' => '123456',
//    'level' => RumbleTalkClient::UL_USER_GLOBAL
//];
//$result = $rumbletalk->post('users', $data);

# get all the chats users
$result = $rumbletalk->get('users');

# get a specific chat user
//$userIdentifier = 'the username or the user id';
//$result = $rumbletalk->get("users/$userIdentifier");

# update a chat user
//$userIdentifier = 'the username or the user id';
//$data = ['level' => RumbleTalkClient::UL_MODERATOR_GLOBAL];
//$result = $rumbletalk->put("users/$userIdentifier", $data);

# delete a chat user
//$userIdentifier = 'the user name or the user id';
//$result = $rumbletalk->delete("users/$userIdentifier");

print_r($result);
