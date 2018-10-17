<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
// disable register globals
if( ini_get( "register_globals" ) && isset( $_REQUEST ) ) foreach ($_REQUEST as $k => $v) unset($GLOBALS[$k]);

/**
* Load game library
*
* @param engine string
* @return void
*/
function load_game_engine($engine, $type = 'Controller'){
require_once LIBRARY_DIR.'gameEngine/'.$engine.$type.'.php';
}

/**
* Transform timestamp to readable time format
*
* @param int $time unix timestamp
* @param string format of time (use the constant fdate_format or ftime_format)
*/
function time_format( $time=null, $format=DATE_FORMAT ){
return strftime( $format, $time );
}

//-------------------------------------------------------------
//
// INPUT FUNCTIONS
//
//-------------------------------------------------------------
/**
* Safely fetch a $_POST value, defaulting to the value provided if the key is
* not found.
*
* @param string $key name
* @return mixed
*/
function is_post($key)
{
/*if ($_POST)
{
if(!preg_match('/'.$_SERVER['HTTP_HOST'].'/i',$_SERVER['HTTP_REFERER']))
{
redirect('login');
}
}*/
return isset($_POST[$key]);
}

/**
* Safely fetch a $_GET value, defaulting to the value provided if the key is
* not found.
*
* @param string $key name
* @return mixed
*/
function is_get($key)
{
return isset($_GET[$key]);
}


/**
* Get GET input
*/
function get( $key = '')
{
// Check if a field has been provided
if ($key === NULL AND ! empty($_GET))
{
$get = array();

// loop through the full _GET array
foreach (array_keys($_GET) as $key)
{
$get[$key] = fetch_from_array($key);
}
return $get;
}

return fetch_from_array($_GET, $key);
}


/**
* Get POST input
*/
function post( $key = '')
{
// Check if a field has been provided
if ($key === NULL AND ! empty($_POST))
{
$post = array();

// Loop through the full _POST array and return it
foreach (array_keys($_POST) as $key)
{
$post[$key] = fetch_from_array($key);
}
return $post;
}
return fetch_from_array($_POST, $key);
}


function fetch_from_array($array, $key = '')
{
if ( ! isset($array[$key]))
{
return FALSE;
}
return xss_clean($array[$key]);
}

/**
* XSS Clean
*
* Sanitizes data so that Cross Site Scripting Hacks can be
* prevented. This function does a fair amount of work but
* it is extremely thorough, designed to prevent even the
* most obscure XSS attempts. Nothing is ever 100% foolproof,
* of course, but I haven't been able to get anything passed
* the filter.
*
* @param mixed string or array
* @param bool
* @return string
*/
function xss_clean($str)
{
/*
* Is the string an array?
*
*/
if (is_array($str))
{
while (list($key) = each($str))
{
$str[$key] = xss_clean($str[$key]);
}

return $str;
}

//$str = replaceAsciiAndHtmlCharacters($str);


/*
* Remove Invisible Characters
*/
$str = remove_invisible_characters($str);

// Validate Entities in URLs
$str = validate_entities($str);

/*
* URL Decode
*
* Just in case stuff like this is submitted:
*
* <a href="http://%77%77%77%2E%67%6F%6F%67%6C%65%2E%63%6F%6D">Google</a>
*
* Note: Use rawurldecode() so it does not remove plus signs
*
*/
$str = rawurldecode($str);

/*
* Remove Invisible Characters Again!
*/
$str = remove_invisible_characters($str);

/*
* Convert all tabs to spaces
*
* This prevents strings like this: ja vascript
* NOTE: we deal with spaces between characters later.
* NOTE: preg_replace was found to be amazingly slow here on
* large blocks of data, so we use str_replace.
*/
if (strpos($str, "\t") !== FALSE)
{
$str = str_replace("\t", ' ', $str);
}

/*
* Capture converted string for later comparison
*/
$converted_string = $str;

// Remove Strings that are never allowed
$str = do_never_allowed($str);

/*
* Makes PHP tags safe
*/
$str = str_replace(array('<?', '?'.'>'), array('&lt;?', '?&gt;'), $str);

// Remove evil attributes such as style, onclick and xmlns
$str = remove_evil_attributes($str);

/*
* Sanitize naughty scripting elements
*
* Similar to above, only instead of looking for
* tags it looks for PHP and JavaScript commands
* that are disallowed. Rather than removing the
* code, it simply converts the parenthesis to entities
* rendering the code un-executable.
*
* For example: eval('some code')
* Becomes: eval&#40;'some code'&#41;
*/
$str = preg_replace('#(alert|cmd|passthru|eval|exec|expression|system|fopen|fsockopen|file|file_get_contents|readfile|unlink)(\s*)\((.*?)\)#si', "\\1\\2&#40;\\3&#41;", $str);


// Final clean up
// This adds a bit of extra precaution in case
// something got through the above filters
$str = do_never_allowed($str);
return $str;
}

