<?php
class Bot
{
    public static $token = '';
    public static $username = '';
    public static $url = "https://api.telegram.org/bot";
    public static $dbg = '';
    public static $getUpdates = [];
    protected $_command = [];
    protected $_onMessage = [];
    public static $debug = true;
    protected static $version = '1.3';

    public function __construct($token, $username = '')
    {
        // Check php version
        if (version_compare(phpversion(), '5.4', '<')) {
            die("PHPTelebot needs to use PHP 5.4 or higher.\n");
        }

        // Check bot token
        if (empty($token)) {
            die("Bot token should not be empty!\n");
        }

        self::$token = $token;
        self::$username = $username;
        self::$url .= $token;
    }

    public function __call($type, $args)
    {
        if ($type == 'start') {
            return $this->manageStart($args);
        }

        $types = [
            'text',
            'animation',
            'audio',
            'document',
            'photo',
            'sticker',
            'video',
            'video_note',
            'voice',
            'contact',
            'dice',
            'game',
            'poll',
            'venue',
            'location',
            'new_chat_members',
            'left_chat_members',
            'new_chat_title',
            'new_chat_photo',
            'delete_chat_photo',
            'group_chat_created',
            'supergroup_chat_created',
            'channel_chat_created',
            'message_auto_delete_timer_changed',
            'migrate_to_chat_id',
            'migrate_from_chat_id',
            'pinned_message',
            'invoice',
            'successful_payment',
            'connected_website',
            'inline_query',
            'callback_query',
            'edited_message',
            'channel_post',
            'edited_channel_post',
        ];

        if (in_array($type, $types)) {
            if (!isset($args[1])) {
                return $this->on($type, $args[0]);
            } else {
                return $this->manageArgs($type, $args);
            }
        }

        return self::__callStatic($type, $args);
    }

    private function manageStart($args)
    {
        if (isset($args[1])) {
            if (is_array($args[1])) {
                if (is_array($args[0])) {
                    return $this->text('/start', function () use ($args) {
                        return self::send('sendMessage', array_merge($args[0], $args[1]));
                    });
                } else {
                    return $this->text('/start', function () use ($args) {
                        return self::send('sendMessage', array_merge(['text' => $args[0]], $args[1]));
                    });
                }
            } else {
                if (is_array($args[0])) {
                    return $this->text('/start', function () use ($args) {
                        return self::send('sendMessage', array_merge($args[0], ['text' => $args[1]]));
                    });
                } else {
                    return $this->text('/start', function () use ($args) {
                        return self::send('sendMessage', ['text' => $args[0] . $args[1]]);
                    });
                }
            }
        } else {
            return $this->text('/start', $args[0]);
        }
    }

    public function getUsername()
    {
        if (!empty($this->username)) return $this->username;
        $url = self::$url . '/getMe';
        if (function_exists('curl_version')) {
            $ch = curl_init($url);
            $json = curl_exec($ch);
            curl_close($ch);
        } else {
            $json = file_get_contents($url);
        }
        $res = json_decode($json);
        return $res->result->username ?? false;
    }

    public function text($command, $answer)
    {
        if ($command == '*') {
            $this->_onMessage['text'] = $answer;
        } else {
            $this->_command[$command] = $answer;
        }
    }

    public function anyText($answer)
    {
        $this->text('*', $answer);
    }

    private function manageArgs($type, $args)
    {
        if (isset($args[1])) {
            if (is_array($args[1])) {
                if (is_array($args[0])) {
                    return $this->on($type, function () use ($args) {
                        return self::send('sendMessage', array_merge($args[0], $args[1]));
                    });
                } else {
                    return $this->on($type, function () use ($args) {
                        return self::send('sendMessage', array_merge(['text' => array_shift($args)], $args[0]));
                    });
                }
            } else {
                if (is_array($args[0])) {
                    return $this->on($type, function () use ($args) {
                        return self::send('sendMessage', array_merge($args[0], ['text' => $args[1]]));
                    });
                } else {
                    return $this->on($type, function () use ($args) {
                        return self::send('sendMessage', ['text' => $args[0] . " " . $args[1]]);
                    });
                }
            }
        } else {
            if (is_array($args[0])) {
                return $this->on($type, function () use ($args) {
                    return self::send('sendMessage', $args[0]);
                });
            } else {
                return $this->on($type, function () use ($args) {
                    return self::send('sendMessage', ['text' => $args[0]]);
                });
            }
        }
    }

