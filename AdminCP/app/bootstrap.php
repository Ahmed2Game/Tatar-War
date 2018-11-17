<?php
ob_start();
session_start();
// set page header
error_reporting(E_ALL);
@ini_set('magic_quotes_runtime', 0);
if ( isset( $_SERVER['HTTP_ACCEPT_ENCODING'] ) && substr_count( $_SERVER['HTTP_ACCEPT_ENCODING'], "gzip" ) )
{
    ob_implicit_flush( 0 );
    if ( @ob_start( array( "ob_gzhandler", 9 ) ) )
    {
        header( "Content-Encoding: gzip" );
    }
}
header( "Date: ".gmdate( "D, d M Y H:i:s" )." GMT" );
header( "Last-Modified: ".gmdate( "D, d M Y H:i:s" )." GMT" );


// Init framwork core
require_once LIBRARY_DIR . "Loader.php";
$loader = Loader::get_instance();
$loader->init_settings();           // load the settings
$loader->init_theme();              // set theme
$loader->init_db2();
$m = $loader->load_model('Servers');
// servers
$servers = $m->ServersList();
$i =0;
$servers_list = array();
foreach($servers as $key => $value)
{
    $servers_list[$i] = $value['id'];
    $i++;
}
if(!isset($_SESSION['server_selected']))
{
	$_SESSION['server_selected'] = current($servers_list);
}


// Load server configurations
$gameConfig = $m->Serverdata($_SESSION['server_selected']);
$gameConfig['settings'] = json_decode($gameConfig['settings'], true);
$gameConfig['plus'] = json_decode($gameConfig['plus'], true);
$gameConfig['troop'] = json_decode($gameConfig['troop'], true);
$gameConfig['page'] = json_decode($m->GetSettings("page"), true);
$gameConfig['system'] = json_decode($m->GetSettings("system"), true);
    // TODO you need to change xtatar_ to your user name 
$loader->init_db('xtatar_'.$_SESSION['server_selected']); // connect database


#--------------------------------
# Auto Load the Controller
# init_route set the controller/action/params
# to load the controller
#--------------------------------
$loader->auto_load_controller();

?>
