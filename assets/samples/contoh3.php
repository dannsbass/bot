<?php
require 'Bot.php';
$bot = new Bot('2062986487:AAF8P2bi0AtpaKYsCXhcSjGDtvsRIcGuONI',  'BotLatihan123Bot');

$bot->chat('/start', function(){
    $pesan = 'Silahkan pilih menu berikut ini';
    $tombol = Bot::keyboard('
    [TENTANG] [MENU]
    [ADMIN] [NO REKENING]
    [HELP]
    ');
    return Bot::sendMessage($pesan, ['reply'=>true, 'reply_markup'=>$tombol]);
});

$bot->chat('TENTANG', 'Kami adalah ...');
$bot->chat('MENU', 'Berikut ini adalah menu ...');
$bot->chat('ADMIN', 'Untuk menghubungi Admin, silahkan ...');
$bot->chat('NO REKENING', 'Silahkan transfer ke no rekening berikut ...');
$bot->chat('HELP', 'Untuk pertolongan, silahkan hubungi ...');

$bot->run();