function replaceAsciiAndHtmlCharacters($str)
{
$chunked = str_split($str, 1);
$str = "";
foreach($chunked as $chunk){
$num = ord($chunk);
// Remove non-ascii & non html characters
if ($num >= 32 && $num <= 123){
$str.=$chunk;
}
}
return $str;
}

function remove_invisible_characters($str, $url_encoded = TRUE)
{
$non_displayables = array();

// every control character except newline (dec 10)
// carriage return (dec 13), and horizontal tab (dec 09)

if ($url_encoded)
{
$non_displayables[] = '/%0[0-8bcef]/'; // url encoded 00-08, 11, 12, 14, 15
$non_displayables[] = '/%1[0-9a-f]/'; // url encoded 16-31
}

$non_displayables[] = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S'; // 00-08, 11, 12, 14-31, 127

do
{
$str = preg_replace($non_displayables, '', $str, -1, $count);
}
while ($count);

return $str;
}

function validate_entities($str)
{
/*
* Protect GET variables in URLs
*/

// 901119URL5918AMP18930PROTECT8198

$str = preg_replace('|\&([a-z\_0-9\-]+)\=([a-z\_0-9\-]+)|i', xss_hash()."\\1=\\2", $str);

/*
* Validate standard character entities
*
* Add a semicolon if missing. We do this to enable
* the conversion of entities to ASCII later.
*
*/
$str = preg_replace('#(&\#?[0-9a-z]{2,})([\x00-\x20])*;?#i', "\\1;\\2", $str);

/*
* Validate UTF16 two byte encoding (x00)
*
* Just as above, adds a semicolon if missing.
*
*/
$str = preg_replace('#(&\#x?)([0-9A-F]+);?#i',"\\1\\2;",$str);

/*
* Un-Protect GET variables in URLs
*/
$str = str_replace(xss_hash(), '&', $str);

return $str;
}

/**
* Random Hash for protecting URLs
*
* @return string
*/
function xss_hash()
{
mt_srand();
return md5(time() + mt_rand(0, 1999999999));
}

/**
* Do Never Allowed
*
* A utility function for xss_clean()
*
* @param string
* @return string
*/
function do_never_allowed($str)
{
$never_allowed_str = array(
'document.cookie' => '',
'document.write' => '',
'.parentNode' => '',
'.innerHTML' => '',
'window.location' => '',
'-moz-binding' => '',
'script' => '',
'<!--' => '',
'-->' => '',
'<![CDATA[' => '',
'<' => '',
'>' => '',
'&lt;' => '',
'&gt;' => '',
'<comment>' => ''
);
$never_allowed_regex = array(
'javascript\s*:',
'expression\s*(\(|&\#40;)', // CSS and IE
'vbscript\s*:', // IE, surprise!
'Redirect\s+302',
"([\"'])?data\s*:[^\\1]*?base64[^\\1]*?,[^\\1]*?\\1?"
);
$str = str_replace(array_keys($never_allowed_str), $never_allowed_str, $str);

foreach ($never_allowed_regex as $regex)
{
$str = preg_replace('#'.$regex.'#is', ' ', $str);
}

return $str;
}

