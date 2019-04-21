<?php

//-------------------------------------------------------------
//
//					 Controller
//
//-------------------------------------------------------------
define("CONTROLLER_EXTENSION", "php");
define("CONTROLLER_CLASS_NAME", "_Controller");

//-------------------------------------------------------------
//
//					 User Info
//
//-------------------------------------------------------------

// get user IP
$IP = getenv("HTTP_X_FORWARDED_FOR") ? getenv("HTTP_X_FORWARDED_FOR") : getenv("REMOTE_ADDR");
if (!preg_match("^([1-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])(\.([0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])){3}^", $IP)) $IP = null;
// browser calculation
$known = array('msie', 'firefox', 'safari', 'webkit', 'opera', 'netscape', 'konqueror', 'gecko');
preg_match('#(' . join('|', $known) . ')[/ ]+([0-9]+(?:\.[0-9]+)?)#', strtolower($_SERVER['HTTP_USER_AGENT']), $br);
preg_match_all('#\((.*?);#', $_SERVER['HTTP_USER_AGENT'], $os);
if (isset($br[1][1])) $browser = $br[1][1]; else $browser = null;
if (isset($br[2][1])) $version = $br[2][1]; else $version = null;

define("IP", $IP);
define("BROWSER_LANG_ID", substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2));
define("BROWSER", $browser);
define("BROWSER_VERSION", $version);
//define( "BROWSER_OS", $os[1][0] );
define("USER_ONLINE_TIME", 150);    // user is considered online before 3 minutes of inactivity

//time
define("TIME", time());        // timestamp
define("SECOND", 1);
define("MINUTE", 60);            // seconds in minute
define("HOUR", 3600);        // seconds in hour
//define( "DAY"		, 86400 );		// seconds in day
define("WEEK", 604800);        // seconds in week
define("MONTH", 2592000);    // seconds in month
define("YEAR", 31536000);    // seconds in year
define("LEAP_YEAR", 31622400);    // seconds in leap year (every 4 year when february has 29 days)
?>