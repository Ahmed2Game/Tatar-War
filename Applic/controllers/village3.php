<?php
load_game_engine('Village');
load_game_engine('Report', 'Helper');

class Village3_Controller extends VillageController
{
    public $pageState = NULL;
    public $mapItemData = NULL;
    public $lastReport = NULL;
    public $itemTroops = NULL;
    public $hasMarketplace = NULL;
    public $hasRallyPoint = NULL;

    public function __construct()
    {
        $this->hasMarketplace = FALSE;
        $this->hasRallyPoint = FALSE;

        parent::__construct();

        $this->viewFile = "village3";
        $this->viewData['contentCssClass'] = "map";

    }

    public function onLoadBuildings($building)
    {
        if (!$this->hasMarketplace && $building['item_id'] == 17) {
            $this->hasMarketplace = TRUE;
        }
        if (!$this->hasRallyPoint && $building['item_id'] == 16) {
            $this->hasRallyPoint = TRUE;
        }
    }


    public function index()
    {
        $villageId = is_get('id') && 0 < intval(get('id')) ? intval(get('id')) : 0;
        if ($villageId <= 0) {
            $this->is_redirect = TRUE;
            redirect("map");
        } else {
            $this->load_model('Village3', 'm');
            $this->mapItemData = $this->m->getMapItemData($villageId);
            if (!is_array($this->mapItemData)) {
                $this->is_redirect = TRUE;

                redirect("map");
            } else {
                if (0 < intval($this->mapItemData['player_id'])) {
                    $this->pageState = $this->mapItemData['is_oasis'] ? 3 : 2;
                    $this->mapItemData['playerType'] = $this->m->getPlayType(intval($this->mapItemData['player_id']));
                } else {
                    $this->pageState = $this->mapItemData['is_oasis'] ? 4 : 1;
                }
                $this->lastReport = NULL;
                if ($this->pageState == 2 || $this->pageState == 3) {
                    if ($this->mapItemData['player_id'] == $this->player->playerId
                        || $this->mapItemData['alliance_id'] == $this->data['alliance_id']
                        && 0 < intval($this->data['alliance_id'])) {
                        $this->lastReport = $this->m->getLatestReports($this->mapItemData['player_id'], $this->mapItemData['id']);
                    } else {
                        $fromPlayersId = 0 < intval($this->data['alliance_id']) ? $this->m->getAlliancePlayersId(intval($this->data['alliance_id'])) : $this->player->playerId;
                        $this->lastReport = $this->m->getLatestReports2($fromPlayersId, $this->mapItemData['player_id'], $this->mapItemData['id']);
                    }
                    ## View
                    $this->viewData['lastReport'] = $this->lastReport;
                    $this->viewData['data'] = $this->data;

                    $this->viewData['hasRallyPoint'] = $this->hasRallyPoint;
                    $this->viewData['hasMarketplace'] = $this->hasMarketplace;

                    if ($this->pageState == 3) {
                        $this->mapItemData['village_name'] = $this->m->getVillageName($this->mapItemData['parent_id']);
                        $this->viewData['player'] = $this->player;
                    }
                } else if ($this->pageState == 1 || $this->pageState == 4) {
                    $this->itemTroops = array();
                    $t = $this->pageState == 1 ? $this->data['troops_num'] : $this->mapItemData['troops_num'];
                    $incFactor = $this->pageState == 4 ? floor($this->mapItemData['elapsedTimeInSeconds'] / 86400) : 0;
                    $t_arr = explode("|", $t);
                    foreach ($t_arr as $t_str) {
                        $t2_arr = explode(":", $t_str);
                        if (0 - 1 < $t2_arr[0]) {
                            continue;
                        }
                        $t2_arr = explode(",", $t2_arr[1]);
                        foreach ($t2_arr as $t2_str) {
                            $t = explode(" ", $t2_str);
                            if ($t[0] == 99) {
                                continue;
                            }
                            $this->itemTroops[$t[0]] = $t[1] + $incFactor;
                            if ($this->pageState == 4 && 7 * intval($t[0]) < $this->itemTroops[$t[0]]) {
                                $this->itemTroops[$t[0]] = 7 * intval($t[0]);
                            }
                        }
                    }

                    ## View
                    if ($this->pageState == 1) {
                        $builderTroopId = 10;
                        switch ($this->data['tribe_id']) {
                            case 2:
                                $builderTroopId = 20;
                                break;
                            case 3:
                                $builderTroopId = 30;
                                break;
                            case 6:
                                $builderTroopId = 60;
                                break;
                            case 7:
                                $builderTroopId = 109;
                        }

                        if ($this->itemTroops[$builderTroopId] < 3) {
                            $this->viewData['builderTroopId'] = $this->itemTroops[$builderTroopId];
                        } else {
                            $this->viewData['builderTroopId'] = FALSE;
                        }
                    } elseif ($this->pageState == 4) {
                        $this->viewData['itemTroops'] = $this->itemTroops;
                        $this->viewData['hasRallyPoint'] = $this->hasRallyPoint;
                    }
                }

            }
        }

        ## Pre-redening
        if (isset($_GET['id'])) {
            $this->viewData['villagesLinkPostfix'] .= "&id=" . intval($_GET['id']);
        }

        ## View
        $this->viewData['active_plus_account'] = $this->data['active_plus_account'];
        $this->viewData['pageState'] = $this->pageState;
        $this->viewData['mapItemData'] = $this->mapItemData;

    }

}

?>