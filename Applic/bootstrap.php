<?php
ob_start();

// set page header
error_reporting(E_ALL);
@ini_set('magic_quotes_runtime', 0);
if ( isset( $_SERVER['HTTP_ACCEPT_ENCODING'] ) && substr_count( $_SERVER['HTTP_ACCEPT_ENCODING'], "gzip" ) )
{
    ob_implicit_flush( 0 );
    if (version_compare(PHP_VERSION, '5.4.0', '>=')) {
      ob_start(null, 0, PHP_OUTPUT_HANDLER_STDFLAGS ^
        PHP_OUTPUT_HANDLER_REMOVABLE);
    } else {
      ob_start(null, 0, false);
    }
}
header( "Date: ".gmdate( "D, d M Y H:i:s" )." GMT" );
header( "Last-Modified: ".gmdate( "D, d M Y H:i:s" )." GMT" );

$tempdata = explode( " ", microtime( ) );
$data1 = $tempdata[0];
$data2 = $tempdata[1];
$__scriptStart = ( double )$data1 + ( double )$data2;



// Init framwork core
require_once LIBRARY_DIR . "Loader.php";
$loader = Loader::get_instance();
$loader->init_settings();           // load the settings
$loader->init_db2();
$m = $loader->load_model('Servers');
// servers

if(isset($_POST['server'])||isset($_COOKIE['server']))
{

    $serv = isset($_POST['server'])  ? $_POST['server'] : $_COOKIE['server'];
    // TODO you need to change xtatar_ to your user name 
    $loader->init_db('xtatar_'.$serv);
    $gameConfig = $m->Serverdata($serv);
    $gameConfig['settings'] = json_decode($gameConfig['settings'], true);
    $gameConfig['plus'] = json_decode($gameConfig['plus'], true);
    $gameConfig['troop'] = json_decode($gameConfig['troop'], true);
    // Load game meta data
    require_once CONFIG_DIR . 'metadata.php';
}
$gameConfig['system'] = json_decode($m->GetSettings("system"), true);
$gameConfig['page'] = json_decode($m->GetSettings("page"), true);
 // connect database

require_once LIBRARY_DIR . "ClientData.php";
$cookie = new ClientData;
$cookie = $cookie->getInstance();
if($cookie->uiLang == "")
{
	$cookie->uiLang = "ar";
}
$loader->init_language($cookie->uiLang);           // set the language
$loader->init_theme();              // set theme
$loader->init_js();

#--------------------------------
# Auto Load the Controller
# init_route set the controller/action/params
# to load the controller
#--------------------------------
$loader->auto_load_controller();


?>