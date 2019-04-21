<?php
require_once LIBRARY_DIR . 'gameEngine/BaseController.php';

class PublicController extends BaseController
{
    public function __construct()
    {

        parent::__construct();
        $this->layoutViewFile = 'layout/form';
        $this->load_model('Servers', 'm');
        $servers_list = $this->m->ServersList();
        $servers = array();
        foreach ($servers_list as $key => $value) {
            $settings = json_decode($value['settings'], true);
            $stop_date = new DateTime($value['start_date']);
            $stop_date->modify('+' . $settings['over'] . ' day');
            $servers[$value['id']] = array(
                'start_date' => $value['start_date'],
                'players_count' => $value['players_count'],
                'speed' => $settings['speed'],
                'end' => $stop_date->format('Y-m-d H:i:s')

            );
        }
        $this->viewData['servers'] = $servers;
        $fields = array();
        if (isset($_COOKIE['server'])) {
            foreach ($this->setupMetadata['field_maps_summary'] as $field => $value) {
                $fields[$field] = explode("-", $value);
            }
        }
        $this->viewData['fields'] = $fields;
    }
}

?>