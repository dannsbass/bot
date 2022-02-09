# PHP Bot Telegram

Modifikasi dari [PHPTelebot](https://github.com/radyakaze/phptelebot) by [radyakaze](https://github.com/radyakaze)

## Cara Pasang

- Salin file `Bot.php`
    ```bash
    wget https://raw.githubusercontent.com/dannsbass/bot/master/Bot.php
    ```
- Lakukan `include` atau `require` (lebih disarankan) file `Bot.php` ke dalam file projek Anda:
    ```php
    require 'Bot.php';
    ```
## Contoh Kode

Berikut ini beberapa contoh kode yang bisa Anda pakai:

### Contoh 1 (Merespon Semua Pesan Teks dengan Satu Pesan Teks)

<img src='https://github.com/dannsbass/bot/blob/master/assets/img/contoh1.png'>

**Keterangan contoh 1**: seperti Anda lihat pada screenshot, bot hanya merespon pesan teks saja dan responnya hanyalah kalimat statis yaitu `Anda mengirim pesan teks`. Selain pesan teks, bot akan mengabaikannya alias tidak meresponnya sama sekali. Berikut ini kodenya:

```php
require 'Bot.php';

$bot = new Bot('TOKEN', 'USERNAME'); //ganti dengan TOKEN dan USERNAME dari @BotFather

$bot->text('Anda mengirim pesan teks');

$bot->run();
```

Keterangan kode: Method yang digunakan untuk merespon semua pesan teks adalah `text()` dengan satu parameter (string) di dalamnya berisi respon/balasan kepada user.

### Contoh 2 (Merespon Pesan Teks Tertentu dengan Pesan Teks yang Tertentu)

<img src='https://github.com/dannsbass/bot/blob/master/assets/img/contoh2.png'>

**Keterangan contoh 2**: bot merespon pesan teks `Hai` dengan `Hai juga`, `Info` dengan `Ini adalah info` dan `/admin` dengan `Ini adalah admin`. Selain itu, semua pesan teks akan direspon dengan kalimat statis `Anda mengirim pesan teks`. Berikut ini kodenya:

```php
require 'Bot.php';

$bot = new Bot('TOKEN', 'USERNAME'); //ganti dengan TOKEN dan USERNAME dari @BotFather

$bot->chat('Hai', 'Hai juga');
$bot->chat('Info', 'Ini adalah info');
$bot->chat('/admin', 'Ini adalah admin');

$bot->text('Anda mengirim pesan teks');

$bot->run();
```

Keterangan kode: Method yang digunakan untuk merespon teks tertentu adalah `chat()` dengan dua parameter:

1. `$request` (kiri) yaitu pesan teks yang dikirim oleh user.
2. `$respon` (kanan) yaitu pesan teks balasan dari bot.

### Contoh 3 (Mengirim Teks disertai Tombol/Keyboard)

<img src='https://github.com/dannsbass/bot/blob/master/assets/img/contoh3.png'>

**Keterangan contoh 3**: bot merespon perintah `/start` dengan pesan `Silahkan pilih menu berikut ini` disertai lima buah tombol (keyboard) yaitu `TENTANG`, `MENU`, `ADMIN`, `NO REKENING` dan `HELP`. Jika tombol ditekan, bot akan merespon dengan kalimat tertentu. Berikut ini kodenya:

```php
require 'Bot.php';

$bot = new Bot('TOKEN', 'USERNAME'); //ganti dengan TOKEN dan USERNAME dari @BotFather

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
```

Keterangan kode: Untuk me-reply pesan yang dikirim oleh user, tambahkan elemen `'reply'=>true` pada array parameter kedua (kanan) dalam method `sendMessage()`. Untuk memodifikasi respon yang akan dikirim, gunakan `function(){}` pada parameter kedua dalam method `chat()`. Untuk membuat tombol/keyboard, method yang digunakan adalah _static method_ `keyboard()` dengan satu parameter berupa string dengan pola `[teks]`.

### Contoh 4 (Membuat Tombol/Keyboard Inline yang Responsif)

<img src='https://github.com/dannsbass/bot/blob/master/assets/img/contoh4.png'>

**Keterangan contoh 4**: bot merespon perintah `/start` dengan kalimat `Selamat user datang di Google` (salah ketik, harusnya: `selamat datang user` hehe..). Perhatikan kata `user` ditebalkan dan kata `Google` berupa link yang merujuk ke situs `https://www.googgle.com`. Selain kalimat tersebut, bot juga menyertakan tombol-tombol (inline keyboard) yang responsif, yaitu jika diklik akan menghasilkan respon `Anda menekan tombol <anu>` kecuali tombol link yang berisi URL ke alamat situs tertentu.

```php
require 'Bot.php';

$bot = new Bot('TOKEN', 'USERNAME'); //ganti dengan TOKEN dan USERNAME dari @BotFather

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
```

Keterangan kode: untuk membuat inline_keyboard secara mudah dan cepat, gunakan method `Bot::inline_keyboard()` dengan satu parameter berupa string yang polanya adalah `[teks|teks]` atau `[teks|URL]`. Method `start($respon, $options)` adalah singkatan dari `chat('/start', function() use($respon, $options){ return Bot::sendMessage($respon, $options);})`, hasilnya sama. Method `callback_query()` diambil dari nama events pada Telegram Bot API (lihat daftarnya di bawah). Fungsinya untuk merespon tombol inline_keyboard jika diklik/ditekan. Sedangkan method `answerCallbackQuery` adalah salah satu method bawaan [Telegram](https://core.telegram.org/bots/api#answercallbackquery) untuk menampilkan notifikasi pada layar chatting bagian atas sebagai alert (peringatan).

### Contoh 5 (Membalas Foto dengan Teks)

<img src='https://github.com/dannsbass/bot/blob/master/assets/img/contoh5.png'>

**Keterangan contoh 5**: bot merespon teks `/start` dengan kalimat `Selamat datang di bot ...` dan merespon foto yang diunggah dengan kalimat `Anda baru saja mengunggah foto dengan rincian sebagai berikut:` lalu disebutkan rincian foto yang dikirim tersebut.

```php
require 'Bot.php';
$bot = new Bot('TOKEN', 'USERNAME'); //ganti dengan TOKEN dan USERNAME dari @BotFather

$bot->start('Selamat datang di bot ...');

$bot->photo(function () {
    $rincian = json_encode(Bot::message(), JSON_PRETTY_PRINT);
    return Bot::sendMessage("Anda baru saja mengunggah foto dengan rincian sebagai berikut:\n$rincian");
});

$bot->run();
```

Keterangan kode: Bot merespon foto yang dikirim oleh user menggunakan method `photo()` yang berisi satu parameter berupa _function_. Cara mendapatkan rincian foto yang dikirim oleh user adalah dengan menggunakan _static method_ bernama `message()` bawaan PHPTelebot. Output method tersebut berupa _array_ sehingga perlu di-encode dengan `json_encode` supaya berubah menjadi _string_ dan bisa dikirim ke user.

### Contoh 6 (Membalas Foto dengan Foto yang Sama)

<img src='https://github.com/dannsbass/bot/blob/master/assets/img/contoh6.png'>

**Keterangan contoh 6**: bot merespon pesan teks `/start` dengan kalimat `Silahkan kirim foto` dan merespon foto yang diunggah dengan mengembalikan foto tersebut ke user yang mengirimnya. Berikut ini kodenya:

```php
require 'Bot.php';
$bot = new Bot('TOKEN', 'USERNAME'); //ganti dengan TOKEN dan USERNAME dari @BotFather

$bot->start('Silahkan kirim foto');

$bot->photo(function () {
    $msg = Bot::message();
    $photo = $msg['photo'][0]['file_id'];
    return Bot::sendPhoto($photo);
});

$bot->run();
```

Keterangan kode: untuk mengirim foto ke user, gunakan static method `sendPhoto()` dengan parameter `$photo` yang diambil dari _file_id_ foto tesebut. Baca keterangan selengkapnya tentang method [sendPhoto](https://core.telegram.org/bots/api#sendphoto)

### Contoh 7

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

**Keterangan contoh 7**: bot merespon pesan teks `/start` dengan pesan teks `Assalamualaikum`, pesan teks `Hai` dengan pesan teks `Hai juga` dan pesan teks `/help` dengan kalimat `Cara menggunakan bot ini adalah sebagai berikut...`. Selain teks itu, bot akan merespon setiap pesan teks dengan pesan teks `Kalau ada pertanyaan silahkan hubungi 08123456789`. Bot juga merespon foto yang diunggah oleh user dengan pesan teks `Kamu baru saja mengunggah foto`, merespon dokumen yang diunggah oleh user dengan pesan teks `Kamu baru saja mengunggah dokumen` dan seterusnya.

### Contoh 8

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

### Contoh 9

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

Ada cara mudah untuk membuat _inline keyboard_ dengan menggunakan _method_ `Bot::inline_keyboard()`

```php
$inline_keyboard = Bot::inline_keyboard('
    [Next|next] [Prev|preview]
    [Google|https://google.com] [Facebook|https://facebook.com]
    [Telegram|https://telegram.org]
    [Instagram|https://instagram.com] [Youtube|https://youtube.com]
    [Twitter|https://twitter.com]
    [Desainer|Danns]
');

$pesan = "Selamat <b>user</b> datang di <a href='https://www.googgle.com'>Google</a>";
$options = [
    'parse_mode'=>'html',
    'reply'=>true,
    'disable_web_page_preview'=>true,
    'reply_markup'=>$inline_keyboard
    ];

$bot->start($pesan, $options);
```

### Cara Mudah Membuat Keyboard

Ada cara mudah membuat _keyboard_ biasa (bukan _inline_keyboard_) menggunakan _method_ `Bot::keyboard($string)`

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

- `start($sambutan)` untuk menyambut user baru yang menekan tombol `START` atau user yang mengirim pesan teks `/start` kepada bot. Parameternya bisa satu berupa string, contoh `start('Assalamualaikum user')`, bisa juga berupa function, contoh `start(function(){ return Bot::sendMessage('Assalamualikum user');})` dan bisa juga disi dengan dua parameter (string dan array), contoh: `start('Assalamualaikum *user*', ['parse_mode'=>'markdown'])` di mana array berisi rincian pesan yang dikirim (lihat dokumentasinya di: [Telegram](https://core.telegram.org/bots/api#sendmessage)).
- `chat($request, $response)` untuk me-response teks tertentu yang di-request oleh user, contoh `chat('Hai', 'Hai juga')` untuk merespon teks `Hai` dengan teks `Hai juga` atau `chat('Hai', function(){return Bot::sendPhoto('fotoku.jpg');})` untuk merespon teks `Hai` dengan file `fotoku.jpg`. Kalau untuk merespon semua teks yang dikirim oleh user, gunakan string satu bintang ( `*` ) pada parameter pertama seperti ini `chat('*', 'Silahkan hubungi Admin')` atau bisa juga begini `text('Silahkan hubungi Admin')`, dua-duanya sama hasilnya.
- `cmd($request, $response)` alias `chat($request, $response)`.
- `all($response)` untuk me-response semua event yang belum diatur sebelumnya.
- **Events** (lihat daftarnya di bawah).

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
- `name()` untuk mengambil username bot.
- `user()` untuk mengambil nama user.
- `from_id()` untuk mengambil user id.
- `chat_id()` untuk mengambil chat id.
- `message_text()` untuk mengambil teks yang dikirim oleh user (hanya berlaku jika user mengirim pesan teks).

## Daftar Events

Ada method-method yang diambil dari nama-nama events pada [Telegram Bot API](https://core.telegram.org/bots/api#message). Cara menggunakannya sama dengan menggunakan method biasa, yaitu `$bot->nama_method()` contoh: `$bot->text()` untuk merespon pesan teks, `$bot->animation()` untuk merespon pesan animasi, `$bot->audio()` untuk merespon pesan audio dan seterusnya. Berikut ini daftarnya:

- `text`
- `animation`
- `audio`
- `document`
- `photo`
- `sticker`
- `video`
- `video_note`
- `voice`
- `contact`
- `dice`
- `game`
- `poll`
- `venue`
- `location`
- `new_chat_members`
- `left_chat_members`
- `new_chat_title`
- `new_chat_photo`
- `delete_chat_photo`
- `group_chat_created`
- `supergroup_chat_created`
- `channel_chat_created`
- `message_auto_delete_timer_changed`
- `migrate_to_chat_id`
- `migrate_from_chat_id`
- `pinned_message`
- `invoice`
- `successful_payment`
- `connected_website`
- `passport_data`
- `proximity_alert_triggered`
- `voice_chat_scheduled`
- `voice_chat_started`
- `voice_chat_ended`
- `voice_chat_participants_invited`
- `inline_query`
- `callback_query`
- `edited_message`
- `channel_post`
- `edited_channel_post`