/*
* Remove Evil HTML Attributes (like evenhandlers and style)
*
* It removes the evil attribute and either:
* - Everything up until a space
* For example, everything between the pipes:
* <a |style=document.write('hello');alert('world');| class=link>
* - Everything inside the quotes
* For example, everything between the pipes:
* <a |style="document.write('hello'); alert('world');"| class="link">
*
* @param string $str The string to check
* @param boolean $is_image TRUE if this is an image
* @return string The string with the evil attributes removed
*/
function remove_evil_attributes($str)
{
// All javascript event handlers (e.g. onload, onclick, onmouseover), style, and xmlns
$evil_attributes = array('on\w*', 'style', 'xmlns', 'formaction');

do {
$count = 0;
$attribs = array();

// find occurrences of illegal attribute strings without quotes
preg_match_all('/('.implode('|', $evil_attributes).')\s*=\s*([^\s>]*)/is', $str, $matches, PREG_SET_ORDER);

foreach ($matches as $attr)
{

$attribs[] = preg_quote($attr[0], '/');
}

// find occurrences of illegal attribute strings with quotes (042 and 047 are octal quotes)
preg_match_all("/(".implode('|', $evil_attributes).")\s*=\s*(\042|\047)([^\\2]*?)(\\2)/is", $str, $matches, PREG_SET_ORDER);

foreach ($matches as $attr)
{
$attribs[] = preg_quote($attr[0], '/');
}

// replace illegal attribute strings that are inside an html tag
if (count($attribs) > 0)
{
$str = preg_replace("/<(\/?[^><]+?)([^A-Za-z<>\-])(.*?)(".implode('|', $attribs).")(.*?)([\s><])([><]*)/i", '<$1 $3$5$6$7', $str, -1, $count);
}

} while ($count);

return $str;
}

//-------------------------------------------------------------
//
// EMAIL FUNCTIONS
//
//-------------------------------------------------------------

/**
* Send an email
* @param $to
*/
function send_mail( $to, $from, $subject, $message, $sname, $rname )
{

require LIBRARY_DIR.'PHPMailer/src/Exception.php';
require LIBRARY_DIR.'PHPMailer/src/PHPMailer.php';
require LIBRARY_DIR.'PHPMailer/src/SMTP.php';
$mail = new PHPMailer(true);                              // Passing `true` enables exceptions
    //Server settings
    $sname ="=?UTF-8?B?".base64_encode($sname)."?=\n"; // اسم المرسل
    $rname ="=?UTF-8?B?".base64_encode($rname)."?=\n"; // اسم المستقبل
    $subject = sprintf( "=?utf-8?B?".base64_encode( $subject )."?=" );
    $mail->CharSet = 'UTF-8';
    $mail->SMTPDebug = 0;                                 // Enable verbose debug output
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'server.xtatar.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'mails@xtatar.com';                 // SMTP username
    $mail->Password = 'Ahmed@1993222';                           // SMTP password
    $mail->SMTPSecure = '';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587;  // TCP port to connect to
    $mail->SMTPOptions = array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
        )
    );

    //Recipients
    $mail->setFrom($mail->Username, $sname);
    $mail->addAddress($to, $rname);     // Add a recipient 
    $mail->addReplyTo($from, $sname);

    //Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $message;
    $mail->AltBody = '';

    $mail->send();
}

//-------------------------------------------------------------
//
// Language
//
//-------------------------------------------------------------
function load_lang( $file ){
require_once LANGUAGE_DIR . LANG_ID . "/" . $file . ".php";
}

//-------------------------------------------------------------
//
// Javascript & CSS
//
//-------------------------------------------------------------

//style sheet and javascript
global $style, $script, $javascript, $javascript_onload;
$style = $script = array();
$javascript = $javascript_onload = "";


//add style sheet
function add_style( $file, $dir = CSS_DIR, $url = null ){
if( !$url )
$url = URL . $dir;
echo $GLOBALS['style'][$dir . $file] = $url . $file;
}

//add javascript file
function add_script( $file, $dir = JAVASCRIPT_DIR, $url = null ){
if( !$url )
$url = URL . $dir;
$GLOBALS['script'][$dir . $file] = $url . $file;
}

//add javascript code
function add_javascript( $javascript, $onload = false ){
if( !$onload )
$GLOBALS['javascript'] .= "\n".$javascript."\n";
else
$GLOBALS['javascript_onload'] .= "\n".$javascript."\n";
}

