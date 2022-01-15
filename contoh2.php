<?php
require 'Bot.php';
$bot = new Bot('2062986487:AAF8P2bi0AtpaKYsCXhcSjGDtvsRIcGuONI',  'BotLatihan123Bot');

$bot->start('Assalamualaikum <b>user</b>',['parse_mode'=>'html']);
$bot->chat('*', 'Oke');
$bot->photo('Kamu baru saja mengunggah <code>foto</code>',['parse_mode'=>'html']);
$bot->video('Kamu baru saja mengunggah <i>video</i>', ['parse_mode'=>'html', 'reply' => true]);
$bot->document('Kamu baru saja mengunggah <a href="https://www.google.com">dokumen</a>',['parse_mode'=>'html','disable_web_page_preview'=>true]);

$bot->run();