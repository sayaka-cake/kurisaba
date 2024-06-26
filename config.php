<?php

mb_internal_encoding("UTF-8");
// Sets error reporting to hide notices.
error_reporting(E_ALL ^ E_NOTICE);
if (!headers_sent()) {
    header('Content-Type: text/html; charset=utf-8');

    /* disable cache, finally. by Reimu */
    header("Expires: Sat, 17 Mar 1990 00:00:01 GMT");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
}

$cf = array();

// Caching (this needs to be set at the start because if enabled, it skips the rest of the configuration process)
$cf['KU_APC'] = false;

$cache_loaded = false;
if ($cf['KU_APC']) {
    if (apc_load_constants('config')) {
        $cache_loaded = true;
    }
}

if (!$cache_loaded) {
    // Common settings, can be retrieved from env
    $cf['KU_ROOTDIR'] = realpath(dirname(__FILE__)) . "/";
    $cf['KU_WEBFOLDER'] = '/';
    $cf['KU_PROTO'] = getenv('KU_PROTO') ?: 'http://';
    $cf['KU_TEMPLATEDIR'] = $cf['KU_ROOTDIR'] . 'dwoo/templates';
    $cf['KU_CACHEDTEMPLATEDIR'] = $cf['KU_ROOTDIR'] . 'tmp/dwoo';

    $cf['KU_DBTYPE'] = getenv('KU_DBTYPE') ?: 'mysqli';
    $cf['KU_DBHOST'] = getenv('KU_DBHOST') ?: 'mysql:3306';
    $cf['KU_DBDATABASE'] = getenv('KU_DBDATABASE') ?: 'kurisa_ch';
    $cf['KU_DBUSERNAME'] = getenv('KU_DBUSERNAME') ?: 'root';
    $cf['KU_DBPASSWORD'] = getenv('KU_DBPASSWORD') ?: 'password';
    $cf['KU_DBPREFIX'] = getenv('KU_DBPREFIX') ?: '';
    $cf['KU_DBUSEPERSISTENT'] = getenv('KU_DBUSEPERSISTENT') ?: true;

    // Основные настройки борды
    $cf['KU_NAME'] = 'Insert your text here';   // Название имиджборды.
    $cf['KU_BASE_HOST'] = "localhost";         // Имя сайта борды (не обязательно, но на кривых серверах без него может не работать).
    $cf['KU_DEFAULTBOARD'] = 'sg';                       // Борда "по умолчанию", показываемая в правом фрейме фреймсета.
    $cf['KU_SEARCH_PHRASES'] = 'Фраза 1#Фраза 2#Фраза 3'; // Случайные фразы, показываемые в окошках поиска текста.
    $cf['KU_BANREASON'] = '';                        // Причина бана по умолчанию.
    $cf['KU_BANMSG'] = '<br /><font color="#FF0000"><b>(USER WAS BANNED FOR THIS POST)</b></font>'; // Сообщение к посту, по которому дан бан (добавляется при включённой настройке "Add ban message").
    $cf['KU_RANDOMSEED'] = 'ENTERRANDOMSHIT';         // Инициализационное значение для md5-Функций. Лучше задать побольше, 35+ символов. Нельзя менять после запуска борды, иначе слетят данные о ip постов, трипкоды с двумя восклицательными знаками и т.п.

    // Совместимость и особенности сервера
    $cf['KU_CSSVER'] = '005';                   // "Версия" CSS. Можно менять при изменении css-файлов, если используется кэширующий прокси, на котором нельзя сбросить кэш.
    $cf['KU_JSVER'] = '007';                   // "Версия" JS. Можно менять при изменении javascript-файлов, если используется кэширующий прокси, на котором нельзя сбросить кэш.
    $cf['KU_FFMPEGPATH'] = '/usr/local/bin/ffmpeg'; // Путь к FFMPEG для создания картинок-превьюшек WEBM. На данный момент не используется.
    $cf['KU_CURL_INTERFACE'] = '';                      // Сетевой интерфейс для загрузки файла по ссылке (например, 'tun0'). Чтобы при загрузке файла не палить IP, рекомендуется поднять VPN до какого-нибудь сервера, который не жалко подставлять под DDoS и указать здесь сетевой интерфейс VPN.

    // Локаль и время
    $cf['KU_LOCALE'] = 'ru';              // Текущая локаль. Возможные значения: en, de, et, es, fi, pl, nl, nb, ro, ru, it, ja.
    $cf['KU_CHARSET'] = 'UTF-8';           // Кодировка. Должна совпадать с той, что указана в .htaccess (AddCharset <кодировка> .html, AddCharset <кодировка> .php).
    $cf['KU_ID3_ENCODING'] = 'cp1251';          // Кодировка ID3-тегов в MP3.
    putenv('TZ=Europe/Moscow'); // Часовой пояс
    $cf['KU_ADDTIME'] = 10800;             // Это значение автоматически добавляется к time(). В зависимости от настроек сервера (определяются экспериментально) должно быть либо 0, либо соответствовать смещению часового пояса (в секундах). Для Москвы это 10800.
    $cf['KU_DATEFORMAT'] = 'D Y M d H:i:s';   // Формат даты в постах (например, 'd/m/y(D)H:i').

    // Разные фичи
    $cf['KU_EXPAND'] = true; // Включить возможность разворотат тредов на странице борд.
    $cf['KU_QUICKREPLY'] = true; // Включить возможность быстрого ответа на пост.
    $cf['KU_FIRSTLAST'] = true; // Включить возможность смотреть не только весь тред, но и первые 100/последние 50 постов.
    $cf['KU_APPEAL'] = true; // Включить возможность просить снять бан.
    $cf['KU_GENERATEBOARDLIST'] = true; // Генерировать список борд сверху/снизу автоматически (если нет - вместо него используется файл boards.html).
    $cf['KU_DIRTITLE'] = true; // Показывать путь к доске в заголовке страницы (например, "/b/ - Random" вместо "Random").
    $cf['KU_CUTPOSTS'] = true; // Обрезать слишком длинные посты на странице доски (можно развернуть по клику).
    $cf['KU_NEWWINDOW'] = true; // Открывать картинки в новом окне (если не используется javascript-разворачивание).
    $cf['KU_MAKELINKS'] = true; // Делать внешние ссылки в постах кликабельными.
    $cf['KU_NOMESSAGETHREAD'] = '';   // Если пользователь создаёт тред с ОП-постом без текста, то этот пост будет содержать этот текст.
    $cf['KU_NOMESSAGEREPLY'] = '';   // Если пользователь отвечает в тред постом без текста, то этот пост будет содержать этот текст.

    // Ограничения
    $cf['KU_MAXSMILIES'] = 10;     // Максимальное количество смайлов в посте.
    $cf['KU_FEEDLENGTH'] = 100;    // Длина однопотока (в постах).
    $cf['KU_MAXTHREADSADAY'] = 10;     // Максимальное количество тредов в день (защита от вайпа). Если столько тредов уже создано, но нужно ещё - админ может сбросить этот отсчёт с помощью пункта "Сбросить лимит тредов" в админке.
    $cf['KU_MAXSEARCHRESULTS'] = 500;    // Максимальное количество элементов на странице поиска.
    $cf['KU_HASHCHECKLAG'] = 604800; // Кулдаун файлов (в секундах). Нельзя загрузить файл, уже имеющийся на борде, если с момента его создания прошло меньше секунд. Кулдаун можно обойти с использованием куклоскрипта (так задумано), бан по картинке - нельзя.
    $cf['KU_MAXNAMELENGTH'] = 75;     // Максимальная длина имени постера.
    $cf['KU_MAXEMAILLENGTH'] = 75;     // Длина невидимого "поля" e-mail (такое значение может понадобиться для внешних приложений).
    $cf['KU_MAXSUBJLENGTH'] = 75;     // Максимальная длина темы поста/треда.
    $cf['KU_MODLOGDAYS'] = 365;    // Записи лога модераторов старше этого количества дней будут удаляться.
    $cf['KU_NEWTHREADDELAY'] = 1;      // Через сколько секунд после создания треда тот же ip может создать ещё тред.
    $cf['KU_REPLYDELAY'] = 1;      // Через сколько секунд после отправки поста тот же ip может отправить ещё пост.
    $cf['KU_CUTMSGLENGTH'] = 2000;   // Размер поста, после которого на странице доски он будет обрезаться, если включен KU_CUTPOSTS.
    $cf['KU_XHRLOADLIMIT'] = 20;     // Максимальное количество файлов, подготовленных на сервере для загрузки через drag-n-drop. Если этот лимит будет исчерпан, то тому, кто попробует загрузить ещё один файл, будет предложено немного подождать.
    $cf['KU_TEMPFILESCLEAN'] = 600;    // Сколько секунд хранить временные файлы, подготовленные для загрузки через drag-n-drop. Если пользователь загрузил файл, но не отправил пост, то при попытке отправки ему будет предложено загрузить файл снова.
    $cf['KU_THREADS'] = 15;     // Количество тредов на страницу обычных и upload-досок
    $cf['KU_THREADSTXT'] = 15;     // Количество тредов на страницу текстовых досок
    $cf['KU_REPLIES'] = 3;      // Количество показываемых последних ответов в обычном треде на странице доски
    $cf['KU_REPLIESSTICKY'] = 1;      // Количество показываемых последних ответов в прикреплённом треде на странице доски

    // Стили досок. Для каждого стиля обычной или upload-доски в каталоге css/styles должны быть файлы <имя_стиля>.css и menu_<имя_стиля>.css.
    $cf['KU_STYLES'] = 'myata:cdark:claire:claire.advance:cchaos:summer:winter:autumn:photon:modern:dark:tomorrow:urupchan:futaba:burichan:kusabax:gurochan:gentoochan:tuvik:suigintou'; // Список стилей, маленькими буквами, разделяется двоеточием.
    $cf['KU_MENUSTYLES'] = $cf['KU_STYLES']; // Стили для меню.
    $cf['KU_MENUTYPE'] = 'normal';         // Тип меню. 'normal' - с использованием стилей, 'plain' - без них.
    $cf['KU_DEFAULTSTYLE'] = 'photon';         // Дефолтный стиль для досок, на которых он не задан. Маленькими буквами.
    $cf['KU_DEFAULTMENUSTYLE'] = 'photon';         // Дефолтный стиль для меню. Маленькими буквами.
    $stylecolors = array             // Цвета капчи для разных стилей (RGB).
    (
        'Cdark' => array(255, 255, 255),
        'Claire' => array(255, 255, 255),
        'Claire.advance' => array(255, 255, 255),
        'Cchaos' => array(0, 204, 204),
        'Summer' => array(17, 119, 67),
        'Winter' => array(33, 0, 127),
        'Photon' => array(0, 0, 0),
        'Modern' => array(95, 95, 95),
        'Dark' => array(200, 200, 200),
        'Tommorow' => array(255, 255, 255),
        'Urupchan' => array(0, 0, 0),
        'Futaba' => array(128, 0, 0),
        'Burichan' => array(0, 0, 0),
        'Kusabax' => array(0, 0, 0),
        'Gurochan' => array(0, 0, 0),
        'Gentoochan' => array(0, 0, 0),
        'Tuvik' => array(255, 255, 255),
        'default' => array(85, 85, 85)
    );
    $cf['KU_MENUSTYLESWITCHER'] = true;              // Показывать переключалку стилей в меню.
    $cf['KU_STYLESWITCHER'] = true;              // Показывать переключалку стилей на upload-досках.
    $cf['KU_DROPSWITCHER'] = true;              // Показывать переключалку стилей на upload-досках в виде выпадающего списка, а не обычно.
    $cf['KU_TXTSTYLES'] = 'futatxt:buritxt'; // Стили текстовых досок. Для каждого стиля в каталоге css должен быть файл txt_<имя_стиля>.css.
    $cf['KU_DEFAULTTXTSTYLE'] = 'futatxt';         // Дефолтный стиль для текстовых досок. Маленькими буквами.
    $cf['KU_TXTSTYLESWITCHER'] = true;              // Показывать переключалку стилей на текстовых досках.

    // Изображения
    $cf['KU_WIDTHHEIGHTLIMIT'] = 9000; // Максимальная ширина или высота загружаемого изображения.
    $cf['KU_THUMBWIDTH'] = 255;  // Максимальная ширина предпросмотра картинки в ОП-посте.
    $cf['KU_THUMBHEIGHT'] = 255;  // Максимальная высота предпросмотра картинки в ОП-посте.
    $cf['KU_REPLYTHUMBWIDTH'] = 255;  // Максимальная ширина предпросмотра картинки в ответе в тред.
    $cf['KU_REPLYTHUMBHEIGHT'] = 255;  // Максимальная высота предпросмотра картинки в ответе в тред.
    $cf['KU_THUMBMETHOD'] = 'imagemagick+gifsicle'; // Метод создания предпросмотра jpg, gif и png ('gd' или 'imagemagick+gifsicle'). Анимированные предпросмотры доступны только в последнем варианте.
    $cf['KU_THUMBMSG'] = false; // Показывать сообщение "Thumbnail displayed, click image for full size." у предпросмотров картинок.

    // Теги файлов для upload-досок. Для отключения можно установить в ''.
    $cf['KU_TAGS'] = array('Japanese' => 'J',
        'Anime' => 'A',
        'Game' => 'G',
        'Loop' => 'L',
        'Other' => '*');

    // Специальные трипкоды (до 30 символов). Для отключения можно установить в array().
    $cf['KU_TRIPS'] = array('#tripcode1' => 'tripresult1',
        '#tripcode2' => 'tripresult2');

    /******************************************************************************************************/
// Дальше что-то менять, скорее всего, не понадобится.

    // Прочие настройки, заимствованные от 0chan-Kusaba X
    $cf['KU_SUPPORTED_LOCALES'] = 'ru|en';
    $cf['KU_REACT_ENA'] = false;
    $cf['KU_LOCAL_REACT_API'] = 'http://127.0.0.1:1337';
    $cf['KU_REACT_SITENAME'] = 'yourchanid';
    $cf['KU_CLI_REACT_API'] = 'http://example.com:1337';
    $cf['KU_REACT_SRVTOKEN'] = 'ENTERRANDOMSHIT';

    // Версия курисабы
    $cf['KU_VERSION'] = '1.0 based on KusabaX-0.9.3/0chan';

    // Debug
    $cf['KU_DEBUG'] = false; // When enabled, debug information will be printed (Warning: all queries will be shown publicly)

    // Board subdomain/alternate directory (optional, change to enable)
    // DO NOT CHANGE THESE IF YOU DO NOT KNOW WHAT YOU ARE DOING!!
    $cf['KU_BOARDSDIR'] = $cf['KU_ROOTDIR'];
    $cf['KU_BOARDSFOLDER'] = $cf['KU_WEBFOLDER'];
    $cf['KU_BOARDSPATH'] = $cf['KU_WEBPATH'];

    // CGI subdomain/alternate directory (optional, change to enable)
    // DO NOT CHANGE THESE IF YOU DO NOT KNOW WHAT YOU ARE DOING!!
    $cf['KU_CGIDIR'] = $cf['KU_BOARDSDIR'];
    $cf['KU_CGIFOLDER'] = $cf['KU_BOARDSFOLDER'];
    $cf['KU_CGIPATH'] = $cf['KU_BOARDSPATH'];

    // Post-configuration actions, don't modify these
    $cf['KU_TAGS'] = serialize($cf['KU_TAGS']);
    $cf['KU_TRIPS'] = serialize($cf['KU_TRIPS']);

    // Host and domain stuff
    $cf['KU_HOST'] = isset($_SERVER['HTTP_HOST']) ? preg_replace("/[^A-Za-z0-9.:]/", '', $_SERVER['HTTP_HOST']) : $cf['KU_BASE_HOST']; // escape string
    $cf['KU_DOMAIN'] = '.' . $cf['KU_HOST']; // Used in cookies for the domain parameter.  Should be a period and then the top level domain, which will allow the cookies to be set for all subdomains.
    $cf['KU_WEBPATH'] = $cf['KU_PROTO'] . $cf['KU_HOST'];

    if (substr($cf['KU_WEBFOLDER'], -2) == '//') {
        $cf['KU_WEBFOLDER'] = substr($cf['KU_WEBFOLDER'], 0, -1);
    }
    if (substr($cf['KU_BOARDSFOLDER'], -2) == '//') {
        $cf['KU_BOARDSFOLDER'] = substr($cf['KU_BOARDSFOLDER'], 0, -1);
    }
    if (substr($cf['KU_CGIFOLDER'], -2) == '//') {
        $cf['KU_CGIFOLDER'] = substr($cf['KU_CGIFOLDER'], 0, -1);
    }

    $cf['KU_WEBPATH'] = trim($cf['KU_WEBPATH'], '/');
    $cf['KU_BOARDSPATH'] = trim($cf['KU_BOARDSPATH'], '/');
    $cf['KU_CGIPATH'] = trim($cf['KU_CGIPATH'], '/');

    if ($cf['KU_APC']) {
        apc_define_constants('config', $cf);
    }
    while (list($key, $value) = each($cf)) {
        define($key, $value);
    }
    unset($cf);
}

