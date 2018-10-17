<?php
/**
 * Quicktasks class file.
 *
 * @author Abdulrahman Mohamed <abdokamal15@gmail.com>
 * @copyright Copyright 2013
 * @license http://www.xtatar.com
 */

/**
 * Dashboard Quicktasks page
 *
 * @author Abdulrahman Mohamed <abdokamal15@gmail.com>
 * @version $Id$
 * @package app.controllers
 * @since 1.0
 */
load_core('Admin');
class Quicktasks_Controller extends AdminController
{

    /**
     * Constructor Method
     * This method defines template layout && view file and load model
     */
    public function __construct()
    {
        parent::__construct();
        $this->viewFile = "quicktasks";
    }


    /**
     * Index Method
     *
     * @return void
     */
    public function index()
    {
        $this->load_model('Quicktasks', 'm');

        global $gameConfig;
        $this->viewData['gameConfig'] = $gameConfig;
        $this->load_model('Servers', 'S');
        $this->viewData['blocked_email'] = $this->S->GetSettings("blocked_email");
        $this->viewData['bad_words'] = $this->S->GetSettings("bad_words");
        
        if ($_POST)
        {
            if (is_get('page'))
            {
                switch (get('page'))
                {
                    case 'configurations':
                        $page = array(
                            'ar_title' => post('ar_title'),
                            'en_title' => post('en_title'),
                            'ar_meta' => post('ar_meta'),
                            'en_meta' => post('en_meta')
                        );
                        $this->S->UpdateSetting("page", json_encode($page, JSON_FORCE_OBJECT));
                        
                        $system = array(
                            'spybass' => post('spybass'),
                            'adminName' => post('adminName'),
                            'adminPassword' => post('adminPassword'),
                            'lang' => post('lang'),
                            'server_url' => post('server_url'),
                            'admin_email' => post('admin_email'),
                            'email' => post('email'),
                            'installkey' => post('installkey')
                        );
                        $this->S->UpdateSetting("system", json_encode($system, JSON_FORCE_OBJECT));
                        header("Location: quicktasks?page=configurations");
                        break;

                    case 'game_config':
                        $settings = array(
                            'speed'        => post('speed'),
                            'moared'       => post('moared'),
                            'map'          => post('map'),
                            'attack'       => post('attack'),
                            'protection'   => post('protection'),

                            'protection1'  => post('protection1'),
                            'holiday'      => post('holiday'),
                            'holidaygold'  => post('holidaygold'),
                            'Crop'         => post('Crop'),
                            'Artefacts'    => post('Artefacts'),
                            'resetTime'    => post('resetTime'),
                            'wingold'      => post('wingold'),

                            'capacity'     => post('capacity'),
                            'cranny'       => post('cranny'),
                            'cp'           => post('cp'),
                            'market'       => post('market'),
                            'osiss1'       => post('osiss1'),
                            'osiss2'       => post('osiss2'),
                            'over'         => post('over'),
                            'RegisterOver' => post('RegisterOver'),
                            'invinteGold'  => post('invinteGold'),
                            'piyadeh'      => post('piyadeh'),
                            'savareh'      => post('savareh'),
                            'shovalieh'    => post('shovalieh'),
                            'freegold'     => post('freegold'),
                            'freegold2'    => post('freegold2'),
                            'pepole'       => post('pepole'),
                            'buytroop'     => post('buytroop')
                        );
                        
                        $this->S->UpdateSettings($_SESSION['server_selected'], json_encode($settings, JSON_FORCE_OBJECT));
                        header("Location: quicktasks?page=game_config");
                    break;

                    case 'troop':
                        $troop = array(
                            'inTatar'  => post('inTatar'),
                            'tatarAtt'  => post('tatarAtt'),
                            'tatarAttM'  => post('tatarAttM'),
                            'inArtef'  => post('inArtef')
                        );

                        $this->S->UpdateTroop($_SESSION['server_selected'], json_encode($troop, JSON_FORCE_OBJECT));
                        header("Location: quicktasks?page=troop");
                    break;

                    case 'news':
                        $news = post('news');
                        if (!empty($news))
                        {
                            $this->m->setSiteNews(post('news'));
                            $this->viewData['notice']  = TRUE;
                            $this->viewData['status']  = 'success';
                            $this->viewData['message'] = 'تم تعديل الاخبار بنجاح';
                        }
                        else
                        {
                            $this->viewData['notice']  = TRUE;
                            $this->viewData['status']  = 'error';
                            $this->viewData['message'] = 'من فضلك ادخل محتوى الرسالة !!';
                        }
                        break;
                    case 'general_message':
                        $news = post('news');
                        if (!empty($news))
                        {
                            $this->m->setGlobalPlayerNews(post('news'));
                            $this->viewData['notice']  = TRUE;
                            $this->viewData['status']  = 'success';
                            $this->viewData['message'] = 'تم ارسال الخبر الى اللاعبين بنجاح';
                        }
                        else
                        {
                            $this->viewData['notice']  = TRUE;
                            $this->viewData['status']  = 'error';
                            $this->viewData['message'] = 'من فضلك ادخل محتوى الرسالة !!';
                        }
                        break;
                    case 'stats':
                        $this->m->UpdateGsummaryData(intval($_POST['players_count']), intval($_POST['active_players_count']), intval($_POST['Arab_players_count']), intval($_POST['Roman_players_count']), intval($_POST['Teutonic_players_count']), intval($_POST['Gallic_players_count']));
                        $this->viewData['notice']  = TRUE;
                        $this->viewData['status']  = 'success';
                        $this->viewData['message'] = 'تم تعديل الاحصائيات بنجاح';
                        break;
                    case 'stop_attack':
                        $Trucetime = intval(post('Trucetime'));
                        if (!empty($Trucetime))
                        {
                            $this->m->UpdateTruceTime($Trucetime * 3600, post('reason'));
                            $this->viewData['notice']  = TRUE;
                            $this->viewData['status']  = 'success';
                            $this->viewData['message'] = 'تم ايقاف الهجوم بنجاح ' . post('Trucetime') . ' ساعة';
                        }
                        else
                        {
                            $this->viewData['notice']  = TRUE;
                            $this->viewData['status']  = 'error';
                            $this->viewData['message'] = 'من فضلك ادخل رقم صحيح !!';
                        }
                        break;
                    case 'send_gold':
                        $goldnum = intval(post('goldnum'));
                        if (!empty($goldnum))
                        {
                            $this->m->UpdatePlayergold($goldnum);
                            $this->viewData['notice']  = TRUE;
                            $this->viewData['status']  = 'success';
                            $this->viewData['message'] = 'تم ارسال الذهب الى اللاعبين بنجاح';
                        }
                        else
                        {
                            $this->viewData['notice']  = TRUE;
                            $this->viewData['status']  = 'error';
                            $this->viewData['message'] = 'من فضلك ادخل رقم صحيح !!';
                        }
                        break;
                    // case 'send_email':
                    //     # code...
                    // break;
                    case 'block_email':
                        $this->S->UpdateSetting("blocked_email", post('emails'));
                        header("Location: quicktasks?page=block_email");
                    break;
                    case 'bad_words':
                        $this->S->UpdateSetting("bad_words", post('bad_words'));
                        header("Location: quicktasks?page=bad_words");
                    break;
                }
            }
        }

        if (is_get('page'))
        {
            if (get('page') == 'configurations')
            {
                $this->viewData['page']           = 'configurations';
            }
            elseif (get('page') == 'game_config')
            {
                $this->viewData['page'] = 'game_config';
            }
            elseif (get('page') == 'troop')
            {
                $this->viewData['page'] = 'troop';
            }
            elseif (get('page') == 'news')
            {
                $this->viewData['s']    = $this->m->getSiteNews();
                $this->viewData['page'] = 'news';
            }
            elseif (get('page') == 'general_message')
            {
                $this->viewData['s']    = $this->m->getGlobalSiteNews();
                $this->viewData['page'] = 'general_message';
            }
            elseif (get('page') == 'stats')
            {
                $this->viewData['s']    = $this->m->GetGsummaryData2();
                $this->viewData['page'] = 'stats';
            }
            elseif (get('page') == 'stop_attack')
            {
                $this->viewData['s']    = $this->m->GetGsummaryData();
                $this->viewData['page'] = 'stop_attack';
            }
            elseif (get('page') == 'send_gold')
            {
                $this->viewData['page'] = 'send_gold';
            }
            elseif (get('page') == 'send_email')
            {
                $this->viewData['page'] = 'send_email';
            }
            elseif (get('page') == 'block_email')
            {
                $this->viewData['page'] = 'block_email';
            }
            elseif (get('page') == 'bad_words')
            {
                $this->viewData['page'] = 'bad_words';
            }
            else
            {
                return header("Location: index.php");
            }
        }
        else
        {
            return header("Location: index.php");
        }
    }

}
//end file
?>