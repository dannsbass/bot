<?php
require 'Bot.php';
$bot = new Bot('2062986487:AAF8P2bi0AtpaKYsCXhcSjGDtvsRIcGuONI',  'BotLatihan123Bot');

$bot->start('Silahkan kirim foto');

$bot->photo(function () {
    $msg = Bot::message();
    $photo = $msg['photo'][0]['file_id'];
    return Bot::sendPhoto($photo);
});

$bot->run();