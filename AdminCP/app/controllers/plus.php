<?php

load_core('Admin');
class Plus_Controller extends AdminController
{

 /**
  * Constructor Method
  * This method defines template layout && view file and load model
  */
 public function __construct()
 {
  parent::__construct();
  $this->viewFile = "plus";
 }

 /**
  * Index Method
  *
  * @return void
  */
 public function index()
 {
  global $gameConfig;
  $this->viewData['gameConfig'] = $gameConfig;
  $this->load_model('Servers', 'S');
  $this->load_model('Payment', 'P');
  if ($_POST) {
   if (is_get('page')) {
    switch (get('page')) {
     case 'config':
      $plus = array(
       'plus1'  => post('plus1'),
       'plus2'  => post('plus2'),
       'plus3'  => post('plus3'),
       'plus4'  => post('plus4'),
       'plus5'  => post('plus5'),
       'plus6'  => post('plus6'),
       'plus7'  => post('plus7'),
       'plus8'  => post('plus8'),
       'plus9'  => post('plus9'),
       'plus10' => post('plus10'),
      );
      $this->S->UpdatePlus($_COOKIE['server_selected'], json_encode($plus, JSON_FORCE_OBJECT));
      header("Location: plus?page=config");
      break;

     case 'offers':
      if (is_post('id')) {
       $this->P->SetPackage(post('id'), post('name'), post('gold'), post('cost'), post('bonus'), post('image'));
      } else {
       $this->P->CreatePackage(post('name'), post('gold'), post('cost'), post('bonus'), post('image'));
      }
      break;

     case 'payment':
      $G2A = array(
       'name'        => post('name'),
       'image'       => post('image'),
       'merchant_id' => post('merchant_id'),
       'currency'    => post('currency'),
       'bonus'       => post('bonus'),
      );
      $this->S->UpdateSetting("G2A", json_encode($G2A, JSON_FORCE_OBJECT));
      break;
    }
   }
  }

  if (is_get('page')) {
   if (get('page') == 'config') {
    $this->viewData['page'] = 'config';
   } elseif (get('page') == 'offers') {
    if (is_get('id')) {
     $this->viewData['package'] = $this->P->GetPackage(get("id"));
     $this->viewData['page']    = 'edit_offer';
    } else {
     $this->viewData['packages'] = $this->P->GetPackages();
     $this->viewData['page']     = 'offer';
    }
   } elseif (get('page') == 'payment') {
    $this->viewData['page']     = 'payment';
    $this->viewData['payments'] = json_decode($this->S->GetSettings("G2A"), true);
   } else {
    $this->is_redirect = true;
    return header("Location: index.php");
   }
  } else {
   $this->is_redirect = true;
   return header("Location: index.php");
  }
 }

}
//end file