    public static function keyboard($pesan)
    {
        if (preg_match_all('/\[[^\]]+\]([^\n]+)?([\n]+|$)/', $pesan, $tombol)) {
            $array_baru = [];
            foreach ($tombol[0] as $tombol) {
                preg_match_all('/\[([^\]]+)\]/', $tombol, $temuan);
                $array = $temuan[1];
                foreach ($array as $key => $value) {
                    $array[$key] = ['text' => $value];
                }
                $array_baru[] = $array;
            }
            $keyboard = json_encode([
                "keyboard" => $array_baru,
                'resize_keyboard' => true,
                'one_time_keyboard' => true,
                'input_field_placeholder' => 'tulis...'
            ]);
            return $keyboard; #string json
        }
    }

    public static function inline_keyboard($pesan)
    {
        if (preg_match_all('/\[[^\|\(\)]+\|[^\|\(\)]+\]([^\n]+)?([\n]+|$)/', $pesan, $tombol)) {

            $tombols = $tombol[0]; #array
            $inline_keyboard = [];
            foreach ($tombols as $tombol) {

                preg_match_all('/\[[^\|\(\)]+\|[^\|\(\)]+\]/', $tombol, $temuan);
                $array = $temuan[0];
                $susunan = [];
                foreach ($array as $a) {

                    $b = explode('|', $a);
                    $bagian = [];
                    foreach ($b as $c) {

                        $bagian[] = $c;
                    }
                    $b0 = trim(str_replace('[', '', $bagian[0]));

                    $b1 = trim(str_replace(']', '', $bagian[1]));

                    if (filter_var($b1, FILTER_VALIDATE_URL) !== false) {
                        $susunan[] = [
                            "text" => $b0,
                            "url" => $b1
                        ];
                    } else {
                        $susunan[] = [
                            "text" => $b0,
                            "callback_data" => $b1
                        ];
                    }
                }
                $inline_keyboard[] = $susunan;
            }
            $inline_keyboard = json_encode(["inline_keyboard" => $inline_keyboard]);

            return $inline_keyboard; #string json
        }
    }

    /**
     * Events.
     *
     * @param string          $types
     * @param callable|string $answer
     */
    public function on($types, $answer)
    {
        if ($types == 'start') {
            $this->_command['/start'] = $answer;
        }
        $types = explode('|', $types);
        foreach ($types as $type) {
            $this->_onMessage[$type] = $answer;
        }
    }

    /**
     * Custom regex for command.
     *
     * @param string          $regex
     * @param callable|string $answer
     */
    public function regex($regex, $answer)
    {
        $this->_command['customRegex:' . $regex] = $answer;
    }

    /**
     * Run telebot.
     *
     * @return bool
     */
    public function run()
    {
        try {
            if (php_sapi_name() == 'cli') {
                echo 'PHPTelebot version ' . self::$version;
                echo "\nMode\t: Long Polling\n";
                $options = getopt('q', ['quiet']);
                if (isset($options['q']) || isset($options['quiet'])) {
                    self::$debug = false;
                }
                echo "Debug\t: " . (self::$debug ? 'ON' : 'OFF') . "\n";
                $this->longPoll();
            } else {
                $this->webhook();
            }

            return true;
        } catch (Exception $e) {
            echo $e->getMessage() . "\n";

            return false;
        }
    }

