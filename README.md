# PHP Bot Telegram 

Modifikasi dari PHPTelebot by radyakaze

## Cara Pakai

Silahkan pilih cara yang paling Anda sukai:

### Cara Pertama (Paling Sederhana)

```php
require 'Bot.php';

$bot = new Bot('2062986487:AAF8P2bi0AtpaKYsCXhcSjGDtvsRIcGuONI',  'BotLatihan123Bot');

$bot->start('Assalamualaikum');
$bot->text('Hai', 'Hai juga');
$bot->photo('Kamu baru saja mengunggah foto');
$bot->document('Kamu baru saja mengunggah dokumen');
$bot->video('Kamu baru saja mengunggah video');

$bot->run();
```

### Cara Kedua (Pertengahan)

```php
require 'Bot.php';

$bot = new Bot('2062986487:AAF8P2bi0AtpaKYsCXhcSjGDtvsRIcGuONI',  'BotLatihan123Bot');

$bot->start('Assalamualaikum <b>user</b>',['parse_mode'=>'html']);
$bot->text('*', 'Oke');
$bot->photo('Kamu baru saja mengunggah <code>foto</code>',['parse_mode'=>'html']);
$bot->video('Kamu baru saja mengunggah <i>video</i>', ['parse_mode'=>'html', 'reply' => true]);
$bot->document('Kamu baru saja mengunggah <a href="https://www.google.com">dokumen</a>',['parse_mode'=>'html','disable_web_page_preview'=>true]);

$bot->run();

```
### Cara Ketiga (Kompleks)

```php
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

    return Bot::sendMessage('Mari kita mulai', ['reply' => true, 'reply_markup' => $reply_markup]);
});


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
```
### Cara Mudah Membuat Inline Keyboard

Ada cara lain untuk membuat inline keyboard dengan mudah:
```php
require 'Bot.php';

$bot = new Bot('2062986487:AAF8P2bi0AtpaKYsCXhcSjGDtvsRIcGuONI',  'BotLatihan123Bot');

$inline_keyboard = Bot::inline_keyboard('
[Google|https://google.com] [Facebook|https://facebook.com]

[Telegram|https://telegram.org]

[Instagram|https://instagram.com] [Youtube|https://youtube.com]

[Twitter|https://twitter.com]

[Desainer|Danns]
');

$bot->start("Selamat <b>user</b> datang di <a href='https://www.googgle.com'>Google</a>",['parse_mode'=>'html','reply'=>true,'disable_web_page_preview'=>true,'reply_markup'=>$inline_keyboard]);

$bot->run();
```
## Daftar Event

- text
- animation
- audio
- document
- photo
- sticker
- video
- video_note
- voice
- contact
- dice
- game
- poll
- venue
- location
- new_chat_members
- left_chat_members
- new_chat_title
- new_chat_photo
- delete_chat_photo
- group_chat_created
- supergroup_chat_created
- channel_chat_created
- message_auto_delete_timer_changed
- migrate_to_chat_id
- migrate_from_chat_id
- pinned_message
- invoice
- successful_payment
- connected_website
- inline_query
- callback_query
- edited_message
- channel_post
- edited_channel_post