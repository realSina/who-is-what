<?php
date_default_timezone_set('Asia/Tehran');
define('ANSWERS', array('کی ', 'چه کسی '));
define('RANDOM', array('فکر کنم', 'فکر میکنم', 'شاید', 'احتمالا', 'به نظرم'));
ini_set('display_errors', 0);
ini_set('memory_limit', -1);
ini_set('max_execution_time', 300);
if(!file_exists('madeline.php') or filesize('madeline.php') < rand(1024, 2048)) {
    copy('https://phar.madelineproto.xyz/madeline.php', 'madeline.php');
}
define("MADELINE_BRANCH", "5.1.34");
include 'madeline.php';
$settings = ["logger" => ["logger_level" => 3, "max_size" => 102400], "serialization" => ["serialization_interval" => 30, "cleanup_before_serialization" => true], "peer" => ["full_info_cache_time" => 30],'app_info'=> ['api_id'=>3687497,'api_hash'=> '961d16c779b209c596881e08c6a42067']];
$MadelineProto = new \danog\MadelineProto\API('session.madeline', $settings);
$MadelineProto->start();
$loops = new \danog\MadelineProto\Loop\Generic\GenericLoop(
    $MadelineProto,
    function () use ($MadelineProto) {
        return 40;
    },
    "GenericLoop"
);
if(!file_exists('bot.lock')) {
    touch('bot.lock');
}
$lock = fopen('bot.lock', 'r+');
$try = 1;
$locked = false;
while (!$locked) {
    $locked = flock($lock, LOCK_EX | LOCK_NB);
    if (!$locked) {
        closeConnection();
        if ($try++ >= 30) {
            exit;
        }
        sleep(1);
    }
}
class EventHandler extends \danog\MadelineProto\EventHandler {
	public function filePutContents(string $patch, string $contents): AMP\Promise {
        return (AMP\File\put($patch, $contents));
    }

    public function getLocalContents(string $patch): AMP\Promise {
        return (AMP\File\get($patch));
    }
    public function __construct($MadelineProto) {
        parent::__construct($MadelineProto);
    }
    public function onUpdateSomethingElse($update) {
        if (isset($update['_'])) {
            if ($update['_'] == 'updateNewMessage') {
                onUpdateNewMessage($update);
            } else if ($update['_'] == 'updateNewChannelMessage') {
                onUpdateNewChannelMessage($update);
            }
        }
    }
    public function onUpdateNewChannelMessage($update) {
        yield $this->onUpdateNewMessage($update);
    }
    public function onUpdateNewMessage($update) {
        $from_id = isset($update['message']['from_id']) ? $update['message']['from_id'] : '';
        try {
            if(isset($update['message']['message'])) {
                if(!isset($update['message']['fwd_from']['_'])) {
                    $text = $update['message']['message'];
                    $message_id = $update['message']['id'];
                    $message = isset($update['message']) ? $update['message'] : '';
                    $MadelineProto = $this;
                    $me = yield $MadelineProto->get_self();
                    $bot_id = $me['id'];
                    $admins = array('404712801');
                    $chat_info = yield $MadelineProto->get_info($update);
                    $peer = $chat_info['bot_api_id'];
                    $chat_type = $chat_info['type'];
                    if($from_id != $bot_id) {
                        if(strtolower($text) == '/start') {
                            yield $this->messages->sendMessage(array('peer' => $peer, 'message' => "سلام 👋\n❓ این ربات فقط در جهت سرگرم کردن شما و دوستانتان در گروه هایتان است\n💡 طرز استفاده:\n۱_ ربات را در گروه مورد نظر اد کنید و ادمینش کنید که به پیام ها دسترسی داشته باشه\n۲_ از کلمه های 'کی' و یا 'چه کسی' استفاده کنید تا ربات جواب بده. مثال: 'کی امروز خوشحاله؟'", 'reply_to_msg_id' => $message_id));
                        }
                        elseif(strtolower($text) == '/creator' || strtolower($text) == '/developer' || strtolower($text) == '/dev') {
                            yield $this->messages->sendMessage(array('peer' => $peer, 'message' => "🤖 این ربات توسط @realSina نوشته شده است", 'reply_to_msg_id' => $message_id
                            ));
                        }
                        elseif(strtolower($text) == '/restart' && in_array($from_id, $admins)) {
                            yield $this->messages->sendMessage(array('peer' => $peer, 'message' => "♻️ ربات درحال ریستارت شدن", 'reply_to_msg_id' => $message_id));
                            yield $this->restart();
                        }
                        elseif(haveAnswers($text)) {
                            $answer = removeAnswers($text);
                            $javab = retRand();
                            $chat = yield $this->getPwrChat($peer);
                            $chats = $chat['participants'];
                            $user = randomUser($chats, $bot_id);
                            $result = $javab.' '.$user.' '.$answer;
                            yield $this->messages->sendMessage(array('peer' => $peer, 'message' => $result, 'reply_to_msg_id' => $message_id, 'parse_mode' => 'Markdown'));
                        }
                    }
                }
            }
        }
        catch(throwable $error) {
            $error_message = $error->getMessage();
            $error_file = $error->getFile();
            $error_line = $error->getLine();
            yield $this->messages->setTyping(["peer" => $owner, "action" => ["_" => "sendMessageTypingAction"]]);
            yield $this->messages->sendMessage(["peer" => 404712801, "message" => "■ Error message: $error_message\n\n■ Error file: $error_file\n\n■ Error line: $error_line"]);
        }
        catch(\Exception $e) {
        }
        catch(\danog\MadelineProto\RPCErrorException $e) {
        }
    }
}
register_shutdown_function('shutdown_function', $lock);
closeConnection();
$MadelineProto->async(true);
$MadelineProto->loop(function () use ($MadelineProto) {
    yield $MadelineProto->setEventHandler('\EventHandler');
});
$loops->start();
$MadelineProto->loop();

