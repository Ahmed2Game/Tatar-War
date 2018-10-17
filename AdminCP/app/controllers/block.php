<?php
/**
 * Block class file.
 *
 * @author Abdulrahman Mohamed <abdokamal15@gmail.com>
 * @copyright Copyright 2013
 * @license http://www.xtatar.com
 */

/**
 * Dashboard Block page
 *
 * @author Abdulrahman Mohamed <abdokamal15@gmail.com>
 * @version $Id$
 * @package app.controllers
 * @since 1.0
 */
load_core('Admin');
class Block_Controller extends AdminController
{

    /**
     * Constructor Method
     * This method defines template layout && view file and load model
     */
    public function __construct()
    {
        parent::__construct();
        $this->viewFile = "block";
    }


    /**
     * Index Method
     *
     * @return void
     */
    public function index()
    {
        $this->load_model('Block', 'm');
        if ($_POST)
        {
            if (is_post('username') && post('username') != '')
            {
                $this->viewData['b'] = $this->m->getBlockPlayerPyName(post('username'));
            }
            elseif(is_post('username'))
            {
                $this->viewData['e'] = TRUE;
            }

            if (is_post('name'))
            {
                $blocked = $this->m->UpdatePlayerPainTime(post('name'), post('time')*3600, post('blocked_reason'));
                if ($blocked)
                {
                    $this->viewData['status'] = 'success';
                    $this->viewData['message'] = 'تم حظر اللاعب '.post('name').' عدد '.post('time').' ساعة';
                }
                else
                {
                    $this->viewData['status'] = 'error';
                    $this->viewData['message'] = 'هذا اللعب غير موجود';
                }
            }
        }

    }
}