    /**
     * Webhook Mode.
     */
    private function webhook()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SERVER['CONTENT_TYPE'] == 'application/json') {
            self::$getUpdates = json_decode(file_get_contents('php://input'), true);
            echo $this->process();
        } else {
            http_response_code(400);
            throw new Exception('Access not allowed!');
        }
    }

    /**
     * Long Poll Mode.
     *
     * @throws Exception
     */
    private function longPoll()
    {
        $offset = 0;
        while (true) {
            $req = json_decode(self::send('getUpdates', ['offset' => $offset, 'timeout' => 30]), true);

            // Check error.
            if (isset($req['error_code'])) {
                if ($req['error_code'] == 404) {
                    $req['description'] = 'Incorrect bot token';
                }
                throw new Exception($req['description']);
            }

            if (!empty($req['result'])) {
                foreach ($req['result'] as $update) {
                    self::$getUpdates = $update;
                    $process = $this->process();

                    if (self::$debug) {
                        $line = "\n--------------------\n";
                        $outputFormat = "$line %s $update[update_id] $line%s";
                        echo sprintf($outputFormat, 'Query ID :', json_encode($update));
                        echo sprintf($outputFormat, 'Response for :', self::$dbg ?: $process ?: '--NO RESPONSE--');
                        // reset debug
                        self::$dbg = '';
                    }
                    $offset = $update['update_id'] + 1;
                }
            }

            // Delay 1 second
            sleep(1);
        }
    }

    /**
     * Process the message.
     *
     * @return string
     */
    private function process()
    {
        $get = self::$getUpdates;
        $run = false;

        if (isset($get['message']['date']) && $get['message']['date'] < (time() - 120)) {
            return '-- Pass --';
        }

        if (self::type() == 'text') {
            $customRegex = false;
            foreach ($this->_command as $cmd => $call) {
                if (substr($cmd, 0, 12) == 'customRegex:') {
                    $regex = substr($cmd, 12);
                    // Remove bot username from command
                    if (self::$username != '') {
                        $get['message']['text'] = preg_replace('/^\/(.*)@' . self::$username . '(.*)/', '/$1$2', $get['message']['text']);
                    }
                    $customRegex = true;
                } else {
                    $regex = '/^(?:' . addcslashes($cmd, '/\+*?[^]$(){}=!<>:-') . ')' . (self::$username ? '(?:@' . self::$username . ')?' : '') . '(?:\s(.*))?$/';
                }
                if ($get['message']['text'] != '*' && preg_match($regex, $get['message']['text'], $matches)) {
                    $run = true;
                    if ($customRegex) {
                        $param = [$matches];
                    } else {
                        $param = isset($matches[1]) ? $matches[1] : '';
                    }
                    break;
                }
            }
        }

        if (isset($this->_onMessage) && $run === false) {
            if (in_array(self::type(), array_keys($this->_onMessage))) {
                $run = true;
                $call = $this->_onMessage[self::type()];
            } elseif (isset($this->_onMessage['*'])) {
                $run = true;
                $call = $this->_onMessage['*'];
            }

            if ($run) {
                switch (self::type()) {
                    case 'callback':
                        $param = $get['callback_query']['data'];
                        break;
                    case 'inline':
                        $param = $get['inline_query']['query'];
                        break;
                    case 'location':
                        $param = [$get['message']['location']['longitude'], $get['message']['location']['latitude']];
                        break;
                    case 'text':
                        $param = $get['message']['text'];
                        break;
                    default:
                        $param = '';
                        break;
                }
            }
        }

        if ($run) {
            if (is_callable($call)) {
                if (!is_array($param)) {
                    $count = count((new ReflectionFunction($call))->getParameters());
                    if ($count > 1) {
                        $param = array_pad(explode(' ', $param, $count), $count, '');
                    } else {
                        $param = [$param];
                    }
                }

                return call_user_func_array($call, $param);
            } else {
                if (!isset($get['inline_query'])) {
                    return self::send('sendMessage', ['text' => $call]);
                }
            }
        }
    }

    public static function send(string $action, array $data)
    {
        $upload = false;
        $actionUpload = ['sendPhoto', 'sendAudio', 'sendDocument', 'sendSticker', 'sendVideo', 'sendVoice'];

        if (in_array($action, $actionUpload)) {
            $field = str_replace('send', '', strtolower($action));

            if (is_file($data[$field])) {
                $upload = true;
                $data[$field] = self::curlFile($data[$field]);
            }
        }

        $needChatId = ['sendMessage', 'forwardMessage', 'sendPhoto', 'sendAudio', 'sendDocument', 'sendSticker', 'sendVideo', 'sendVoice', 'sendLocation', 'sendVenue', 'sendContact', 'sendChatAction', 'editMessageText', 'editMessageCaption', 'editMessageReplyMarkup', 'sendGame'];

        if (in_array($action, $needChatId) && !isset($data['chat_id'])) {
            $getUpdates = self::$getUpdates;
            if (isset($getUpdates['callback_query'])) {
                $getUpdates = $getUpdates['callback_query'];
            }
            $data['chat_id'] = $getUpdates['message']['chat']['id'];
            // Reply message
            if (!isset($data['reply_to_message_id']) && isset($data['reply']) && $data['reply'] === true) {
                $data['reply_to_message_id'] = $getUpdates['message']['message_id'];
                unset($data['reply']);
            }
        }

        if (isset($data['reply_markup']) && is_array($data['reply_markup'])) {
            $data['reply_markup'] = json_encode($data['reply_markup']);
        }

        if (function_exists('curl_version')) {
            $ch = curl_init();
            $options = [
                CURLOPT_URL => self::$url . '/' . $action,
                CURLOPT_POST => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_SSL_VERIFYPEER => false
            ];

            if (is_array($data)) {
                $options[CURLOPT_POSTFIELDS] = $data;
            }

            if ($upload) {
                $options[CURLOPT_HTTPHEADER] = ['Content-Type: multipart/form-data'];
            }

            curl_setopt_array($ch, $options);

            $result = curl_exec($ch);

            if (curl_errno($ch)) {
                echo curl_error($ch) . "\n";
            }
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
        } else {

            if ($upload) return self::send('sendMessage', ['text' => 'Maaf, layanan ini tidak tersedia karena versi PHP yang digunakan saat ini tidak mendukung fungsi curl. Silahkan instal terlebih dahulu']);

            $opts = [
                'http' => [
                    'method' => "POST",
                    'header' => 'Content-Type: application/x-www-form-urlencoded',
                    'content' => http_build_query($data)
                ]
            ];

            $result = file_get_contents(self::$url . '/' . $action, false, stream_context_create($opts));
            if (!$result) return false;

            $httpcode = null; //perlu review lagi
        }

        if (self::$debug && $action != 'getUpdates') {
            self::$dbg .= 'Method: ' . $action . "\n";
            self::$dbg .= 'Data: ' . print_r($data, true) . "\n";
            self::$dbg .= 'Response: ' . $result . "\n";
        }

        if ($httpcode == 401) {
            throw new Exception('Incorect bot token');
            return false;
        } else {
            return $result;
        }
    }

    public static function answerInlineQuery($results, $options = [])
    {
        if (!empty($options)) {
            $data = $options;
        }

        if (!isset($options['inline_query_id'])) {
            $get = self::$getUpdates;
            $data['inline_query_id'] = $get['inline_query']['id'];
        }

        $data['results'] = json_encode($results);

        return self::send('answerInlineQuery', $data);
    }

    public static function answerCallbackQuery($text, $options = [])
    {
        $options['text'] = $text;

        if (!isset($options['callback_query_id'])) {
            $get = self::$getUpdates;
            $options['callback_query_id'] = $get['callback_query']['id'];
        }

        return self::send('answerCallbackQuery', $options);
    }

    private static function curlFile($path)
    {
        // PHP 5.5 introduced a CurlFile object that deprecates the old @filename syntax
        // See: https://wiki.php.net/rfc/curl-file-upload
        if (function_exists('curl_file_create')) {
            return curl_file_create($path);
        } else {
            // Use the old style if using an older version of PHP
            return "@$path";
        }
    }

    public static function message()
    {
        $get = self::$getUpdates;
        if (isset($get['message'])) {
            return $get['message'];
        } elseif (isset($get['callback_query'])) {
            return $get['callback_query'];
        } elseif (isset($get['inline_query'])) {
            return $get['inline_query'];
        } elseif (isset($get['edited_message'])) {
            return $get['edited_message'];
        } elseif (isset($get['channel_post'])) {
            return $get['channel_post'];
        } elseif (isset($get['edited_channel_post'])) {
            return $get['edited_channel_post'];
        } else {
            return [];
        }
    }

    public static function type()
    {
        $getUpdates = self::$getUpdates;

        if (isset($getUpdates['message']['text'])) {
            return 'text';
        } elseif (isset($getUpdates['message']['animation'])) {
            return 'animation';
        } elseif (isset($getUpdates['message']['photo'])) {
            return 'photo';
        } elseif (isset($getUpdates['message']['video'])) {
            return 'video';
        } elseif (isset($getUpdates['message']['video_note'])) {
            return 'video_note';
        } elseif (isset($getUpdates['message']['audio'])) {
            return 'audio';
        } elseif (isset($getUpdates['message']['contact'])) {
            return 'contact';
        } elseif (isset($getUpdates['message']['dice'])) {
            return 'dice';
        } elseif (isset($getUpdates['message']['poll'])) {
            return 'poll';
        } elseif (isset($getUpdates['message']['voice'])) {
            return 'voice';
        } elseif (isset($getUpdates['message']['document'])) {
            return 'document';
        } elseif (isset($getUpdates['message']['sticker'])) {
            return 'sticker';
        } elseif (isset($getUpdates['message']['venue'])) {
            return 'venue';
        } elseif (isset($getUpdates['message']['location'])) {
            return 'location';
        } elseif (isset($getUpdates['inline_query'])) {
            return 'inline_query';
        } elseif (isset($getUpdates['callback_query'])) {
            return 'callback_query';
        } elseif (isset($getUpdates['message']['new_chat_members'])) {
            return 'new_chat_members';
        } elseif (isset($getUpdates['message']['left_chat_members'])) {
            return 'left_chat_members';
        } elseif (isset($getUpdates['message']['new_chat_title'])) {
            return 'new_chat_title';
        } elseif (isset($getUpdates['message']['new_chat_photo'])) {
            return 'new_chat_photo';
        } elseif (isset($getUpdates['message']['delete_chat_photo'])) {
            return 'delete_chat_photo';
        } elseif (isset($getUpdates['message']['group_chat_created'])) {
            return 'group_chat_created';
        } elseif (isset($getUpdates['message']['channel_chat_created'])) {
            return 'channel_chat_created';
        } elseif (isset($getUpdates['message']['supergroup_chat_created'])) {
            return 'supergroup_chat_created';
        } elseif (isset($getUpdates['message']['migrate_to_chat_id'])) {
            return 'migrate_to_chat_id';
        } elseif (isset($getUpdates['message']['migrate_from_chat_id'])) {
            return 'migrate_from_chat_id';
        } elseif (isset($getUpdates['message']['pinned_message'])) {
            return 'pinned_message';
        } elseif (isset($getUpdates['message']['invoice'])) {
            return 'invoice';
        } elseif (isset($getUpdates['message']['successful_payment'])) {
            return 'successful_payment';
        } elseif (isset($getUpdates['message']['connected_website'])) {
            return 'connected_website';
        } elseif (isset($getUpdates['edited_message'])) {
            return 'edited_message';
        } elseif (isset($getUpdates['message']['game'])) {
            return 'game';
        } elseif (isset($getUpdates['channel_post'])) {
            return 'channel_post';
        } elseif (isset($getUpdates['edited_channel_post'])) {
            return 'edited_channel_post';
        } else {
            return 'unknown';
        }
    }

    public static function __callStatic($action, $args)
    {
        $param = [];
        $firstParam = [
            'sendMessage' => 'text',
            'sendPhoto' => 'photo',
            'sendVideo' => 'video',
            'sendAudio' => 'audio',
            'sendVoice' => 'voice',
            'sendDocument' => 'document',
            'sendSticker' => 'sticker',
            'sendVenue' => 'venue',
            'sendChatAction' => 'action',
            'setWebhook' => 'url',
            'getUserProfilePhotos' => 'user_id',
            'getFile' => 'file_id',
            'getChat' => 'chat_id',
            'leaveChat' => 'chat_id',
            'getChatAdministrators' => 'chat_id',
            'getChatMembersCount' => 'chat_id',
            'sendGame' => 'game_short_name',
            'getGameHighScores' => 'user_id',
        ];
        if (!isset($firstParam[$action])) {
            if (isset($args[0]) && is_array($args[0])) {
                $param = $args[0];
            }
        } else {
            $param[$firstParam[$action]] = $args[0];
            if (isset($args[1]) && is_array($args[1])) {
                $param = array_merge($param, $args[1]);
            }
        }
        return call_user_func_array('self::send', [$action, $param]);
    }
}
