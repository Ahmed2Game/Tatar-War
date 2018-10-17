<?php
global $debug;
$debug = false;	// set true for debug mode on

global $settings;

date_default_timezone_set("Asia/Riyadh");  // server timezone
$settings['url'] 		= str_replace( basename( $_SERVER['PHP_SELF'] ), '', 'https://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['SCRIPT_NAME']) );
define( "URL", $settings['url'] ); // base url


########## GAME DEFINES #############
define( "PLAYERTYPE_NORMAL", 1 );
define( "PLAYERTYPE_ADMIN", 2 );
define( "PLAYERTYPE_TATAR", 3 );
define( "PLAYERTYPE_HUNTER", 4 );
define( "PLAYERTYPE_ONE", 5 );
define( "PLAYERTYPE_WIN", 6 );
define( "PLAYERTYPE_WINWEEK", 7 );
define( "PLAYERTYPE_WINTATAR", 8 );
define( "GUIDE_QUIZ_NOTSTARTED", NULL );
define( "GUIDE_QUIZ_SUSPENDED", 0 - 2 );
define( "GUIDE_QUIZ_COMPLETED", 0 - 1 );
define( "ALLIANCE_ROLE_SETROLES", 1 );
define( "ALLIANCE_ROLE_REMOVEPLAYER", 2 );
define( "ALLIANCE_ROLE_EDITNAMES", 4 );
define( "ALLIANCE_ROLE_EDITCONTRACTS", 8 );
define( "ALLIANCE_ROLE_SENDMESSAGE", 16 );
define( "ALLIANCE_ROLE_INVITEPLAYERS", 32 );
define( "QS_ACCOUNT_DELETE", 1 );
define( "QS_BUILD_CREATEUPGRADE", 2 );
define( "QS_BUILD_DROP", 3 );
define( "QS_TROOP_RESEARCH", 4 );
define( "QS_TROOP_UPGRADE_ATTACK", 5 );
define( "QS_TROOP_UPGRADE_DEFENSE", 6 );
define( "QS_TROOP_TRAINING", 7 );
define( "QS_TROOP_TRAINING_HERO", 8 );
define( "QS_TOWNHALL_CELEBRATION", 9 );
define( "QS_MERCHANT_GO", 10 );
define( "QS_MERCHANT_BACK", 11 );
define( "QS_WAR_REINFORCE", 12 );
define( "QS_WAR_ATTACK", 13 );
define( "QS_WAR_ATTACK_PLUNDER", 14 );
define( "QS_WAR_ATTACK_SPY", 15 );
define( "QS_CREATEVILLAGE", 16 );
define( "QS_LEAVEOASIS", 17 );
define( "QS_PLUS1", 18 );
define( "QS_PLUS2", 19 );
define( "QS_PLUS3", 20 );
define( "QS_PLUS4", 21 );
define( "QS_PLUS5", 22 );
define( "QS_GUIDENOQUIZ", 23 );
define( "QS_TATAR_RAISE", 24 );
define( "QS_SITE_RESET", 25 );
define( "QS_CROP_DELETE", 26 );
define( "QS_ARTEFACTS_RAISE", 27 );
?>