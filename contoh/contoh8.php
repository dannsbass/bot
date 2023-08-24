<?php
require 'Bot.php';
$bot = new Bot('2062986487:AAF8P2bi0AtpaKYsCXhcSjGDtvsRIcGuONI',  'BotLatihan123Bot');

$bot->start('Kirim location');

$bot->location(function($longitude, $latitude){
    return Bot::sendMessage("Longitude: $longitude, Latitude: $latitude");
});

$bot->run();
