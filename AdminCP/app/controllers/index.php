<?php

load_core('Admin');

class Index_Controller extends AdminController
{

    /**
     * Constructor Method
     * This method defines template layout && view file and load model
     */
    public function __construct()
    {
        parent::__construct();
        $this->viewFile = "home";
    }

    /**
     * Index Method
     *
     * This method defines template layout && view file and load model
     * @return void
     */
    public function index()
    {
        // change server + admin change pass email
        if (is_post('change_server')) {
            $new_server_name = post('change_server');
            setcookie('server_selected', $new_server_name, time() + 60 * 60 * 24 * 365, '/');
            $this->is_redirect = true;
            redirect('index.php');
        } elseif (get('page') == 'logout') {
            $this->Auth->deletesession($_COOKIE['auth_session']);
            $this->is_redirect = true;
            redirect('login');
        }

    }

}
//end file
