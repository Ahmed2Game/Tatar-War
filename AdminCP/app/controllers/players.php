<?php

load_core('Admin');

class Players_Controller extends AdminController
{

    /**
     * Constructor Method
     * This method defines template layout && view file and load model
     */
    public function __construct()
    {
        parent::__construct();
        $this->viewFile = "players";
    }

    /**
     * Index Method
     *
     * @return void
     */
    public function index()
    {
        $this->load_model('players', 'm');
        global $gameConfig;
        if ($_POST) {
            if (is_get('page')) {
                switch (get('page')) {
                    case 'update':
                        $this->m->UpdatePlayerData(intval($_POST['id']), intval($_POST['tribe_id']), intval($_POST['alliance_id']), trim($_POST['alliance_name']), post('alliance_roles'), trim($_POST['name']), intval($_POST['is_blocked']), intval($_POST['player_type']), intval($_POST['active_plus_account']), trim($_POST['ip_his']), intval($_POST['total_people_count']), intval($_POST['villages_count']), trim($_POST['villages_id']), intval($_POST['hero_troop_id']), intval($_POST['hero_level']), intval($_POST['hero_points']), trim($_POST['hero_name']), intval($_POST['hero_in_village_id']), intval($_POST['attack_points']), intval($_POST['defense_points']), trim($_POST['week_attack_points']), intval($_POST['week_defense_points']), intval($_POST['week_dev_points']), intval($_POST['week_thief_points']));
                        $_GET['page'] = 'edit';
                        $_GET['id'] = post('id');
                        $this->viewData['sc'] = true;
                        break;
                    case 'update2':
                        if (post('pwd') == '') {
                            $pwd = $this->m->GetPlayerPwd(post('id'));
                        } else {
                            $pwd = md5(trim(post('pwd')));
                        }
                        $this->m->UpdateMplayer(intval($_POST['id']), trim($_POST['name']), $pwd, trim($_POST['email']), intval($_POST['is_active']), intval($_POST['invite_by']), trim($_POST['house_name']), intval($_POST['gold_num']));
                        $_GET['page'] = 'edit2';
                        $_GET['id'] = post('id');
                        $this->viewData['sc'] = true;
                        break;

                    case 'search':
                        if (post('searchword') != "") {
                            if (post('type') == 1) {
                                $this->viewData['list'] = $this->m->GetPlayerDataByName(post('searchword'));
                            } elseif (post('type') == 2) {
                                $this->viewData['list'] = $this->m->GetPlayerDataByAlliance(post('searchword'));
                            } elseif (post('type') == 3) {
                                $this->viewData['list'] = $this->m->GetPlayerDataByIB(post('searchword'));
                            } elseif (post('type') == 4) {
                                $this->viewData['list'] = $this->m->GetPlayerDataByType(post('searchword'));
                            }

                        } elseif (post('searchword2') != "") {
                            unset($this->viewData['list']);
                            if (post('type2') == 1) {
                                $this->viewData['list2'] = $this->m->GetMPlayerDataByName(post('searchword2'));
                            } elseif (post('type2') == 2) {
                                $this->viewData['list2'] = $this->m->GetPlayerDataByGold(post('searchword2'));
                            }
                        }

                        $this->viewData['url'] = URL;
                        break;
                    case 'gold':
                        $count = $this->m->GivePlayerGold(post('name'), post('gold'));
                        if ($count) {
                            $this->viewData['sc'] = true;
                        }
                        break;
                    case 'activate':
                        $result = $this->m->GetPlayerDataByName2(post('name'));
                        $link = URL . "/index?active=" . $result['activation_code'];
                        $to = $result['email'];
                        $subject = "تفعيل الحساب في حرب التتار";
                        $body = "مرحباً %s<br/>\r\nشكرا لك على التسجيل.<br/>\r\n<br/>\r\n----------------------------<br/>\r\n----------------------------<br/>\r\n<br/>\r\nانقر هذه الوصله لتنشيط العضوية:<br/>\r\n<a href=\"%s\">%s</a>";
                        $message = sprintf($body, post('name'), $link, $link);
                        $count = send_mail($to, $subject, $message, $gameConfig['page'][$gameConfig['system']['lang'] . '_title'], post('name'));
                        if ($count) {
                            $this->viewData['sc'] = true;
                        }
                        break;

                    default:
                        @header("Location: index.php");
                        break;
                }
            }
        }
        if (is_get('page')) {
            if (get('page') == 'edit' and is_get('id')) {
                $this->viewData['p'] = $this->m->GetPlayerDataById(get('id'));
                $this->viewData['page'] = 'edit';
            } elseif (get('page') == 'edit2' and is_get('id')) {
                $this->viewData['p'] = $this->m->GetMplayerData(get('id'));
                $this->viewData['page'] = 'edit2';
            } elseif (get('page') == 'search') {
                $this->viewData['page'] = 'search';
            } elseif (get('page') == 'delete') {
                $this->m->deletePlayer(get('id'));
                $this->viewData['page'] = 'search';
            } elseif (get('page') == 'gold') {
                $this->viewData['page'] = 'gold';
            } elseif (get('page') == 'activate') {
                $this->viewData['page'] = 'activate';
            } else {
                return header("Location: index.php");
            }
        } else {
            return header("Location: index.php");
        }
    }

}
//end file