// DO NOT MODIFY BELOW THIS LINE UNLESS YOU KNOW WHAT YOU ARE DOING OR ELSE BAD THINGS MAY HAPPEN
$modules_loaded = array();
$required = array(KU_ROOTDIR, KU_WEBFOLDER, KU_WEBPATH);
if (in_array('CHANGEME', $required) || in_array('', $required)) {
    echo 'You must set KU_ROOTDIR, KU_WEBFOLDER, and KU_WEBPATH before installation will finish!';
    die();
}
require KU_ROOTDIR . 'lib/gettext/gettext.inc.php';
require KU_ROOTDIR . 'lib/adodb/adodb.inc.php';

function style_to_captcha_color($style)
{
    global $stylecolors;
    if (!isset($stylecolors[$style])) $style = 'default';
    return $stylecolors[$style];
}

// Gettext
_textdomain('kusaba');
_setlocale(LC_ALL, KU_LOCALE);
_bindtextdomain('kusaba', KU_ROOTDIR . 'inc/lang');
_bind_textdomain_codeset('kusaba', KU_CHARSET);

// SQL  database
if (!isset($tc_db) && !isset($preconfig_db_unnecessary)) {
    $tc_db = &NewADOConnection(KU_DBTYPE);
    if (KU_DBUSEPERSISTENT) {
        $tc_db->PConnect(KU_DBHOST, KU_DBUSERNAME, KU_DBPASSWORD, KU_DBDATABASE) or die('SQL database connection errors: ' . $tc_db->ErrorMsg() . ' + ' . phpversion());
    } else {
        $tc_db->Connect(KU_DBHOST, KU_DBUSERNAME, KU_DBPASSWORD, KU_DBDATABASE) or die('SQL database connection errorss: ' . $tc_db->ErrorMsg());
    }
    //file_put_contents('connects.log','.',FILE_APPEND);
    $tc_db->Execute("SET NAMES 'utf8mb4'");
    $tc_db->Execute("SET character_set_connection = 'utf8mb4'");
    $tc_db->Execute("SET character_set_results = 'utf8mb4'");
    $tc_db->Execute("SET character_set_client = 'utf8mb4'");
    //$tc_db->SetCharSet('utf8mb4');

    // SQL debug
    if (KU_DEBUG) {
        $tc_db->debug = true;
    }
}


function stripslashes_deep($value)
{
    $value = is_array($value) ?
        array_map('stripslashes_deep', $value) :
        stripslashes($value);
    return $value;
}

// Thanks Z
if (get_magic_quotes_gpc()) {
    $_POST = array_map('stripslashes_deep', $_POST);
    $_GET = array_map('stripslashes_deep', $_GET);
    $_COOKIE = array_map('stripslashes_deep', $_COOKIE);
}
if (get_magic_quotes_runtime()) {
    set_magic_quotes_runtime(0);
}

?>
