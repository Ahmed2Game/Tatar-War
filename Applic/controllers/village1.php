<?php
load_game_engine('ProgressVillage');

class Village1_Controller extends ProgressVillageController
{
    public $troops = array();
    public $heroCount = 0;

    public function __construct()
    {
        parent::__construct();

        $this->viewFile = 'village1';
        $this->viewData['contentCssClass'] = 'village1';
    }

    public function index()
    {
        if (is_get('_gn_') && !$this->player->isSpy) {
            $this->load_model('Profile', 'mprof');
            $this->mprof->resetGNewsFlag($this->player->playerId);
        }

        $this->heroCount = $this->data['hero_in_village_id'] == $this->data['selected_village_id'] ? 1 : 0;
        $t_arr = explode("|", $this->data['troops_num']);
        foreach ($t_arr as $t_str) {
            $t2_arr = explode(":", $t_str);
            $t2_arr = explode(",", $t2_arr[1]);
            foreach ($t2_arr as $t2_str) {
                list($tid, $tnum) = explode(" ", $t2_str);
                if ($tid == 99 || $tnum == 0) {
                    continue;
                }
                if ($tnum == 0 - 1) {
                    $this->heroCount++;
                    continue;
                }
                if (isset($this->troops[$tid])) {
                    $this->troops[$tid] += $tnum;
                } else {
                    $this->troops[$tid] = $tnum;
                }
            }
        }
        ksort($this->troops, SORT_NUMERIC);

        ######################################### VIEW ################################
        $this->viewData['heroCount'] = $this->heroCount;

        ### buildings titles
        for ($i = 1; $i <= 18; $i++) {
            $this->viewData['BuildingTitle_' . $i] = $this->getBuildingTitle($i);
        }

        ## buildings
        $buildings = array();
        foreach ($this->buildings as $id => $build) {
            if (19 <= $id) {
                break;
            }
            if (0 < $build['level']) {
                $buildings[$id] = array('level' => $build['level'], 'name' => $this->getBuildingName($id));
            }
        }
        $this->viewData['buildings'] = $buildings;

        ## tasksInQueue
        $this->viewData['tasksInQueue'] = $this->queueModel->tasksInQueue;

        ## troops
        $troops = array();
        foreach ($this->troops as $k => $v) {
            $troops[$k] = array('name' => htmlspecialchars(constant("troop_" . $k)), 'v' => $v);
        }
        $this->viewData['troops'] = $troops;

        ## tmp buildings
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

    public function getBuildingName($id)
    {
        return htmlspecialchars(constant("item_" . $this->buildings[$id]['item_id']) . " " . level_lang . " "
            . $this->buildings[$id]['level']);
    }

    public function getBuildingTitle($id)
    {
        $name = $this->getBuildingName($id);
        return "title=\"" . $name . "\" alt=\"" . $name . "\"";
    }

}

?>