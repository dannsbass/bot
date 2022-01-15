<?php
require 'Bot.php';
$bot = new Bot('2062986487:AAF8P2bi0AtpaKYsCXhcSjGDtvsRIcGuONI',  'BotLatihan123Bot');

$bot->chat('Tentang', 'Ini adalah Tentang (bukan kentang hehe)');
$bot->chat('Fitur', 'Ini adalah Fitur');
$bot->chat('Desainer', 'Desainer bot ini adalah...');

$bot->text(function(){
    $keyboard = Bot::keyboard('
    [Tentang] [Fitur]
    [Desainer]
    ');
    return Bot::sendMessage('Silahkan pilih menu yang tersedia',['reply'=>true,'reply_markup'=>$keyboard]);
});

$bot->photo(function () {
    $rincian = json_encode(Bot::message(), JSON_PRETTY_PRINT);
    return Bot::sendMessage("Anda baru saja mengunggah foto dengan rincian sebagai berikut:\n$rincian");
});

$bot->document(function () {
    $rincian = json_encode(Bot::message(), JSON_PRETTY_PRINT);
    return Bot::sendMessage("Anda baru saja mengunggah dokumen dengan rincian sebagai berikut:\n$rincian");
});

$bot->callback_query(function () {
    $msg = Bot::message();
    $data = $msg['data'];
    return Bot::answerCallbackQuery("Anda menekan tombol $data");
});

$bot->run();