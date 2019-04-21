<?php
require_once LIBRARY_DIR . 'gameEngine/AuthController.php';

class VillageController extends AuthController
{
    public $buildings = array();
    public $tribeId;

    public function __construct()
    {
        parent::__construct();
        // check is the player is logged
        if ($this->player == NULL) {
            $this->is_redirect = TRUE;
            redirect('login');
            return;
        }

        $this->tribeId = $this->data['tribe_id'];

        // get the village buildings
        $b_arr = explode(',', $this->data['buildings']);
        $indx = 0;
        foreach ($b_arr as $b_str) {
            $indx++;
            $b2 = explode(' ', $b_str);
            $this->onLoadBuildings($this->buildings[$indx] =
                array(
                    'index' => $indx,
                    'item_id' => $b2[0],
                    'level' => $b2[1],
                    'update_state' => $b2[2]
                ));
        }
    }

    public function onLoadBuildings($building)
    {
    }


    // @return:
    //  1: can be build
    //  0:available soon
    // -1:can not be build
    public function canCreateNewBuild($item_id)
    {
        if (!isset ($this->gameMetadata['items'][$item_id])) {
            return -1;
        }

        $buildMetadata = $this->gameMetadata['items'][$item_id];

        if ($this->data['is_capital']) {
            if (!$buildMetadata['built_in_capital']) {
                return -1;
            }
        } else {
            if (!$buildMetadata['built_in_non_capital']) {
                return -1;
            }
        }

        if ($buildMetadata['built_in_special_only']) {
            if (!$this->data['is_special_village']) {
                return -1;
            }
        }

        // check for support multiple
        $alreadyBuilded = FALSE;
        $alreadyBuildedWithMaxLevel = FALSE;
        foreach ($this->buildings as $villageBuild) {
            if ($villageBuild['item_id'] == $item_id) {
                $alreadyBuilded = TRUE;
                if ($villageBuild['level'] == sizeof($buildMetadata['levels'])) {
                    $alreadyBuildedWithMaxLevel = TRUE;
                    break;
                }
            }
        }
        if ($alreadyBuilded) {
            if (!$buildMetadata['support_multiple']) {
                return -1;
            } else {
                if (!$alreadyBuildedWithMaxLevel) {
                    return -1;
                }
            }
        }

        // check for none pre-request
        foreach ($buildMetadata['pre_requests'] as $req_item_id => $level) {
            if ($level == NULL) {
                foreach ($this->buildings as $villageBuild) {
                    if ($villageBuild['item_id'] == $req_item_id) {
                        return -1;
                    }
                }
            }
        }

        // check for pre-request
        foreach ($buildMetadata['pre_requests'] as $req_item_id => $level) {
            if ($level == NULL) {
                continue;
            }

            $result = FALSE;
            foreach ($this->buildings as $villageBuild) {
                if ($villageBuild['item_id'] == $req_item_id
                    && $villageBuild['level'] >= $level) {
                    $result = TRUE;
                    break;
                }
            }

            if (!$result) {
                return 0;
            }
        }
        return 1;
    }

    public function isResourcesAvailable($neededResources)
    {
        foreach ($neededResources as $k => $v) {
            if ($v > $this->resources[$k]['current_value']) {
                return FALSE;
            }
        }
        return TRUE;
    }

    // @return:
    //  0:not need upgrades
    //  1: need to increase crop resource production
    //  2: need to increase Warehouse
    //  3: need to increase Granary
    //  4: need to increase Granary and Warehouse
    public function needMoreUpgrades($neededResources, $itemId = 0)
    {
        $result = 0;
        if ($this->resources[4]['calc_prod_rate']
            <= 2
            && $itemId != 4
            && $itemId != 8
            && $itemId != 9
            && $itemId != 16
            && $this->data['is_special_village'] != 1) {
            return 1;
        }

        foreach ($neededResources as $k => $v) {
            if ($v > $this->resources[$k]['store_max_limit']) {
                if ($result == 0 && ($k == 1 || $k == 2 || $k == 3)) {
                    $result++;
                }

                if ($k == 4) {
                    $result += 2;
                }
            }
        }

        if ($result > 0) {
            $result++;
        }

        return $result;
    }

