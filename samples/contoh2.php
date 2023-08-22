<?php
require 'Bot.php';
$bot = new Bot('2062986487:AAF8P2bi0AtpaKYsCXhcSjGDtvsRIcGuONI',  'BotLatihan123Bot');

$bot->chat('Hai', 'Hai juga');
$bot->chat('Info', 'Ini adalah info');
$bot->chat('/admin', 'Ini adalah admin');

$bot->text('Anda mengirim pesan teks');

$bot->run();