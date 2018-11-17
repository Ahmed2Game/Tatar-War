<?php
load_core('Admin');
require_once LIBRARY_DIR . "Cpanel.php";
require CONFIG_DIR . '/db.php';

class Servers_Controller extends AdminController
{

 /**
  * Constructor Method
  * This method defines template layout && view file and load model
  */
 public function __construct()
 {
  parent::__construct();
  $this->viewFile = "servers";
 }

 /**
  * Index Method
  *
  * @return void
  */
 public function index()
 {
  $this->load_model('Servers', 'm');
  global $gameConfig;
  if ($_POST) {
   if (is_post('post_type')) {
    switch (post('post_type')) {
     case 'add':

      $settings = '{"speed":"100","moared":"1","map":"601","attack":"10","protection":"24","protection1":"1","holiday":"1","holidaygold":"500","capacity":"500","cranny":"1000","cp":"100","market":"100","osiss1":"50","osiss2":"150","over":"30","Crop":"90000","Artefacts":"5","RegisterOver":"10","resetTime":"24","wingold":"500000","invinteGold":"1500","piyadeh":"1000","savareh":"500","shovalieh":"300","freegold":"5000","freegold2":"500","pepole":"350","buytroop":"1"}';

      $troops = '{"inTatar":"402300,607600","tatarAtt":"80000,100000","tatarAttM":"20000,30000","inArtef":"20000,30000"}';

      $plus = '{"plus1":"5","plus2":"2","plus3":"20","plus4":"5","plus5":"1","plus6":"1","plus7":"35","plus8":"35","plus9":"1","plus10":"30000"}';

      $id = $this->m->CreateNewServer($settings, $troops, $plus);
      // TODO change cpanel url for create database
      $api = new CPANEL(['url' => 'https://server.knightswar.com:2083', 'username' => post('user'), 'password' => post('password')]);

      $Creatdb = $api->makeRequest('MysqlFE', 'createdb', ['db' => post('user') . '_' . $id]);

      if ($Creatdb->cpanelresult->event->result == 1) {
       $AddUserTodb = $api->makeRequest('MysqlFE', 'setdbuserprivileges', ['privileges' => 'ALL PRIVILEGES', 'db' => post('user') . '_' . $id, 'dbuser' => $username]);
       if ($AddUserTodb->cpanelresult->event->result == 1) {
        $this->viewData['flash_message'] = array('success', "تم اضافة السيرفر بنجاح");
       } else {
        $this->viewData['flash_message'] = array('error', $AddUserTodb->cpanelresult->event->reason);
       }
      } else {
       $this->viewData['flash_message'] = array('error', $Creatdb->cpanelresult->event->reason);
      }
      break;

     case 'reset':

      $this->m->restServer(post('time') * 60 * 60);

      break;
     case 'new':

      $this->load_model('Install', 'I');
      $this->I->processSetup($gameConfig['settings']['map']);
      header("Location: ?page=edit");
      break;

    }
   }
  }
  if (is_get('page')) {
   if (get('page') == 'add') {
    $this->viewData['page'] = 'add';
   } elseif (get('page') == 'edit') {
    $this->viewData['page'] = 'edit';
   } elseif (get('page') == 'show') {
    $this->viewData['servers'] = $this->m->ServersList();
    $this->viewData['page']    = 'show';
   } else {
    return header("Location: index.php");
   }
  } else {
   return header("Location: index.php");
  }
 }

}
//end file