    public function isWorkerBusy($isField)
    {
        $qTasks = $this->queueModel->tasksInQueue;
        $maxTasks = $this->data['active_plus_account'] ? 2 : 1;

        if ($this->gameMetadata['tribes'][$this->data['tribe_id']]['dual_build']) {
            return array(
                'isBusy' => (($isField) ? ($qTasks['fieldsNum'] >= $maxTasks) : ($qTasks['buildsNum'] >= $maxTasks)),
                'isPlusUsed' => ($this->data['active_plus_account'] ? ($isField ? ($qTasks['fieldsNum'] > 0) : ($qTasks['buildsNum'] > 0)) : FALSE)
            );
        }

        return array(
            'isBusy' => ($qTasks['buildsNum'] + $qTasks['fieldsNum']) >= $maxTasks,
            'isPlusUsed' => ($this->data['active_plus_account'] ? (($qTasks['buildsNum'] + $qTasks['fieldsNum']) > 0) : FALSE)
        );
    }


    public function getBuildingProperties($index)
    {
        if (!isset ($this->buildings[$index])) {
            return NULL;
        }

        $building = $this->buildings[$index];
        if ($building['item_id'] == 0) {
            return array('emptyPlace' => TRUE);
        }

        if ($this->data['is_special_village'] && ($building['index'] == 26 || $building['index'] == 29 || $building['index'] == 30 || $building['index'] == 33)) {
            return null;
        }

        $buildupdat = $this->global_model->getbuildupdate($this->player->playerId, $this->data['selected_village_id'], $building['item_id'], $building['index']);
        if ($buildupdat !== $building['update_state']) {
            $b_arr = explode(',', $this->data['buildings']);
            $newbuilding = '';
            $ind = 0;
            foreach ($b_arr as $b_str) {
                ++$ind;
                $b2_arr = explode(' ', $b_str);
                if ($b2_arr[0] == $building['item_id'] and $ind == $building['index']) {
                    $b_str = $b2_arr[0] . ' ' . $b2_arr[1] . ' ' . $buildupdat;
                }
                if ($newbuilding != '') {
                    $newbuilding .= ',';
                }
                $newbuilding .= $b_str;
            }
            if ($newbuilding != '') {
                $this->global_model->updatevillagebuild($this->data['selected_village_id'], $newbuilding);
                $building['update_state'] = $buildupdat;
            }
        }
        $artPower = 1;
        if ($building['item_id'] == 23) {
            $this->load_model('Artefacts', 'A');
            $artLevel = $this->A->Artefacts($this->player->playerId, $this->data['selected_village_id'], 8);
            $artPower = ($artLevel == 0) ? 1 : (($artLevel == 1) ? 200 : (($artLevel == 2) ? 100 : 500));
        }
        $buildMetadata = $this->gameMetadata['items'][$building['item_id']];
        $_trf = isset ($buildMetadata['for_tribe_id'][$this->tribeId]) ? $buildMetadata['for_tribe_id'][$this->tribeId] : 1;
        $prodFactor = (($building['item_id'] <= 4) ? (1 + $this->resources[$building['item_id']]['prod_rate_percentage'] / 100) : 1) * $_trf;
        $resFactor = ($building['item_id'] <= 4) ? $this->gameSpeed : 1;
        $maxLevel = ($this->data['is_capital']) ? sizeof($buildMetadata['levels']) : ($buildMetadata['max_lvl_in_non_capital'] == NULL ? sizeof($buildMetadata['levels']) : $buildMetadata['max_lvl_in_non_capital']);
        $upgradeToLevel = $building['level'] + $building['update_state'];
        $nextLevel = $upgradeToLevel + 1;
        if ($nextLevel > $maxLevel) {
            $nextLevel = $maxLevel;
        }
        $nextLevelMetadata = $buildMetadata['levels'][$nextLevel - 1];

        return array(
            'emptyPlace' => FALSE,
            'upgradeToLevel' => $upgradeToLevel,
            'nextLevel' => $nextLevel,
            'maxLevel' => $maxLevel,
            'building' => $building,
            'level' => array(
                'current_value' => intval((($building['level'] == 0) ? 2 : $buildMetadata['levels'][$building['level'] - 1]['value']) * $prodFactor * $resFactor * $artPower),
                'value' => intval($nextLevelMetadata['value'] * $prodFactor * $resFactor * $artPower),
                'resources' => $nextLevelMetadata['resources'],
                'people_inc' => $nextLevelMetadata['people_inc'],
                'calc_consume' => intval(($nextLevelMetadata['time_consume'] / $this->gameSpeed) * ($this->data['time_consume_percent'] / 100))
            )
        );
    }

}

?>