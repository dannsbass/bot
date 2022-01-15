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

$options = [
    'reply'=>true, // sebagai ganti 'reply_to_message_id' => $message_id
    'parse_mode'=>'html', // pilihannya: 'html' atau 'markdown'
    'disable_web_page_preview'=>true, // pilihannya: true atau false
    'reply_markup'=>$inline_keyboard
];

$bot->start("Selamat <b>user</b> datang di <a href='https://www.googgle.com'>Google</a>.", $options);

$bot->callback_query(function () {
    $msg = Bot::message();
    $data = $msg['data'];
    return Bot::answerCallbackQuery("Anda menekan tombol $data");
});

$bot->run();