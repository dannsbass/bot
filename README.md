# PHP Bot Telegram 

Modifikasi dari [PHPTelebot](https://github.com/radyakaze/phptelebot) by [radyakaze](https://github.com/radyakaze)

## Cara Pasang

Salin file `Bot.php` lalu `include` atau `require` ke dalam file projek Anda.

## Contoh Kode

Berikut ini beberapa contoh kode yang bisa Anda pakai:

### Contoh Pertama (Paling Sederhana)

```php
require 'Bot.php';

$bot = new Bot('TOKEN', 'USERNAME'); //ganti dengan TOKEN dan USERNAME dari @BotFather

$bot->start('Assalamualaikum');
$bot->chat('Hai', 'Hai juga');
$bot->chat('/help', 'Cara menggunakan bot ini adalah sebagai berikut...');
$bot->text('Kalau ada pertanyaan silahkan hubungi 08123456789');
$bot->photo('Kamu baru saja mengunggah foto');
$bot->document('Kamu baru saja mengunggah dokumen');
$bot->video('Kamu baru saja mengunggah video');
$bot->sticker('Kamu baru saja mengunggah sticker');

$bot->run();
```

### Contoh Kedua (Pertengahan)

```php
require 'Bot.php';

$bot = new Bot('TOKEN', 'USERNAME'); //ganti dengan TOKEN dan USERNAME dari @BotFather

$bot->start('Assalamualaikum <b>user</b>',['parse_mode'=>'html']);
$bot->chat('*', 'Oke');
$bot->photo('Kamu baru saja mengunggah <code>foto</code>',['parse_mode'=>'html']);
$bot->video('Kamu baru saja mengunggah <i>video</i>', ['parse_mode'=>'html', 'reply' => true]);
$bot->document('Kamu baru saja mengunggah <a href="https://www.google.com">dokumen</a>',['parse_mode'=>'html','disable_web_page_preview'=>true]);

$bot->run();

```
### Contoh Ketiga (Kompleks)

```php
require 'Bot.php';

$bot = new Bot('TOKEN', 'USERNAME'); //ganti dengan TOKEN dan USERNAME dari @BotFather

$bot->start(function () {
    $keyboard[] = [
        ['text' => 'satu', 'callback_data' => 'satu'],
        ['text' => 'dua', 'callback_data' => 'dua'],
    ];

    $reply_markup = [
        'inline_keyboard' => $keyboard
    ];

    return Bot::sendMessage('Mari kita mulai. Silahkan klik /menu atau /help', ['reply' => true, 'reply_markup' => $reply_markup]);
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
    return Bot::answerCallbackQuery("You touch $data button");
});

$bot->run();
```
### Cara Mudah Membuat Inline Keyboard

Ada cara lain untuk membuat inline keyboard dengan mudah:
```php
$inline_keyboard = Bot::inline_keyboard('
[Next|next] [Prev|preview]
[Google|https://google.com] [Facebook|https://facebook.com]
[Telegram|https://telegram.org]
[Instagram|https://instagram.com] [Youtube|https://youtube.com]
[Twitter|https://twitter.com]
[Desainer|Danns] 
');

$bot->start("Selamat <b>user</b> datang di <a href='https://www.googgle.com'>Google</a>",['parse_mode'=>'html','reply'=>true,'disable_web_page_preview'=>true,'reply_markup'=>$inline_keyboard]);

$bot->run();
```
### Cara Mudah Membuat Keyboard

Ada cara mudah membuat keyboard biasa (bukan inline_keyboard) menggunakan function `Bot::keyboard($string)`
```php
$bot->text(function(){
    $keyboard = Bot::keyboard('
    [Tentang] [Fitur]
    [Desainer]
    ');
    return Bot::sendMessage('Silahkan pilih menu yang tersedia',['reply'=>true,'reply_markup'=>$keyboard]);
});
```
## Daftar Method Biasa

- `getUsername()` untuk mengambil username bot
- `chat($request, $response)` untuk me-response teks tertentu yang di-request oleh user, contoh `chat('Hai', 'Hai juga')` untuk merespon teks `Hai` dengan teks `Hai juga` atau `chat('Hai',function(){return Bot::sendPhoto('fotoku.jpg');})` untuk merespon teks `Hai` dengan file `fotoku.jpg`. Kalau untuk merespon semua teks yang dikirim oleh user, gunakan string satu bintang (*) pada parameter pertama seperti ini `chat('*', 'Silahkan hubungi Admin')` atau bisa juga begini `text('Silahkan hubungi Admin')`, dua-duanya sama hasilnya.
- `cmd($request, $response)` alias `chat($request, $response)`
## Daftar Method Static

- `keyboard($pola)` untuk membuat keyboard dari string dengan pola `[tombol]`, contoh:
```php
$tombol = Bot::keyboard('
[UTAMA]
[TENTANG] [MENU]
[KEMBALI] [KEDEPAN]
[INFO]
');`
```
- `inline_keyboard($pola)` untuk membuat inline keyboard dari string dengan pola `[teks|URL]` atau `[teks|teks]`, contoh:
```php
$inline_keyboard = Bot::inline_keyboard('
[ Next | next ] [ Back | back ]
[ Menu 1 | menu_1 ] [ Google | https://google.com ]
[ Info | info ]
');
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