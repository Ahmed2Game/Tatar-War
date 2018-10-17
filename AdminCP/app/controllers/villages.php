<?php
/**
 * villages class file.
 *
 * @author Abdulrahman Mohamed <abdokamal15@gmail.com>
 * @copyright Copyright 2013
 * @license http://www.xtatar.com
 */

/**
 * Dashboard Villages page
 *
 * @author Abdulrahman Mohamed <abdokamal15@gmail.com>
 * @version $Id$
 * @package app.controllers
 * @since 1.0
 */
load_core('Admin');
class Villages_Controller extends AdminController
{

    /**
     * Constructor Method
     * This method defines template layout && view file and load model
     */
    public function __construct()
    {
        parent::__construct();
        $this->viewFile = "villages";
    }


    /**
     * Index Method
     *
     * @return void
     */
    public function index()
    {
        $this->load_model('Villages', 'm');
        global $gameConfig;
        if ($_POST)
        {
            if (is_get('page'))
            {
                switch (get('page'))
                {
                    case 'search':
                        if (post('type') == 1)
                        {
                            $this->viewData['list'] = $this->m->GetVillagesDataByName(post('searchword'));
                        }
                        elseif (post('type') == 2)
                        {
                            $result                 = $this->m->GetVillagesDataByTroops(post('searchword'));
                            $this->viewData['list'] = array();
                            foreach ($result as $value)
                            {
                                $returnTroops = 0;
                                if ($value['troops_out_num'] != '')
                                {
                                    $t_arr = explode('|', $value['troops_out_num']);
                                    foreach ($t_arr as $t_str)
                                    {
                                        $t2_arr = explode(':', $t_str);
                                        $t2_arr = explode(',', $t2_arr[1]);
                                        foreach ($t2_arr as $t2_str)
                                        {
                                            $t = explode(' ', $t2_str);
                                            if ($t[1] == 0 - 1)
                                            {
                                                continue;
                                            }
                                            $returnTroops += $t[1];
                                        }
                                    }
                                }
                                if ($value['troops_num'] != '')
                                {
                                    $t_arr2  = explode('|', $value['troops_num']);
                                    $t2_arr2 = explode(':', $t_arr2[0]);
                                    $t2_arr2 = explode(',', $t2_arr2[1]);
                                    foreach ($t2_arr2 as $t2_str2)
                                    {
                                        $t2 = explode(' ', $t2_str2);
                                        if ($t2[1] == 0 - 1)
                                        {
                                            continue;
                                        }
                                        $returnTroops += $t2[1];
                                    }
                                }
                                if (post('searchword') <= $returnTroops)
                                {
                                    $this->viewData['list'][] = array(
                                        'id' => $value['id'],
                                        'village_name' => $value['village_name'],
                                        'player_id' => $value['player_id'],
                                        'player_name' => $value['player_name'],
                                        'people_count' => $value['people_count'],
                                        'crop_consumption' => $value['crop_consumption'],
                                        'troop' => $returnTroops
                                    );
                                }
                            }
                        }
                        elseif (post('type') == 3)
                        {
                            $this->viewData['list'] = $this->m->GetVillagesDataByCrop(post('searchword'));
                        }
                        $this->viewData['url'] = $gameConfig['system']['server_url'];
                        break;
                    case 'update':
                        $this->m->UpdateVillageData( intval($_POST['id']), trim($_POST['rel_x']), trim($_POST['rel_y']), intval($_POST['tribe_id']), intval($_POST['player_id']), intval($_POST['alliance_id']), trim($_POST['player_name']), trim($_POST['village_name']), trim($_POST['alliance_name']), intval($_POST['is_capital']), intval($_POST['is_special_village']), intval($_POST['is_oasis']), intval($_POST['people_count']), trim($_POST['crop_consumption']), trim($_POST['resources']), trim($_POST['cp']), trim($_POST['buildings']), trim($_POST['troops_num']), trim($_POST['village_oases_id']), intval($_POST['id']) );
                        $_GET['page'] = 'edit';
                        $_GET['id']   = post('id');
                        $this->viewData['sc'] = TRUE;
                        break;
                    default:
                        # code...
                        break;
                }
            }
        }

        if (is_get('page'))
        {
            if (get('page') == 'show_results')
            {
                $this->viewData['page'] = 'show_results';
            }
            elseif (get('page') == 'edit' and is_get('id'))
            {
                $this->viewData['v']    = $this->m->GetVillageDataById(get('id'));
                $this->viewData['page'] = 'edit';
            }
            elseif (get('page') == 'search')
            {
                $this->viewData['page'] = 'search';
            }
            else
            {
                return header("Location: index.php");
            }
        }
        else
        {
            $this->viewData['page'] = 'search';
        }
    }

}
//end file
?>