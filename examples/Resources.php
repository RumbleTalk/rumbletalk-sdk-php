<?php

/**
 * Last updated at 18-DEC-2021
 * See more about the [open] resources routes here:
 * https://dev.rumbletalk.com/rest/resources
 */

namespace Examples;

require_once '../vendor/autoload.php';

use RumbleTalk\RumbleTalkClient;

# create the RumbleTalk SDK instance (these routes are open and do not need a token)
$rumbletalk = new RumbleTalkClient();

# get the list of available countries for your account settings
$result = $rumbletalk->get('resources/countries');

# get the list of available languages for your chat settings
//$result = $rumbletalk->get('resources/languages');

# get the list of available skins to choose from as the base design of your chat rooms
//$result = $rumbletalk->get('resources/skins');

# get the list of available sounds to choose for different events in your chat rooms
//$result = $rumbletalk->get('resources/sounds');

print_r($result);
