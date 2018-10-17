<?php
/**
 * AdminController class file.
 *
 * @author Abdulrahman Mohamed <abdokamal15@gmail.com>
 * @copyright Copyright 2013
 * @license http://www.xtatar.com
 */

/**
 * this class uses as a parent class for all dashboard controllers .
 *
 * @author Abdulrahman Mohamed <abdokamal15@gmail.com>
 * @version $Id$
 * @package system.core
 * @since 1.0
 */

class AdminController extends Controller
{
    public $layoutViewFile = 'layout/template';
    public $viewFile = NULL;
    public $viewData = array();
    public $Auth = NULL;
    public $FlashMessages = NULL;
    public $is_redirect = FALSE;

    public function __construct()
    {
        global $loader;
        $this->viewData['controllers'] = $loader->selected_controller;
        load_core('Auth', '');
        $this->Auth = new Auth();

        if (isset($_COOKIE["auth_session"]) && $this->Auth->checksession($_COOKIE["auth_session"]))
        {
            global $servers_list;
            $this->viewData['servers_list'] = $servers_list;

            // Check server connection
            if (db::is_connected() == 0)
            {
                $this->viewData['flash_message'] = array(
                    'error',
                    'خطأ فى الاتصال بقاعدة البيانات',
                    1
                );
            }
            $this->viewData['sessioninfo'] = $this->Auth->sessioninfo($_COOKIE['auth_session']);

            // Check user permissions
            $user        = $this->Auth->getOne($this->viewData['sessioninfo']['uid']);
            $permissions = json_decode($user['permissions'], true);

            if (is_null($permissions))
            {
                $this->viewData['admin'] = TRUE;
            }
            else
            {
                $this->viewData['admin'] = FALSE;
            }

            switch ($loader->selected_controller)
            {
                case 'quicktasks':
                    if ($permissions['allow']['control_tasks'] == '0')
                    {
                        $this->is_redirect = TRUE;
                        redirect('index.php');
                    }
                    break;
                case 'supervisors':
                    if ($permissions['allow']['control_supervisors'] == '0')
                    {
                        $this->is_redirect = TRUE;
                        redirect('index.php');
                    }
                    break;
                case 'players':
                    if ($permissions['allow']['control_players'] == '0')
                    {
                        $this->is_redirect = TRUE;
                        redirect('index.php');
                    }
                    break;
                case 'villages':
                    if ($permissions['allow']['control_villages'] == '0')
                    {
                        $this->is_redirect = TRUE;
                        redirect('index.php');
                    }
                    break;
                case 'alliances':
                    if ($permissions['allow']['control_alliances'] == '0')
                    {
                        $this->is_redirect = TRUE;
                        redirect('index.php');
                    }
                    break;
                case 'payment':
                    if ($permissions['allow']['control_payment'] == '0')
                    {
                        $this->is_redirect = TRUE;
                        redirect('index.php');
                    }
                    break;
                case 'block':
                    if ($permissions['allow']['control_ban_players'] == '0')
                    {
                        $this->is_redirect = TRUE;
                        redirect('index.php');
                    }
                    break;
                case 'support':
                    if ($permissions['allow']['control_support'] == '0')
                    {
                        $this->is_redirect = TRUE;
                        redirect('index.php');
                    }
                    break;
            }

            if ($permissions['allow']['control_tasks'] == '0')
            {
                $this->viewData['quicktasks_allow'] = FALSE;
            }
            else
            {
                $this->viewData['quicktasks_allow'] = TRUE;
            }
            if ($permissions['allow']['control_supervisors'] == '0')
            {
                $this->viewData['supervisors_allow'] = FALSE;
            }
            else
            {
                $this->viewData['supervisors_allow'] = TRUE;
            }
            if ($permissions['allow']['control_players'] == '0')
            {
                $this->viewData['players_allow'] = FALSE;
            }
            else
            {
                $this->viewData['players_allow'] = TRUE;
            }
            if ($permissions['allow']['control_villages'] == '0')
            {
                $this->viewData['villages_allow'] = FALSE;
            }
            else
            {
                $this->viewData['villages_allow'] = TRUE;
            }
            if ($permissions['allow']['control_alliances'] == '0')
            {
                $this->viewData['alliances_allow'] = FALSE;
            }
            else
            {
                $this->viewData['alliances_allow'] = TRUE;
            }
            if ($permissions['allow']['control_payment'] == '0')
            {
                $this->viewData['payment_allow'] = FALSE;
            }
            else
            {
                $this->viewData['payment_allow'] = TRUE;
            }
            if ($permissions['allow']['control_ban_players'] == '0')
            {
                $this->viewData['block_allow'] = FALSE;
            }
            else
            {
                $this->viewData['block_allow'] = TRUE;
            }
            if ($permissions['allow']['control_support'] == '0')
            {
                $this->viewData['support_allow'] = FALSE;
            }
            else
            {
                $this->viewData['support_allow'] = TRUE;
            }
        }
        else
        {
            if ($loader->selected_controller != 'login')
            {
                $this->is_redirect = TRUE;
                redirect('login');
                return;
            }
        }
    }


    public function __destruct()
    {
        if (!$this->is_redirect)
        {
            // Output template
            $tpl = new View;
            $tpl->assign($this->viewData);

            if ($this->layoutViewFile != NULL && $this->viewFile != NULL)
            {
                $tpl->assign('content', $tpl->draw($this->viewFile, $return_string = true));
                $tpl->draw($this->layoutViewFile);
            }
            else if ($this->layoutViewFile != NULL)
            {
                $tpl->draw($this->layoutViewFile);
            }
            else if ($this->viewFile != NULL)
            {
                $tpl->draw($this->viewFile);
            }
        }
    }

}
?>