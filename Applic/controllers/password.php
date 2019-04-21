<?php
load_game_engine('Public');

class Password_Controller extends PublicController
{
    public $pageState = -1;
    public $playerId;

    public function __construct()
    {
        parent::__construct();
        $this->layoutViewFile = NULL;
    }

    public function index()
    {

        $this->load_model('Password', 'm');
        $Data = array();
        $Data['error'] = null;
        if (is_post('email')) {
            global $gameConfig;
            $email = post('email');
            $result = $this->m->PlayerDataPyEmail($email);
            if ($result != null) {
                $link = URL . "index?key=" . $result['email'] . "&key2=" . $result['pwd'];
                $to = $email;
                $from = $gameConfig['system']['email'];
                $subject = forget_password_subject;
                $message = sprintf(forget_password_body, $result['name'], $result['name'], $link, $link);
                send_mail($to, $from, $subject, $message, $gameConfig['page'][$gameConfig['system']['lang'] . '_title'], $result['name']);
                $Data['success'] = true;
            } else {
                $Data['success'] = false;
                $Data['error'] = register_player_txt_invalidemail;
            }
        } else if (is_post('password') && is_get('key') && is_get('key2')) {
            $validpass = strlen((string)post('password')) < 6 ? register_player_txt_notless6 : "";
            if ($validpass == "") {
                $count = $this->m->setPlayerPassword(get('key'), get('key2'), post('password'));
                if ($count) {
                    $Data['success'] = true;
                } else {
                    $Data['success'] = false;
                }
            } else {
                $Data['success'] = false;
                $Data['error'] = $validpass;
            }

        } else {
            $this->is_redirect = TRUE;
            redirect("index");
        }
        echo json_encode($Data);

    }
}

?>