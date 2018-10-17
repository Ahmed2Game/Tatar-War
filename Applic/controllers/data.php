<?php 
load_game_engine('Public');
class Data_Controller extends PublicController
{
    public function __construct()
    {
        parent::__construct();
        $this->viewFile = null;
        $this->layoutViewFile = null;
        echo json_encode($this->viewData);
    }
    
    public function index ()
    {
        
        
    }
}
?>