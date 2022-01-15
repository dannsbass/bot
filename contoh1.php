<?php
require 'Bot.php';
$bot = new Bot('2062986487:AAF8P2bi0AtpaKYsCXhcSjGDtvsRIcGuONI',  'BotLatihan123Bot');

$bot->start('Assalamualaikum');
$bot->chat('Hai', 'Hai juga');
$bot->chat('/help', 'Cara menggunakan bot ini adalah sebagai berikut...');
$bot->text('Kalau ada pertanyaan silahkan hubungi 08123456789');
$bot->photo('Kamu baru saja mengunggah foto');
$bot->document('Kamu baru saja mengunggah dokumen');
$bot->video('Kamu baru saja mengunggah video');
$bot->sticker('Kamu baru saja mengunggah sticker');

$bot->run();