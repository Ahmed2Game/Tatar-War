<?php

class AdminController extends Controller
{
 public $layoutViewFile = 'layout/template';
 public $viewFile       = null;
 public $viewData       = array();
 public $Auth           = null;
 public $FlashMessages  = null;
 public $is_redirect    = false;

 public function __construct()
 {
  global $loader;
  $this->viewData['controllers'] = $loader->selected_controller;
  load_core('Auth', '');
  $this->Auth = new Auth();

  if (isset($_COOKIE["auth_session"]) && $this->Auth->checksession($_COOKIE["auth_session"])) {
   global $servers_list;
   $this->viewData['servers_list'] = $servers_list;

   // Check server connection
   /* if (db::is_connected()) {
    $this->viewData['flash_message'] = array(
     'error',
     'خطأ فى الاتصال بقاعدة البيانات',
     1,
    );
   } */
   $this->viewData['sessioninfo'] = $this->Auth->sessioninfo($_COOKIE['auth_session']);

   // Check user permissions
   $user        = $this->Auth->getOne($this->viewData['sessioninfo']['uid']);
   $permissions = json_decode($user['permissions'], true);

   if (is_null($permissions)) {
    $this->viewData['admin'] = true;
   } else {
    $this->viewData['admin'] = false;
   }

   switch ($loader->selected_controller) {
    case 'quicktasks':
     if ($permissions['allow']['control_tasks'] == '0') {
      $this->is_redirect = true;
      redirect('index.php');
     }
     break;
    case 'supervisors':
     if ($permissions['allow']['control_supervisors'] == '0') {
      $this->is_redirect = true;
      redirect('index.php');
     }
     break;
    case 'players':
     if ($permissions['allow']['control_players'] == '0') {
      $this->is_redirect = true;
      redirect('index.php');
     }
     break;
    case 'villages':
     if ($permissions['allow']['control_villages'] == '0') {
      $this->is_redirect = true;
      redirect('index.php');
     }
     break;
    case 'alliances':
     if ($permissions['allow']['control_alliances'] == '0') {
      $this->is_redirect = true;
      redirect('index.php');
     }
     break;
    case 'payment':
     if ($permissions['allow']['control_payment'] == '0') {
      $this->is_redirect = true;
      redirect('index.php');
     }
     break;
    case 'block':
     if ($permissions['allow']['control_ban_players'] == '0') {
      $this->is_redirect = true;
      redirect('index.php');
     }
     break;
    case 'support':
     if ($permissions['allow']['control_support'] == '0') {
      $this->is_redirect = true;
      redirect('index.php');
     }
     break;
   }

   if ($permissions['allow']['control_tasks'] == '0') {
    $this->viewData['quicktasks_allow'] = false;
   } else {
    $this->viewData['quicktasks_allow'] = true;
   }
   if ($permissions['allow']['control_supervisors'] == '0') {
    $this->viewData['supervisors_allow'] = false;
   } else {
    $this->viewData['supervisors_allow'] = true;
   }
   if ($permissions['allow']['control_players'] == '0') {
    $this->viewData['players_allow'] = false;
   } else {
    $this->viewData['players_allow'] = true;
   }
   if ($permissions['allow']['control_villages'] == '0') {
    $this->viewData['villages_allow'] = false;
   } else {
    $this->viewData['villages_allow'] = true;
   }
   if ($permissions['allow']['control_alliances'] == '0') {
    $this->viewData['alliances_allow'] = false;
   } else {
    $this->viewData['alliances_allow'] = true;
   }
   if ($permissions['allow']['control_payment'] == '0') {
    $this->viewData['payment_allow'] = false;
   } else {
    $this->viewData['payment_allow'] = true;
   }
   if ($permissions['allow']['control_ban_players'] == '0') {
    $this->viewData['block_allow'] = false;
   } else {
    $this->viewData['block_allow'] = true;
   }
   if ($permissions['allow']['control_support'] == '0') {
    $this->viewData['support_allow'] = false;
   } else {
    $this->viewData['support_allow'] = true;
   }
  } else {
   if ($loader->selected_controller != 'login') {
    $this->is_redirect = true;
    redirect('login');
    return;
   }
  }
 }

 public function __destruct()
 {
  if (!$this->is_redirect) {
   // Output template
   $tpl = new View;
   $tpl->assign($this->viewData);

   if ($this->layoutViewFile != null && $this->viewFile != null) {
    $tpl->assign('content', $tpl->draw($this->viewFile, $return_string = true));
    $tpl->draw($this->layoutViewFile);
   } elseif ($this->layoutViewFile != null) {
    $tpl->draw($this->layoutViewFile);
   } elseif ($this->viewFile != null) {
    $tpl->draw($this->viewFile);
   }
  }
 }

}
