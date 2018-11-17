<?php

load_core('Admin');
class Messages_Controller extends AdminController
{

 /**
  * Constructor Method
  * This method defines template layout && view file and load model
  */
 public function __construct()
 {
  parent::__construct();
  $this->viewFile = "messages";
 }

 /**
  * Index Method
  *
  * @return void
  */
 public function index()
 {
  if ($_POST) {
   $this->load_model('Messages', 'm');
   if (is_post('username')) {
    $excuteDelete = $this->m->deleteMessagesByUserName(post('username'));

    if ($excuteDelete) {
     $this->viewData['notice']  = true;
     $this->viewData['status']  = 'success';
     $this->viewData['message'] = 'تم مسح الرسائل الخاصة بالعضو';
    } else {
     $this->viewData['notice']  = true;
     $this->viewData['status']  = 'error';
     $this->viewData['message'] = 'لم نجد رسائل لهذا العضو !!';
    }
   }
  } else {
   $this->viewData['page'] = 'show';
  }
 }

}
//end file
