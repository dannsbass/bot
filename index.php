<?php

/**
 * implementasi class PHPTelebot yang telah dimodifikasi
 */

require 'Bot.php';

$bot = new Bot('2062986487:AAF8P2bi0AtpaKYsCXhcSjGDtvsRIcGuONI',  'BotLatihan123Bot');

$bot->start('Assalamualaikum');

$bot->start('Assalamualaikum <b>user</b>',['parse_mode'=>'html']);

$bot->start(['text'=>'Assalamualaikum','reply'=>true]);

$bot->start("Welcome to <a href='https://www.google.com'>Google</a>", ['reply' => true, 'disable_web_page_preview' => true, 'parse_mode'=>'html']);

$bot->text('/menu', function () {

    $keyboard[] = [['text' => 'satu'], ['text' => 'dua']];
    $keyboard[] = [['text' => 'tiga'], ['text' => 'empat']];
    $keyboard[] = [['text' => 'lima']];

    $reply_markup = [
        'keyboard' => $keyboard,
        'resize_keyboard' => true,
        'one_time_keyboard' => true,
        'input_field_placeholder' => 'apa ini?'
    ];

    return Bot::sendMessage('Silahkan pilih menu berikut', ['reply' => true, 'reply_markup' => $reply_markup]);
});

$bot->text('Hi', function () {
    $keyboard[] = [
        ['text' => 'satu', 'callback_data' => 'satu'],
        ['text' => 'dua', 'callback_data' => 'dua'],
    ];

    $reply_markup = [
        'inline_keyboard' => $keyboard
    ];

    return Bot::sendMessage('Hi too', ['reply' => true, 'reply_markup' => $reply_markup]);
    // return Bot::sendMessage('Hi too', ['reply'=>true]);
});

$bot->text('*', function () {
    $msg = Bot::message();
    $text = $msg['text'];
    return Bot::sendMessage($text, ['reply' => true]);
});

$bot->photo('Photo uploaded');

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
    return Bot::answerCallbackQuery("You touch $data button");
});

$bot->run();
