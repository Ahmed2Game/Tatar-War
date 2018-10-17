<?php 
load_game_engine('Public');
class News_Controller extends PublicController
{
    public function __construct()
    {
        parent::__construct();
        $this->viewFile = 'news';
    }
    
    public function index ()
    {
        $this->load_model('News', 'N');
        $allnews = $this->N->get_all($this->viewData['lang']);
        if($_GET['id']){
           $this->viewData['one'] = $this->N->get_row( $_GET['id'] );
        }
        $this->viewData['all'] = $allnews;
        
    }
}
?>