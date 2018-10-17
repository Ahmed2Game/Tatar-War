<?php
  require_once LIBRARY_DIR.'gameEngine/PublicController.php';
  class Manual_Controller extends PublicController
  {
      public function __construct()
      {
          parent::__construct();
          $this->viewData['contentCssClass'] = 'manual';
          $this->viewFile = 'manual';
      }

      public function index ()
      {
          if (!is_get('t'))
          {
             $tab = 1;
          }
          else
          {
             $this->viewData['tab'] = get('t');
          }
      }

  }
?> 