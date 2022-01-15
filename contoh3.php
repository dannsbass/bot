<?php
require 'Bot.php';
$bot = new Bot('2062986487:AAF8P2bi0AtpaKYsCXhcSjGDtvsRIcGuONI',  'BotLatihan123Bot');

$bot->start(function () {
    $keyboard[] = [
        ['text' => 'satu', 'callback_data' => 'satu'],
        ['text' => 'dua', 'callback_data' => 'dua'],
    ];

    $reply_markup = [
        'inline_keyboard' => $keyboard
    ];

    return Bot::sendMessage('Mari kita mulai. Silahkan klik /menu', ['reply' => true, 'reply_markup' => $reply_markup]);
});

$bot->chat('satu', 'Anda memilih satu');
$bot->chat('dua', 'Anda memilih dua');
$bot->chat('tiga', 'Anda memilih tiga');
$bot->chat('empat', 'Anda memilih empat');
$bot->chat('lima', 'Anda memilih lima');

$bot->chat('/menu|/help', function () {

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