<?php
load_game_engine('ProgressVillage');

class Village2_Controller extends ProgressVillageController
{

    public $showLevelsStr = NULL;

    public function __construct()
    {
        parent::__construct();
        $this->viewFile = "village2";
        $this->viewData['contentCssClass'] = "village2";
    }

    public function index()
    {
        $this->load_library('ClientData', 'cookie');
        $cookie = $this->cookie->getInstance();
        $this->showLevelsStr = $cookie->showLevels ? "on" : "off";

        ## View

        $this->viewData['showLevelsStr'] = $this->showLevelsStr;
        $this->viewData['getWallCssName'] = $this->getWallCssName();

        // buildings titles
        for ($i = 19; $i <= 40; $i++) {
            $this->viewData['BuildingTitle_' . $i] = $this->getBuildingTitle($i);
        }

        // buildings title classes
        for ($i = 19; $i <= 39; $i++) {
            $this->viewData['BuildingTitleClass_' . $i] = $this->getBuildingTitleClass($i);
        }


        // Buildings
        $buildings_array = array();
        $i = 0;
        foreach ($this->buildings as $id => $build) {
            if ($id < 19) {
                continue;
            }
            ++$i;
            if ($this->data['is_special_village'] && ($id == 25 || $id == 26 || $id == 29 || $id == 30 || $id == 33)) {
                continue;
            }
            if (0 < $build['level'] || 0 < $build['update_state']) {
                $cssClass = $id == 39 || $id == 40 ? "l" . $id : "d" . $i;
                $buildings_array[$cssClass] = $build['level'];
            }
        }
        $this->viewData['buildings_array'] = $buildings_array;
        $this->viewData['buildings'] = $this->buildings;

        $this->viewData['data'] = $this->data;

        // tmp buildings
        $this->viewData['QS_BUILD_CREATEUPGRADE'] = FALSE;
        if (isset($this->queueModel->tasksInQueue[QS_BUILD_CREATEUPGRADE])) {
            $this->viewData['QS_BUILD_CREATEUPGRADE'] = TRUE;

            $tmpBuilding = array();
            $tmpBuilding2 = array();
            foreach ($this->queueModel->tasksInQueue[QS_BUILD_CREATEUPGRADE] as $qtask) {
                $index = $qtask['proc_params'];
                $itemId = $qtask['building_id'];

                if (!isset($tmpBuilding[$index])) {
                    $tmpBuilding[$index] = 0;
                }
                ++$tmpBuilding[$index];
                $tmpBuilding2[] = array(
                    'id' => $qtask['id'],
                    'level' => $this->buildings[$index]['level'] + $tmpBuilding[$index],
                    'itemId' => $itemId,
                    'item_id_title' => constant("item_" . $itemId),
                    'remainingSeconds' => secondstostring($qtask['remainingSeconds'])
                );
            }
            $this->viewData['tmpBuilding'] = $tmpBuilding2;
            unset($tmpBuilding);
            unset($tmpBuilding2);
        }
    }

    public function getWallCssName()
    {
        if ($this->buildings[40]['level'] == 0 && $this->buildings[40]['update_state'] == 0) {
            return "d2_0";
        }
        return $this->gameMetadata['tribes'][$this->data['tribe_id']]['wall_css'];
    }

    public function getBuildingName($id)
    {
        $emptyName = "";
        switch ($id) {
            case 39 :
                $emptyName = buildin_place_railpoint;
                break;
            case 40 :
                $emptyName = buildin_place_wall;
                break;
        }
        $emptyName = $this->data['is_special_village'] && ($id == 25 || $id == 26 || $id == 29 || $id == 30 || $id == 33) ? buildin_place_topbuild : buildin_place_empty;
        return htmlspecialchars($this->buildings[$id]['item_id'] == 0 ? $emptyName : constant("item_" . $this->buildings[$id]['item_id']) . " " . level_lang . " " . $this->buildings[$id]['level']);
    }

    public function getBuildingCssName($id)
    {
        $cssName = "";
        switch ($id) {
            case 39 :
                $e = "";
                if ($this->buildings[$id]['level'] == 0 && 0 < $this->buildings[$id]['update_state']) {
                    $e = "b";
                } else if ($this->buildings[$id]['level'] == 0) {
                    $e = "e";
                }
                $cssName = "g" . $this->buildings[$id]['item_id'] . $e;
                break;
            case 25 :

            case 26 :

            case 29 :

            case 30 :

            case 33 :

                if ($this->data['is_special_village']) {
                    $cssName = "g40";
                    if (20 <= $this->buildings[$id]['level']) {
                        $cssName .= "_" . floor($this->buildings[$id]['level'] / 20);
                    }
                    break;
                }
            case 19 :
            case 20 :
            case 21 :
            case 22 :
            case 23 :
            case 24 :
            case 25 :

            case 26 :
            case 27 :
            case 28 :

            case 29 :

            case 30 :
            case 31 :
            case 32 :

            case 33 :
            case 34 :
            case 35 :
            case 36 :
            case 37 :
            case 38 :
                $e = $this->buildings[$id]['level'] == 0 && 0 < $this->buildings[$id]['update_state'] ? "b" : "";
                $cssName = $this->buildings[$id]['item_id'] == 0 ? "iso" : "g" . $this->buildings[$id]['item_id'] . $e;
                break;
        }
        return $cssName;
    }

    public function getBuildingTitle($id)
    {
        $name = $this->getBuildingName($id);
        return "title=\"" . $name . "\" alt=\"" . $name . "\"";
    }

    public function getBuildingTitleClass($id)
    {
        $name = $this->getBuildingName($id);
        $cssClass = $this->getBuildingCssName($id);
        return $cssClass . "\" alt=\"" . $name;
    }

}

?>