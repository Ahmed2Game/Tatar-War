<?php
/**
 * support class file.
 *
 * @author Abdulrahman Mohamed <abdokamal15@gmail.com>
 * @copyright Copyright 2013
 * @license http://www.xtatar.com
 */

/**
 * Dashboard support page
 *
 * @author Abdulrahman Mohamed <abdokamal15@gmail.com>
 * @version $Id$
 * @package app.controllers
 * @since 1.0
 */
load_core('Admin');
class Support_Controller extends AdminController
{

    /**
     * Constructor Method
     * This method defines template layout && view file and load model
     */
    public function __construct()
    {
        parent::__construct();
        $this->viewFile = "support";
    }


    /**
     * Index Method
     *
     * @return void
     */
    public function index()
    {
        $this->load_model('Support', 'm');

        if($_POST)
        {
            if(is_get('page'))
            {
                switch (get('page')) {
                    case 'read' :
                        $ticketid = post('ticketid');

                        if(is_post('status'))
                        {
                            $status = post('status');
                            $type = post('type');

                            $this->m->change_status_and_type($status, $type, $ticketid);
                        }
                        else
                        {
                            $replay = post('replay');

                            if(empty($replay))
                            {
                                $this->is_redirect = TRUE;
                                redirect('support?page=read&id='.$ticketid);
                            }

                            // add replay
                            global $gameConfig;
                            $sessioninfo = $this->Auth->sessioninfo($_COOKIE['auth_session']);
                            $this->m->add_replay($replay, $gameConfig['system']['adminName'], $ticketid, $sessioninfo['uid'], $sessioninfo['username']);
                        }

                        $this->viewData['ticket'] = $this->m->get_one(get('id'));
                        $this->viewData['replaies'] = $this->m->get_replaies(get('id'));
                        $this->viewData['page'] = 'read';
                    break;

                    default:
                        $this->is_redirect = TRUE;
                        redirect('index.php');
                    break;
                }
            }
        }
        else
        {
            if(is_get('page'))
            {
                if(get('page') == 'show')
                {
                    $status = 'all';
                    if(is_get('status'))
                    {
                        $status = get('status');
                    }
                    $query = $this->m->get_all($status);
                    $this->viewData['tickets'] = $query['results'];
                    $this->viewData['pagination'] = $query['pagination'];
                    $this->viewData['page'] = 'show';
                }
                elseif(get('page') == 'read') {
                    if(is_get('cid'))
                    {
                        $this->m->delete_comment(get('cid'));
                        $this->viewData['flash_message'] = array('success', "تم حذف الرد.");
                    }
                    $this->viewData['ticket'] = $this->m->get_one(get('id'));
                    $this->viewData['replaies'] = $this->m->get_replaies(get('id'));
                    $this->viewData['page'] = 'read';
                }
                elseif(get('page') == 'delete')
                {
                    $this->m->delete(get('id'));
                    $this->viewData['flash_message'] = array('success', "تم حذف التذكرة بنجاح .");

                    $query = $this->m->get_all('all');
                    $this->viewData['tickets'] = $query['results'];
                    $this->viewData['pagination'] = $query['pagination'];
                    $this->viewData['page'] = 'show';
                }
                else {
                    $this->is_redirect = TRUE;
                    redirect('index.php');
                }
            }
            else
            {
                $this->is_redirect = TRUE;
                redirect('index.php');
            }
        }
    }

}
//end file
?>