/**
* get javascript
*/
function get_javascript( $compression = false ){
global $script, $javascript, $javascript_onload;
$html = "";
if( $script ){

if( $compression ){
$js_file = "";
foreach( $script as $file => $url)
$js_file .= "$url,";
$html = '<script src="/js.php?'.$js_file.'" type="text/javascript"></script>' . "\n";

}
else{
foreach( $script as $s )
$html .= '<script src="'.$s.'" type="text/javascript"></script>' . "\n";
}

}
if( $javascript_onload ) $javascript .= "\n" . "$(function(){" . "\n" . " $javascript_onload" . "\n" . "});" . "\n";
if( $javascript ) $html .= "<script type=\"text/javascript\">" . "\n" .$javascript . "\n" . "</script>";
return $html;
}

/**
* get the style
*/
function get_style( $compression = false ){
global $style;
$html = "";

if( $style ){

if( $compression ){
$css_file = "";
foreach( $style as $file => $url)
$css_file .= "$url,";
$html = ' <link rel="stylesheet" href="/css.php?'.$css_file.'" type="text/css" />' . "\n";
}
else{
foreach( $style as $file => $url)
$html .= ' <link rel="stylesheet" href="'.$url.'" type="text/css" />' . "\n";
}

}

return $html;

}


//-------------------------------------------------------------
//
// LOCALIZATION FUNCTIONS
//
//-------------------------------------------------------------

/*function get_ip(){
if( !defined("IP") ){
$ip = getenv( "HTTP_X_FORWARDED_FOR" ) ? getenv( "HTTP_X_FORWARDED_FOR" ) : getenv( "REMOTE_ADDR" );
if( !preg_match("^([1-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])(\.([0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])){3}^", $ip ) ) $ip = null;
define( "IP", $ip );
}
return IP;
}*/
function get_ip( )
{
$ip = "";
if ( isset( $_SERVER['REMOTE_ADDR'] ) )
{
$ip = $_SERVER['REMOTE_ADDR'];
}
else if ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) )
{
$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
}
else if ( isset( $_SERVER['HTTP_CLIENT_IP'] ) )
{
$ip = $_SERVER['HTTP_CLIENT_IP'];
}
return $ip;
}
/**
* Return true if $ip is a valid ip
*/
function is_ip($ip){
return preg_match("^([1-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])(\.([0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])){3}^", $ip );
}

############ Game functions #######################

function merge_manual_text($text1, $text2)
{

return constant($text1.'_'.$text2);
}

// will move to gameHelpers.php

function redirect( $url )
{
header( "location: ".URL.$url );
exit( 0 );
}

function getPlayerKey()
{

return md5( getDomain() );
}

function getDomain( )
{
$surl = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$surl = preg_replace( "/^(www\\.)/", "", $surl );
$arr = explode( "/", $surl );
$count = sizeof( $arr ) - 1;
if ( 0 < $count )
{
$surl = "";
$i = 0;
while ( $i < $count )
{
$surl .= $arr[$i]."/";
++$i;
}
}
return strtolower( $surl );
}

function secondsToString( $seconds )
{
$seconds = intval( $seconds );
$h = floor( $seconds / 3600 );
$m = floor(($seconds - (floor( $seconds / 3600 ) * 3600)) / 60 );
$s = floor($seconds - (floor( $seconds / 60 ) * 60));
if ( $h < 0 || $m < 0 || $s < 0 )
{
return "0:00:00<a href=\"#\" onclick=\"return showManual(7,0);\">?</a>";
}
return $h.":".( $m < 10 ? "0" : "" ).$m.":".( $s < 10 ? "0" : "" ).$s;
}

function getdistance($coorx1, $coory1, $coorx2, $coory2, $map_size)
{
$xdistance = ABS($coorx2 - $coorx1);
if ($xdistance > floor($map_size))
{
$xdistance = (2*$map_size) - $xdistance;
}
$ydistance = ABS($coory2 - $coory1);
if ($ydistance > floor($map_size))
{
$ydistance = (2*$map_size) - $ydistance;
}
$distance = SQRT(POW($xdistance, 2) + POW($ydistance, 2));
return $distance;
}
// -- end