<?php
/**
 * index class file.
 *
 * @author Abdulrahman Mohamed <abdokamal15@gmail.com>
 * @copyright Copyright 2013
 * @license http://www.xtatar.com
 */

/**
 * Dashboard index page
 *
 * @author Abdulrahman Mohamed <abdokamal15@gmail.com>
 * @version $Id$
 * @package app.controllers
 * @since 1.0
 */
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
        if(is_post('change_server'))
        {
            $new_server_name = post('change_server');
            $_SESSION['server_selected'] = $new_server_name;
            $this->is_redirect = TRUE;
            redirect('index.php');
        }
        elseif(get('page') == 'logout')
        {
            $this->Auth->deletesession($_COOKIE['auth_session']);
            $this->is_redirect = TRUE;
            redirect('login');
        }

    }

}
//end file
?>
