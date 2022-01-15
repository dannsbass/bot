<?php
require 'Bot.php';
$bot = new Bot('2062986487:AAF8P2bi0AtpaKYsCXhcSjGDtvsRIcGuONI',  'BotLatihan123Bot');

$inline_keyboard = Bot::inline_keyboard('
[Next|next] [Prev|preview]
[Google|https://google.com] [Facebook|https://facebook.com]
[Telegram|https://telegram.org]
[Instagram|https://instagram.com] [Youtube|https://youtube.com]
[Twitter|https://twitter.com]
[Desainer|Danns] 
');

$bot->start("Selamat <b>user</b> datang di <a href='https://www.googgle.com'>Google</a>. Silahkan klik /menu untuk melihat menu yang tersedia",['parse_mode'=>'html','reply'=>true,'disable_web_page_preview'=>true,'reply_markup'=>$inline_keyboard]);


$bot->chat('satu', 'Anda memilih satu');
$bot->chat('dua', 'Anda memilih dua');
$bot->chat('tiga', 'Anda memilih tiga');
$bot->chat('empat', 'Anda memilih empat');
$bot->chat('lima', 'Anda memilih lima');

$bot->chat('/menu', function () {

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