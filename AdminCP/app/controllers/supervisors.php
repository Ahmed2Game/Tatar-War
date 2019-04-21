<?php

load_core('Admin');

class Supervisors_Controller extends AdminController
{

    /**
     * Constructor Method
     * This method defines template layout && view file and load model
     */
    public function __construct()
    {
        parent::__construct();
        $this->viewFile = "supervisors";
    }


    /**
     * Index Method
     *
     * @return void
     */
    public function index()
    {
        if ($_POST) {
            if (is_post('post_type')) {
                switch (post('post_type')) {
                    case 'register':
                        $username = post('username');
                        $password = post('password');
                        $email = post('email');
                        $active = post('active');

                        $permissions['allow'] = array(
                            'control_tasks' => post('control_tasks'),
                            'control_supervisors' => post('control_supervisors'),
                            'control_players' => post('control_players'),
                            'control_villages' => post('control_villages'),
                            'control_alliances' => post('control_alliances'),
                            'control_payment' => post('control_payment'),
                            'control_ban_players' => post('control_ban_players'),
                            'control_support' => post('control_support')
                        );

                        $permissions['support'] = array(
                            'cat_1' => post('cat_1'),
                            'cat_2' => post('cat_2'),
                            'cat_3' => post('cat_3'),
                            'cat_4' => post('cat_4')
                        );

                        if ($this->Auth->register($username, $password, $password, $email, $permissions)) {
                            $successmsg = '';
                            foreach ($this->Auth->successmsg as $suc) {
                                $successmsg .= $suc . '<br />';
                            }
                            $this->viewData['flash_message'] = array('success', $successmsg);
                        } else {
                            $errormsg = '';
                            foreach ($this->Auth->errormsg as $err) {
                                $errormsg .= $err . '<br />';
                            }
                            $this->viewData['flash_message'] = array('error', $errormsg);
                        }
                        $this->viewData['page'] = 'register';
                        break;

                    case 'update' :
                        $userid = post('userid');
                        $username = post('username');
                        $password = post('password');
                        $email = post('email');

                        if ($userid != '1') {
                            $active = post('active');

                            $permissions['allow'] = array(
                                'control_tasks' => post('control_tasks'),
                                'control_supervisors' => post('control_supervisors'),
                                'control_players' => post('control_players'),
                                'control_villages' => post('control_villages'),
                                'control_alliances' => post('control_alliances'),
                                'control_payment' => post('control_payment'),
                                'control_ban_players' => post('control_ban_players'),
                                'control_support' => post('control_support')
                            );

                            $permissions['support'] = array(
                                'cat_1' => post('cat_1'),
                                'cat_2' => post('cat_2'),
                                'cat_3' => post('cat_3'),
                                'cat_4' => post('cat_4')
                            );

                            $update = $this->Auth->update($username, $password, $email, $active, $permissions, $userid);
                        } else {
                            $update = $this->Auth->update($username, $password, $email, 1, 'all', 1);
                        }


                        if ($update) {
                            $this->Auth->updatesession($username, $userid);


                            $successmsg = '';
                            foreach ($this->Auth->successmsg as $suc) {
                                $successmsg .= $suc . '<br />';
                            }
                            $this->viewData['flash_message'] = array('success', $successmsg);
                        } else {
                            $errormsg = '';
                            foreach ($this->Auth->errormsg as $err) {
                                $errormsg .= $err . '<br />';
                            }
                            $this->viewData['flash_message'] = array('error', $errormsg);
                        }
                        $this->viewData['supervisors'] = $this->Auth->getAll();
                        $this->viewData['page'] = 'show';
                        break;

                    default:
                        redirect('index.php');
                        break;
                }
            }
        } else {
            if (is_get('page')) {
                if (get('page') == 'register') {
                    $this->viewData['page'] = 'register';
                } elseif (get('page') == 'show') {
                    $this->viewData['supervisors'] = $this->Auth->getAll();
                    $this->viewData['page'] = 'show';
                } elseif (get('page') == 'edit') {
                    $this->viewData['supervisor'] = $this->Auth->getOne(get('id'));
                    $this->viewData['page'] = 'edit';
                } elseif (get('page') == 'delete') {
                    if ($this->Auth->deleteaccount(get('id'))) {
                        $this->viewData['flash_message'] = array('success', "تم حذف المشرف بنجاح .");
                    } else {
                        $errormsg = '';
                        foreach ($this->Auth->errormsg as $err) {
                            $errormsg .= $err . '<br />';
                        }
                        $this->viewData['flash_message'] = array('error', $errormsg);
                    }
                    $this->viewData['supervisors'] = $this->Auth->getAll();
                    $this->viewData['page'] = 'show';
                } else {
                    redirect('index.php');
                }
            } else {
                redirect('index.php');
            }
        }
    }

}

//end file
?>
