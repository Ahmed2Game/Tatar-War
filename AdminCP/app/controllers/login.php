<?php

load_core('Admin');
class Login_Controller extends AdminController
{

 /**
  * Constructor Method
  * This method defines template layout && view file and load model
  */
 public function __construct()
 {
  parent::__construct();

  $this->layoutViewFile = false;
  $this->viewFile       = "login";
 }

 /**
  * Index Method
  *
  * @return void
  */
 public function index()
 {
  if (is_post('email')) {
   $email    = post("email");
   $password = post("password");

   $login = $this->Auth->login($email, $password);
   if ($login) {
    $this->is_redirect = true;
    redirect('index.php');
   } else {
    $errormsg = '';
    foreach ($this->Auth->errormsg as $err) {
     $errormsg .= $err . '<br />';
    }
    $this->viewData['flash_message'] = array('error', $errormsg);
   }
  }
 }

}
//end file
