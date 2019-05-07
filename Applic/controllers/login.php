<?php
load_game_engine('Public');

class Login_Controller extends PublicController
{

    public $error = NULL;
    public $errorState = -1;
    public $name = NULL;
    public $password = NULL;

    public function __construct()
    {
        parent::__construct();
        if (!is_get("udata")) {
            $this->layoutViewFile = NULL;
        }


        $this->load_model('Login', 'index_model');
    }


    public function index()
    {

        $this->do_login();
        $this->Rdata['error'] = $this->error;
        $this->Rdata['errorState'] = $this->errorState;
        echo json_encode($this->Rdata);

    }


    private function do_login()
    {


        if (is_post('name')) {

            if (trim($_POST['name']) == "") {
                $this->setError(login_result_msg_noname, 1);
            } else {
                $this->name = trim(post('name'));
                if (!is_post('password') || $_POST['password'] == "") {
                    $this->setError(login_result_msg_nopwd, 2);
                } else {
                    $this->password = post('password');
                    $result = $this->index_model->getLoginResult($this->name, $this->password, get_ip());
                    if ($result == NULL) {
                        $this->setError(login_result_msg_notexists, 1);
                    } elseif ($result['hasError']) {
                        $this->setError(login_result_msg_wrongpwd, 2);
                    } elseif (isset($result['data']['is_blocked']) and $result['data']['is_blocked']) {
                        $this->setError(login_result_msg_block, 1);
                    // } elseif (!$result['data']['is_active']) {
                    //     $this->setError(login_result_msg_notactive, 1);
                    } elseif ($result['inserver'] == 1) {

                        $udata = base64_encode($result['data']['id'] . "," . $result['data']['name'] . "," . $this->password . "," . $result['gameStatus'] . "," . $result['data']['is_agent'] . "," . time());
                        $this->Rdata['url'] = "login?udata=" . $udata;
                        $this->Rdata['regdata'] = "";
                        setcookie('server', post('server'), time() + 60 * 60 * 24 * 365, '/');
                    } else {
                        global $gameConfig;
                        $this->load_model('global', 'global_model');
                        $start_time = $this->global_model->getServerStartTime();
                        if (($gameConfig['settings']['RegisterOver'] * 24 * 60 * 60) < $start_time['server_start_time']) {
                            $this->setError(register_player_txt_fullserver, 3);
                        }
                        $this->Rdata['regdata'] = base64_encode($result['data']['id'] . "," . $result['data']['name'] . "," . $this->password . "," . $result['data']['invite_by'] . "," . $result['gameStatus']);
                        setcookie('server', post('server'), time() + 60 * 60 * 24 * 365, '/');
                    }
                }
            }
        } elseif (is_get('reg') AND is_post('dr')) {
            if ($this->global_model->isGameOver()) {
                $this->is_redirect = TRUE;
                redirect("over");
            }
            $dr = explode(',', base64_decode($_POST['dr']));
            $erorr = !isset($_POST['tid']) || $_POST['tid'] != 1 && $_POST['tid'] != 2 && $_POST['tid'] != 3 && $_POST['tid'] != 6 && $_POST['tid'] != 7 ? "<li>" . register_player_txt_choosetribe . "</li>" : "";
            $erorr .= !isset($_POST['kid']) || !is_numeric($_POST['kid']) || $_POST['kid'] < 0 || 4 < $_POST['kid'] ? "<li>" . register_player_txt_choosestart . "</li>" : "";
            $villageName = new_village_name_prefix . " " . $dr[1];
            $this->load_model('Register', 'R');
            if ($erorr == "") {
                $result = $this->R->createNewPlayer($dr[0], $dr[1], $_POST['tid'], $_POST['kid'], $villageName, $this->setupMetadata['map_size'], PLAYERTYPE_NORMAL, 1, get_ip(), $dr[3], $_POST['fid']);

                if ($result['hasErrors']) {
                    $erorr = register_player_txt_fullserver;

                } else {
                    $udata = base64_encode($dr[0] . "," . $dr[1] . "," . $dr[2] . "," . $dr[4] . ",0," . time());
                    $this->Rdata['url'] = "login?udata=" . $udata;

                }
            } else {
                $this->setError($erorr, 1);
                $this->Rdata['regdata'] = $_POST['dr'];
            }


        } elseif (is_get("udata") and get("udata") != null) {

            $userData = explode(',', base64_decode(get("udata")));
            $time = time() - $userData[5];
            if ($time <= 2) {
                $this->load_library('ClientData', 'cookie');
                $this->index_model->updatesession($userData[0]);
                $this->PlayerLibrary->playerId = $userData[0];
                $this->PlayerLibrary->isAgent = $userData[4];
                $this->PlayerLibrary->gameStatus = $userData[3];
                $this->PlayerLibrary->save();
                $this->cookie->uname = $userData[1];
                $this->cookie->upwd = $userData[2];
                $this->cookie->save();
                $this->is_redirect = TRUE;
                redirect("village1");
            } else {
                $this->is_redirect = TRUE;
                redirect("index.php");
            }

            //echo json_encode($userData);
        } else {
            ###########################
            $this->load_library('ClientData', 'cookie');
            if (is_get('dcookie')) {
                $this->cookie->clear();
            }
            $this->is_redirect = TRUE;
            redirect("index.php");
        }
    }


    public function setError($errorMessage, $errorState = -1)
    {
        $this->error = $errorMessage;
        $this->errorState = $errorState;
    }

}

?>