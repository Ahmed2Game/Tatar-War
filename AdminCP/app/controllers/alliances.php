<?php

load_core('Admin');

class Alliances_Controller extends AdminController
{

    /**
     * Constructor Method
     * This method defines template layout && view file and load model
     */
    public function __construct()
    {
        parent::__construct();
        $this->viewFile = "alliances";
    }

    /**
     * Index Method
     *
     * @return void
     */
    public function index()
    {
        $this->load_model('Alliance', 'm');

        if ($_POST) {
            if (is_get('page')) {
                if (get('page') == 'update') {
                    $this->m->UpdateAlliance(post('name'), post('name2'), post('creator_player_id'), post('max_player_count'), post('description1'), post('description2'), post('attack_points'), post('defense_points'), post('week_attack_points'), post('week_defense_points'), post('week_dev_points'), post('week_thief_points'));
                    $this->viewData['sc'] = true;
                }
            }
            if (post('aid')) {
                $this->viewData['a'] = $this->m->GetAllianceDataById(post('aid'));
            }
        }
    }

}
//end file
