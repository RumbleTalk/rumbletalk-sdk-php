# RumbleTalk API Client Library for PHP

[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%205.4-8892BF)](https://www.php.net/supported-versions.php)
[![License](https://poser.pugx.org/rumbletalk/rumbletalk-sdk-php/license)](https://packagist.org/packages/rumbletalk/rumbletalk-sdk-php)
<!--[![Latest Stable Version](https://img.shields.io/packagist/v/rumbletalk/rumbletalk-sdk-php)](https://packagist.org/packages/rumbletalk/rumbletalk-sdk-php)-->
<!--[![Total Downloads](https://poser.pugx.org/rumbletalk/rumbletalk-sdk-php/downloads)](https://packagist.org/packages/rumbletalk/rumbletalk-sdk-php)-->

This project hosts the PHP client library for the [RumbleTalk API](https://dev.rumbletalk.com/rest).

## Requirements

* This library depends on the [PHP cURL extension](https://www.php.net/curl), which depends on [cURL](https://curl.se/).
* It is requires the [json extension](https://www.php.net/manual/en/book.json.php), which is bundled and compiled into
  PHP by default.
* PHP >= 5.4
* `[recommended]` Composer >= 2.0; Composer is not required but recommended.
    * If you don't want to use Composer, simply download the `src/*` folder into your project and use your own
      autoloader

## Getting started

First, install the library using composer:

    composer require rumbletalk/rumbletalk-sdk-php

Then require your autoloader, and use the client as such:

    require 'vendor/autoload.php';
    
    use RumbleTalk\RumbleTalkClient;
    
    $appKey = 'YOUR_TOKEN_KEY';
    $appSecret = 'YOUR_TOKEN_SECRET';
    
    # create the RumbleTalk SDK instance using the key and secret
    $rumbletalk = new RumbleTalkClient($appKey, $appSecret);
    
    # fetch (and set) the access token for the account (tokens lasts for 30 days)
    $rumbletalk->fetchAccessToken();
    
    # use the $rumbletalk client to access the API endpoints
    # see examples/*.php for the different usages

* Make sure you replace `YOUR_TOKEN_KEY` and `YOUR_TOKEN_SECRET` with your values.
* See our dev site [Authentication](https://dev.rumbletalk.com/rest/authentication#authenticationRegular) page for
  instructions on how to get the token key and secret

## Common uses

* Members chat room
* Live Events and streaming
* One to one messaging (private or direct thread)
* Social Chat
* Q&A chat room

## Features

* NEW - polls options in the chat
* NEW - Bio Description can be added in the username (ex: display name+bio)
* NEW - Admin count in plan summary added
* NEW - Admin button is added for easy access
* support for avatar integration with different members plugins.
* Integration with WordPress users base avatar

* Mark texts as a bold, italic, strike and code.
* Admin mode - mute all users.
* New lines - now you can add more lines in each message
* Font size - increase/decrease the web-based font size
* Private chat - prevents automatic private chat window popup

* Paid access, bug fixes
* Experts chat, allow you to advice in a private conversation (with or without payment)
* PayWall - Set paid access to your chat.
* Control what username will show in the chat
* Keywords feature - automatic text highlights
* login type: Register before logging-in

* Better Sound Control
* History search options
* Export chat history to csv or html
* Video chat messages, record 30-second video messages.
* Mobile video calls (android)
* Set Private chats with registered users
* Spam Filter applied also for users name
* Create additional rooms directly from the plugin
* Open settings from WordPress admin
* Delete Archive messages directly from the chat
* Increase Font Size in mobile

* Full Screen In mobile mode
* Admin user avatar
* BuddyPress integration
* Export Chat Transcript from the chat interface
* Auto login with your own users base users name (API)
* IP info

* Upload Images from your mobile device
* Take photos from your mobile version
* One on One VIDEO and Audio calls
* Upload Docs, Excel, PowerPoint, PDF files
* Upload Images from your own PC

* Take pictures from your PC camera
* Easily Embed a group chat in your site.

* Chatroom Theme Library
* Talk from Mobile and Tablet.
* Login, Share and Invite
* Private chat
