<?php

/**
 * See more about the polls routes here:
 * https://dev.rumbletalk.com/rest/polls
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

# create a poll
//$chatId = 0;
//$data = [
//    'question' => 'Who is poll?',
//    'answer1' => 'Ren',
//    'answer2' => 'Stimpy',
//];
//$result = $rumbletalk->post("chats/$chatId/polls", $data);

# get all the polls in a room
$chatId = 0;
$result = $rumbletalk->get("chats/$chatId/polls");

# get a specific chat
/** currently not available */
//$chatId = 0;
//$pollId = 0;
//$result = $rumbletalk->get("chats/$chatId/polls/$pollId");

# update a poll
/** [Beta] some issues may occur; currently requires all fields to update */
//$chatId = 0;
//$pollId = 0;
//$data = ['answer3' => 'Happy, happy, joy, joy'];
//$result = $rumbletalk->put("chats/$chatId/polls/$pollId", $data);

# delete a poll
//$chatId = 0;
//$pollId = 0;
//$result = $rumbletalk->delete("chats/$chatId/polls/$pollId");


print_r($result);
