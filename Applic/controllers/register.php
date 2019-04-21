<?php
load_game_engine('Public');

class Register_Controller extends PublicController
{

    public $err = array(
        0 => "",
        1 => "",
        2 => "",
        3 => ""
    );
    public $UserID = 0;

    public function __construct()
    {
        parent::__construct();

        global $gameConfig;

        $this->layoutViewFile = NULL;
    }

    public function index()
    {
        $this->Rdata['success'] = false;
        // Check if form submitted ..
        if (is_post('name')) {
            $this->do_register(get('ref'));
        } else {
            $this->is_redirect = TRUE;
            redirect("index");
        }
        $this->Rdata['err'] = $this->err;
        echo json_encode($this->Rdata);
    }


    private function do_register($ref)
    {
        global $gameConfig;

        if (is_post('name')) {
            $name = post('name');
            $email = post('email');
            $pwd = post('pwd');
            $Ip = get_ip();

            if ($ref != 0) {
                $Invite = $_GET['ref'];
            } else {
                $Invite = 0;
            }

            $this->err[0] = strlen($name) < 5 ? register_player_txt_notless3 : "";

            if ($this->err[0] == "") {
                $this->err[0] = preg_match("/[:,\\. \\<\\>\\n\\r\\t\\s]+/", $name) ? register_player_txt_invalidchar : "";
            }
            if ($name == "[ally]" || $name == "admin" || $name == "Admin" || $name == "administrator"
                || $name == "Administrator" || $name == "multihunter" || $name == "Multihunter"
                || $name == "tatar" || $name == "Tatar" || $name == "الـدعم" || $name == "الدعـم"
                || $name == "الاداره" || $name == "الأدارة" || $name == "الأداره"
                || $name == $gameConfig['system']['adminName'] || $name == tatar_tribe_player || $name == "التحف") {
                $this->err[0] = register_player_txt_reserved;
            }
            if (strlen($name) > 15) {
                $this->err[0] = register_player_txt_invalidchar;
            }
            if ($name != htmlspecialchars($name)) {
                $this->err[0] = register_player_txt_invalidchar;
            }
            $this->err[1] = !preg_match("/^[^@]+@[a-zA-Z0-9._-]+\\.[a-zA-Z]+\$/", $email) ? register_player_txt_invalidemail : "";
            $this->err[2] = strlen($pwd) < 6 ? register_player_txt_notless6 : "";
            $this->err[2] = !preg_match('/^[A-Za-z]/', $pwd) ? register_player_txt_invalidbas : "";
            if (0 < strlen($this->err[0]) || 0 < strlen($this->err[1]) || 0 < strlen($this->err[2])) {
                return;
            }

            $this->load_model('Register', 'm');
            $playerdata = $this->m->isPlayerNameExists($name, $email);
            $this->err[0] = ($playerdata['master']['name']) ? register_player_txt_usedname : "";
            $this->err[1] = ($playerdata['master']['email']) ? register_player_txt_usedemail : "";
            $this->load_model('Servers', 'S');
            $blockEmail = explode(',', $this->S->GetSettings("blocked_email"));
            if (in_array($email, $blockEmail)) {
                $this->err[1] = register_player_txt_usedemail;
            }

            if ($this->m->isPlayerMultiReg($Ip)) {
                $this->err[0] = register_player_txt_invalidchar;
                $this->err[1] = register_player_txt_invalidemail;
            }

            if (0 < strlen($this->err[0]) || 0 < strlen($this->err[1])) {

            } else {
                $result = $this->m->createMaster($name, $email, $pwd, $Invite, 0, $gameConfig['settings']['freegold']);
                if ($result['result'] <= 0) {
                    $this->err[3] = register_player_txt_fullserver;
                } else {
                    $link = URL . "index?active=" . $result['activationCode'];
                    $to = $email;
                    $from = $gameConfig['system']['email'];
                    $subject = register_player_txt_regmail_sub;
                    $message = sprintf(register_player_txt_regmail_body, $name, $name, $pwd, $link, $link);

                    send_mail($to, $from, $subject, $message, $gameConfig['page'][$gameConfig['system']['lang'] . '_title'], $name);
                    $this->Rdata['success'] = TRUE;

                    $this->Rdata['username'] = $name;
                    $this->Rdata['email'] = $email;
                }
            }

        }
    }

}

?>