function closeConnection($message = "<br><br><br><center><h1>Madeline is running<h2></center>") {
    if(php_sapi_name() === 'cli' || isset($GLOBALS['exited'])) {
        return;
    }
    @ob_end_clean();
    header('Connection: close');
    ignore_user_abort(true);
    ob_start();
    echo "$message";
    $size = ob_get_length();
    header("Content-Length: $size");
    header('Content-Type: text/html');
    ob_end_flush();
    flush();
    $GLOBALS['exited'] = true;
}
function shutdown_function($lock) {
    try {
        $a = fsockopen((isset($_SERVER['HTTPS']) && @$_SERVER['HTTPS'] ? 'tls' : 'tcp') . '://' . @$_SERVER['SERVER_NAME'], @$_SERVER['SERVER_PORT']);
        fwrite($a, @$_SERVER['REQUEST_METHOD'] . ' ' . @$_SERVER['REQUEST_URI'] . ' ' . @$_SERVER['SERVER_PROTOCOL'] . "
" . 'Host: ' . @$_SERVER['SERVER_NAME'] . "

");
    flock($lock, LOCK_UN);
    fclose($lock);
    }
    catch(Exception $v) {
    }
}
function retRand() {
    return RANDOM[array_rand(RANDOM)];
}
function haveAnswers($str) {
    foreach(ANSWERS as $string) {
        if(isFind($str, $string)) {
            return true;
        }
    }
    return false;
}
function randomUser($chat, $me = -1) {
    $randUser = rand(1, sizeof($chat));
    $result = '';
    foreach($chat as $number => $info) {
        if(($number + 1) == $randUser) {
            $id = $info['user']['id'];
            $name = $info['user']['first_name'];
            if($me != -1 && $id == $me) {
                return randomUser($chat, $me);
            }
            if(empty($name) || is_null($name)) {
                $result = "[$id](tg://user?id=$id)";
            }
            else {
                $result = "[$name](tg://user?id=$id)";
            }
        }
    }
    return $result;
}
function removeAnswers($str) {
    $oldStr = $str;
    foreach(ANSWERS as $string) {
        if(isFind($str, $string)) {
            $oldStr = explode($string, $str)[1];
            if(!empty($oldStr)) {
                return str_replace('?', '', str_replace('؟', '', $oldStr));
            }
        }
    }
    return -1;
}
function isFind($string, $find) {
    $pos = stripos($string, $find);
    if($pos === false) {
        return false;
    }
    return true;
}