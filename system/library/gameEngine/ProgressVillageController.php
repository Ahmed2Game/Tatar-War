<?php
require_once LIBRARY_DIR . 'gameEngine/VillageController.php';

class ProgressVillageController extends VillageController
{

    public function __construct()
    {
        parent::__construct();

        // check for update key
        if (is_get('id') && is_numeric(get('id')) && is_get('k')
            && get('k') == $this->data['update_key']
            && !$this->isGameTransientStopped() && !$this->isGameOver()) {

            if (is_get('d')) {
                // task cancellation
                $this->queueModel->cancelTask($this->player->playerId, intval(get('id')));
            } elseif (isset ($this->buildings[get('id')])) {
                // create or upgrade building
                $buildProperties = $this->getBuildingProperties(intval(get('id')));
                if ($buildProperties != NULL) {
                    $canAddTask = FALSE;
                    if (!$this->data['is_capital'] && (get('id') < 19)) {
                        if ($this->buildings[get('id')]['level'] >= 10 || $buildProperties['upgradeToLevel'] >= 10) {
                            return;
                        }
                    }
                    if (!is_get('b')) {
                        if (($this->buildings[get('id')]['level'] >= 20 && $this->buildings[get('id')]['item_id'] != 40) || ($buildProperties['upgradeToLevel'] >= 20 && $this->buildings[get('id')]['item_id'] != 40)) {
                            return;
                        }
                    }
                    if ($buildProperties['emptyPlace']) {
                        // new building
                        $item_id = is_get('b') ? intval(get('b')) : 0;

                        $posIndex = intval(get('id'));
                        if (($posIndex == 39 && $item_id != 16)
                            || ($posIndex == 40 && $item_id != 31 && $item_id != 32 && $item_id != 33)) {
                            return;
                        }
                        if ($this->data['is_special_village']
                            && ($posIndex == 25 || $posIndex == 26 || $posIndex == 29 || $posIndex == 30 || $posIndex == 33)
                            && $item_id != 40) {
                            return;
                        }

                        if ($this->canCreateNewBuild($item_id) == 1) {
                            $canAddTask = TRUE;
                            $neededResources = $this->gameMetadata['items'][$item_id]['levels'][0]['resources'];
                            $calcConsume = intval(($this->gameMetadata['items'][$item_id]['levels'][0]['time_consume'] / $this->gameSpeed) * ($this->data['time_consume_percent'] / 100));
                        }
                    } else {
                        // upgrade building
                        $canAddTask = TRUE;
                        $item_id = $buildProperties['building']['item_id'];
                        $neededResources = $buildProperties['level']['resources'];
                        $calcConsume = $buildProperties['level']['calc_consume'];
                        if ($item_id != 40 && is_get('max')) {
                            $uplevels = $buildProperties['maxLevel'] - $buildProperties['upgradeToLevel'];
                            $this->load_model('Plus', 'P');
                            if ($uplevels <= $this->data['gold_num'] && $uplevels > 0) {
                                $this->P->DeletPlayerGold2($this->player->playerId, $uplevels);
                                $i = 0;
                                while ($i < $uplevels) {
                                    $this->load_model('Queuejob', 'Q');
                                    $this->Q->upgradeBuilding($this->data['selected_village_id'], intval(get('id')), $item_id, FALSE);
                                    $i++;
                                }
                            }
                            $canAddTask = FALSE;
                            $this->is_redirect = TRUE;
                            $page = $item_id > 4 ? "village2" : "village1";
                            redirect($page);
                        }
                    }

                    if ($canAddTask && $this->needMoreUpgrades($neededResources, $item_id) == 0
                        && $this->isResourcesAvailable($neededResources)) {
                        $workerResult = $this->isWorkerBusy($item_id <= 4);
                        if (!$workerResult['isBusy']) {
                            // add the task into the queue
                            $newTask = $this->load_library('QueueTask', 'newTask', array(
                                'taskType' => QS_BUILD_CREATEUPGRADE,
                                'playerId' => $this->player->playerId,
                                'executionTime' => $calcConsume
                            ));

                            $this->newTask->villageId = $this->data['selected_village_id'];
                            $this->newTask->buildingId = $item_id;
                            $this->newTask->procParams = $item_id == 40 ? 25 : intval(get('id'));
                            $this->newTask->tag = $neededResources;
                            $this->queueModel->addTask($this->newTask);
                        }
                    }
                }
            }
        }
    }

}

?>