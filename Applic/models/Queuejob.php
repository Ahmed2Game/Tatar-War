<?php
require_once(MODELS_DIR . "Report.php");
require_once(MODELS_DIR . "Mutex.php");

class Queuejob_Model extends Model
{
    public function processQueue($type = 3, $playerId = 0)
    {
        global $gameConfig;
        $this->load_model('Mutex', 'mutex');
        $this->mutex->releaseOnTimeout();
        if ($this->mutex->lock())
        {
            $this->processTaskQueue($type, $playerId);
                $row = db::get_row("SELECT gs.cur_week w1, CEIL((TO_DAYS(NOW())-TO_DAYS(gs.start_date))/2) w2 FROM g_settings gs");
                if (($row['w2'] - $row['w1']) >= 1 )
                {
                    db::query("UPDATE g_settings gs SET gs.cur_week=:cur", array(
                        'cur' => intval($row['w2'])
                    ));
					/*$allP = db::get_all("SELECT p.id FROM p_players p WHERE p.total_people_count>5000 ");
					$Ids2 = "";
					foreach($allP as $Ids)
					{
						$Ids2 .= ($Ids2 == "" ) ? $Ids['id'] : ",".$Ids['id'];
					}
					$Ids2 = "(".$Ids2.")";
                    db2::query("UPDATE p_players p SET p.gold_num=p.gold_num+:gold WHERE p.is_active=1 and p.id IN $Ids2",array('gold' => $gameConfig['settings']['freegold2']));*/
                    $this->setWeeklyMedals(intval($row['w2']));
                }
            $this->mutex->release();
        }
    }

    public function processTaskQueue($type, $playerId)
    {
        $p_type = QS_ACCOUNT_DELETE.','.QS_MERCHANT_GO.','.QS_MERCHANT_BACK.','.QS_WAR_REINFORCE.','.QS_WAR_ATTACK.','.QS_WAR_ATTACK_PLUNDER.','.QS_WAR_ATTACK_SPY.','.QS_CREATEVILLAGE.','.QS_TATAR_RAISE.','.QS_SITE_RESET.','.QS_CROP_DELETE.','.QS_ARTEFACTS_RAISE;
        if ($type == 1)
        {
            $expr = "((q.player_id=".$playerId." AND q.proc_type!=".QS_TROOP_TRAINING.") OR q.proc_type IN (".$p_type."))";
        }
        elseif ($type == 2)
        {
            $expr = "(q.player_id=".$playerId." OR q.proc_type IN (".$p_type."))";
        }
        else
        {
            $expr = "q.proc_type!=".QS_TROOP_TRAINING;
        }
        $result = db::get_all("SELECT  q.id, q.player_id, q.village_id, q.to_player_id, q.to_village_id, q.proc_type, q.building_id, q.proc_params, q.threads, q.execution_time, TIMESTAMPDIFF(SECOND, NOW(),q.end_date) remainingTimeInSeconds FROM p_queue q WHERE TIMESTAMPDIFF(SECOND, NOW(),(q.end_date - INTERVAL (q.execution_time*(q.threads-1)) SECOND)) <= 0 AND $expr ORDER BY TIMESTAMPDIFF(SECOND, NOW(),(q.end_date - INTERVAL (q.execution_time*(q.threads-1)) SECOND)) ASC");
        foreach($result as $resultRow)
        {
            $remain = $resultRow['remainingTimeInSeconds'];
            if ($remain < 0)
            {
                $remain = 0;
            }
            $resultRow['threads_completed_num'] = $resultRow['execution_time'] <= 0 ? $resultRow['threads'] : floor(($resultRow['threads'] * $resultRow['execution_time'] - $remain) / $resultRow['execution_time']);
            if ($this->processTask($resultRow))
            {
                unset($result);
                $this->processQueue($type, $playerId);
                break;
            }
        }
        unset($result);
            
    }

    public function setWeeklyMedals($week)
    {
        $keyArray = array(
            "week_dev_points" => 1,
            "week_attack_points" => 2,
            "week_defense_points" => 3,
            "week_thief_points" => 4
        );
        $this->load_model('Statistics', 'sm');
        foreach ($keyArray as $columnName => $index)
        {
            $result = $this->sm->getTop10(TRUE, $columnName);
            if ($result != NULL)
            {
                $i = 0;
                foreach($result as $resultRow)
                {
                    $givegold = array("1" => 1000, "2" => 800, "3" => 600, "4" => 500, "5" => 400, "6" => 350, "7" => 300, "8" => 250, "9" => 200, "10" => 150);
                    $medal = $index . ":" . ++$i . ":" . $week . ":" . $resultRow['points'];
                    db::query("UPDATE p_players SET medals=CONCAT_WS(',', medals, :mod) WHERE id=:id", array(
                        'mod' => $medal,
                        'id' => $resultRow['id']
                    ));
					db2::query("UPDATE p_players SET gold_num=gold_num+:gold WHERE id=:id", array(
                        'gold' => $givegold[$i],
                        'id' => $resultRow['id']
                    ));
                }
            }
            $result = $this->sm->getTop10(FALSE, $columnName);
            if ($result != NULL)
            {
                $i = 0;
                foreach($result as $resultRow)
                {
                    $medal = ($index + 4) . ":" . ++$i . ":" . $week . ":" . $resultRow['points'];
                    db::query("UPDATE p_alliances SET medals=CONCAT_WS(',', medals, :mod) WHERE id=:id", array(
                        'mod' => $medal,
                        'id' => $resultRow['id']
                    ));
                }
            }
        }
        db::query("UPDATE p_players   SET week_dev_points=0, week_attack_points=0, week_defense_points=0, week_thief_points=0");
        db::query("UPDATE p_alliances SET week_dev_points=0, week_attack_points=0, week_defense_points=0, week_thief_points=0");
        
    }

    public function processTask($taskRow)
    {
        $customAction = FALSE;
        switch ($taskRow['proc_type'])
        {
            case QS_ACCOUNT_DELETE:
            {
                $this->deletePlayer($taskRow['player_id']);
                break;
            }
            case QS_BUILD_CREATEUPGRADE:
            {
                $customAction = $this->executeBuildingTask($taskRow);
                break;
            }
            case QS_BUILD_DROP:
            {
                $customAction = $this->executeBuildingDropTask($taskRow);
                break;
            }
            case QS_TROOP_RESEARCH:
            {
            }
            case QS_TROOP_UPGRADE_ATTACK:
            {
            }
            case QS_TROOP_UPGRADE_DEFENSE:
            {
                $this->executeTroopUpgradeTask($taskRow);
                break;
            }
            case QS_TROOP_TRAINING:
            {
                $this->executeTroopTrainingTask($taskRow);
                break;
            }
            case QS_TROOP_TRAINING_HERO:
            {
                $this->executeHeroTask($taskRow);
                break;
            }
            case QS_TOWNHALL_CELEBRATION:
            {
                $this->executeCelebrationTask($taskRow);
                break;
            }
            case QS_MERCHANT_GO:
            {
                $customAction = $this->executeMerchantTask($taskRow);
                break;
            }
            case QS_MERCHANT_BACK:
            {
                if ($taskRow['building_id'] >= 1) {
                    $this->returnMerchantTask($taskRow);
                }
                break;
            }
            case QS_WAR_REINFORCE:
            {
            }
            case QS_WAR_ATTACK:
            {
            }
            case QS_WAR_ATTACK_PLUNDER:
            {
            }
            case QS_WAR_ATTACK_SPY:
            {
            }
            case QS_CREATEVILLAGE:
            {
                $customAction = $this->executeWarTask($taskRow);
                break;
            }
            case QS_LEAVEOASIS:
            {
                $this->executeLeaveOasisTask($taskRow);
                break;
            }
            case QS_PLUS1:
            {
                db::query('UPDATE p_players p SET p.active_plus_account=0 WHERE p.id=:id', array(
                    'id' => intval($taskRow['player_id'])
                ));
                break;
            }
            case QS_PLUS2:
            {
                $this->executePlusTask($taskRow, 1);
                break;
            }
            case QS_PLUS3:
            {
                $this->executePlusTask($taskRow, 2);
                break;
            }
            case QS_PLUS4:
            {
                $this->executePlusTask($taskRow, 3);
                break;
            }
            case QS_PLUS5:
            {
                $this->executePlusTask($taskRow, 4);
                break;
            }
            case QS_TATAR_RAISE:
            {
                $this->load_model('Artefacts', 'A');
                $this->A->createTatarVillages();
                break;
            }
            case QS_SITE_RESET:
            {
                $this->load_model('Install', 'm');
                $this->m->processSetup($GLOBALS['SetupMetadata']['map_size']);
                $customAction = TRUE;
                
                break;
            }
            case QS_CROP_DELETE:
            {
                $this->load_model('Crop', 'c');
                $customAction = $this->c->deleteCrop($taskRow);
                break;
            }
            case QS_ARTEFACTS_RAISE:
            {
                $this->load_model('Artefacts', 'A');
                $this->A->createArtefacts();
                break;
            }
        }
        if (!$customAction)
        {
            $remaining_thread = $taskRow['threads'] - $taskRow['threads_completed_num'];
            if ($remaining_thread <= 0)
            {
                db::query("DELETE FROM p_queue WHERE id=:id", array(
                    'id' => intval($taskRow['id'])
                ));
            }
            else
            {
                db::query("UPDATE p_queue q SET q.threads=:th WHERE q.id=:id", array(
                    'th' => intval($remaining_thread),
                    'id' => intval($taskRow['id'])
                ));
            }
        }
        return $customAction;
    }

    public function cropBalance($playerId, $villageId)
    {
        $row = db::get_row("SELECT v.crop_consumption,  v.people_count, v.resources, v.cp, v.troops_num, v.troops_out_num, v.troops_intrap_num, TIME_TO_SEC(TIMEDIFF(NOW(), v.last_update_date)) elapsedTimeInSeconds, TIME_TO_SEC(TIMEDIFF(NOW(), v.creation_date)) oasisElapsedTimeInSeconds FROM p_villages v  WHERE v.id=:id AND v.player_id=:pid", array(
            'id' => intval($villageId),
            'pid' => intval($playerId)
        ));
        if ($row == NULL)
        {
            return;
        }
    }

   public function deletePlayer($playerId)
    {
        $playerId = intval($playerId);
		$this->load_model('Artefacts', 'A');
		$hasArtefacts = $this->A->GetArtefactsNum($playerId);
        if ($playerId <= 0 || $hasArtefacts > 0)
        {
            return;
        }
		$this->load_model('Battles_WarBattle', 'Warbattle');
		$villages = db::get_all("SELECT v.id, v.player_id, v.troops_out_num FROM p_villages v WHERE player_id=:id", array(
		    'id' => $playerId
		));
		foreach ($villages as $villagesd)
		{
			$this->Warbattle->DeleteTroopOut($villagesd);
		}
        $row = db::get_row("SELECT p.alliance_id, p.villages_id, p.tribe_id FROM p_players p WHERE id=:id", array(
            'id' => $playerId
        ));
		$row2 = db2::get_row("SELECT p.is_active FROM p_players p WHERE id=:id", array(
            'id' => $playerId
        ));
		$row = array_merge($row, $row2);
        if ($row == NULL)
        {
            return;
        }
        db::query("UPDATE p_msgs m SET m.to_player_id=IF(m.to_player_id=:id, NULL, m.to_player_id), m.from_player_id=IF(m.from_player_id=:id, NULL, m.from_player_id)", array(
            'id' => $playerId
        ));
        db::query("UPDATE p_rpts r SET r.to_player_id=IF(r.to_player_id=:id, NULL, r.to_player_id), r.from_player_id=IF(r.from_player_id=:id, NULL, r.from_player_id)", array(
            'id' => $playerId
        ));
        if (0 < intval($row['alliance_id']))
        {
            db::query("UPDATE p_alliances SET player_count=player_count-1 WHERE id=:id", array(
                'id' => intval($row['alliance_id'])
            ));
            $_aRow = db::get_row("SELECT a.players_ids, a.player_count FROM p_alliances a WHERE a.id=:id", array(
                'id' => intval($row['alliance_id'])
            ));
            if ($_aRow['player_count'] <= 0)
            {
                db::query("DELETE FROM p_alliances WHERE id=:id", array(
                    'id' => intval($row['alliance_id'])
                ));
            }
            else
            {
                $aplayers_ids = $_aRow['players_ids'];
                if (trim($aplayers_ids) != "")
                {
                    $newPlayers_ids  = "";
                    $aplayers_idsArr = explode(",", $aplayers_ids);
                    foreach ($aplayers_idsArr as $pid)
                    {
                        if ($pid == $playerId)
                        {
                            continue;
                        }
                        if ($newPlayers_ids != "")
                        {
                            $newPlayers_ids .= ",";
                        }
                        $newPlayers_ids .= $pid;
                    }
                    db::query("UPDATE p_alliances SET players_ids=:ids WHERE id=:id", array(
                        'ids' => $newPlayers_ids,
                        'id' => intval($row['alliance_id'])
                    ));
                }
            }
        }
        db::query("DELETE FROM p_merchants WHERE player_id=:id", array(
            'id' => $playerId
        ));
        db::query("UPDATE p_villages v  SET  v.tribe_id=IF(v.is_oasis=1, 4, 0), v.parent_id=NULL, v.player_id=NULL, v.alliance_id=NULL, v.player_name=NULL, v.village_name=NULL, v.alliance_name=NULL, v.is_capital=0, v.people_count=2, v.crop_consumption=2, v.time_consume_percent=100, v.offer_merchants_count=0, v.resources=IF(v.is_oasis=1, v.resources, NULL), v.cp=IF(v.is_oasis=1, '0 0', NULL), v.buildings=NULL, v.troops_training=NULL, v.child_villages_id=NULL, v.village_oases_id=NULL, v.troops_trapped_num=0, v.allegiance_percent=100, v.troops_num=IF(v.is_oasis=1, '-1:31 0,34 0,37 0', NULL), v.troops_out_num=NULL, v.troops_intrap_num=NULL, v.troops_out_intrap_num=NULL, v.creation_date=NOW() WHERE v.player_id=:id", array(
            'id' => $playerId
        ));
        db::query("DELETE FROM p_players WHERE id=:id", array(
            'id' => $playerId
        ));
        db::query("UPDATE g_summary  SET  players_count=players_count-1, active_players_count=active_players_count-:a, Arab_players_count=Arab_players_count-:b, Roman_players_count=Roman_players_count-:r, Teutonic_players_count=Teutonic_players_count-:t", array(
            'a' => $row['is_active'] ? 1 : 0,
            'b' => $row['tribe_id'] == 3 ? 1 : 0,
            'r' => $row['tribe_id'] == 1 ? 1 : 0,
            't' => $row['tribe_id'] == 2 ? 1 : 0
        ));
    }

    public function captureOasis($oasisId, $playerId, $villageId, $capture = TRUE)
    {
        $villageRow = db::get_row("SELECT v.id, v.player_id, v.tribe_id, v.alliance_id, v.player_name, v.alliance_name, v.resources, v.cp, v.crop_consumption, v.village_oases_id, TIME_TO_SEC(TIMEDIFF(NOW(), v.last_update_date)) elapsedTimeInSeconds  FROM p_villages v WHERE v.id=:id", array(
            'id' => intval($villageId)
        ));
        if (intval($villageRow['player_id']) == 0 || intval($villageRow['player_id']) != $playerId)
        {
            return;
        }
        if ($capture)
        {
            db::query("UPDATE p_villages v SET v.parent_id=:pa, v.tribe_id=:tid, v.player_id=:pid, v.alliance_id=:aid, v.player_name=:pname, v.alliance_name=:aname, v.troops_num=NULL, v.troops_out_num=NULL, v.troops_intrap_num=NULL, v.troops_out_intrap_num=NULL, v.allegiance_percent=100, v.creation_date=NOW(), v.last_update_date=NOW() WHERE v.id=:id", array(
                'pa' => intval($villageId),
                'tid' => intval($villageRow['tribe_id']),
                'pid' => intval($villageRow['player_id']),
                'aid' => 0 < intval($villageRow['alliance_id']) ? intval($villageRow['alliance_id']) : NULL,
                'pname' => $villageRow['player_name'],
                'aname' => $villageRow['alliance_name'],
                'id' => intval($oasisId)
            ));
        }
        else
        {
            db::query("UPDATE p_villages v  SET  v.tribe_id=4, v.parent_id=NULL, v.player_id=NULL, v.alliance_id=NULL, v.player_name=NULL, v.village_name=NULL, v.alliance_name=NULL, v.troops_num='-1:31 0,34 0,37 0', v.troops_out_num=NULL, v.troops_intrap_num=NULL, v.troops_out_intrap_num=NULL, v.allegiance_percent=100, v.creation_date=NOW() WHERE v.id=:id", array(
                'id' => intval($oasisId)
            ));
        }
        $village_oases_id = "";
        if ($capture)
        {
            $village_oases_id = trim($villageRow['village_oases_id']);
            if ($village_oases_id != "")
            {
                $village_oases_id .= ",";
            }
            $village_oases_id .= $oasisId;
        }
        else if (trim($villageRow['village_oases_id']) != "")
        {
            $village_oases_idArr = explode(",", $villageRow['village_oases_id']);
            foreach ($village_oases_idArr as $oid)
            {
                if ($oid == $oasisId)
                {
                    continue;
                }
                if ($village_oases_id != "")
                {
                    $village_oases_id .= ",";
                }
                $village_oases_id .= $oid;
            }
        }
        $resultArr  = $this->_getResourcesArray($villageRow, $villageRow['resources'], $villageRow['elapsedTimeInSeconds'], $villageRow['crop_consumption'], $villageRow['cp']);
        $oasisIndex = db::get_field("SELECT v.image_num FROM p_villages v WHERE v.id=:id", array(
            'id' => intval($oasisId)
        ));
        $oasisRes   = $GLOBALS['SetupMetadata']['oasis'][$oasisIndex];
        $factor     = $capture ? 1 : 0 - 1;
        foreach ($oasisRes as $k => $v)
        {
            $resultArr['resources'][$k]['prod_rate_percentage'] += $v * $factor;
            if ($resultArr['resources'][$k]['prod_rate_percentage'] < 0)
            {
                $resultArr['resources'][$k]['prod_rate_percentage'] = 0;
            }
        }
        db::query("UPDATE p_villages v  SET v.resources=:res, v.cp=:cp, v.village_oases_id=:vid, v.last_update_date=NOW() WHERE v.id=:id", array(
            'res' => $this->_getResourcesString($resultArr['resources']),
            'cp' => $resultArr['cp']['cpValue'] . " " . $resultArr['cp']['cpRate'],
            'vid' => $village_oases_id,
            'id' => intval($villageId)
        ));
    }

    public function executeLeaveOasisTask($taskRow)
    {
        $this->captureOasis($taskRow['building_id'], $taskRow['player_id'], $taskRow['village_id'], FALSE);
    }

    public function executeMerchantTask($taskRow)
    {
        $villageRow = db::get_row("SELECT v.id, v.player_id, v.resources, v.cp, v.crop_consumption, TIME_TO_SEC(TIMEDIFF(NOW(), v.last_update_date)) elapsedTimeInSeconds  FROM p_villages v WHERE v.id=:id", array(
            'id' => intval($taskRow['to_village_id'])
        ));
        if (0 < intval($villageRow['player_id']))
        {
            $resultArr = $this->_getResourcesArray($villageRow, $villageRow['resources'], $villageRow['elapsedTimeInSeconds'], $villageRow['crop_consumption'], $villageRow['cp']);
            list($merchantNum, $resourcesStr) = explode('|', $taskRow['proc_params']);
            $resources = explode(" ", $resourcesStr);
            $i         = 0;
            foreach ($resources as $v)
            {
                $resultArr['resources'][++$i]['current_value'] += $v;
                if ($resultArr['resources'][$i]['store_max_limit'] < $resultArr['resources'][$i]['current_value'])
                {
                    $resultArr['resources'][$i]['current_value'] = $resultArr['resources'][$i]['store_max_limit'];
                }
            }
            db::query("UPDATE p_villages v  SET v.resources=:res, v.cp=:cp, v.last_update_date=NOW() WHERE v.id=:id", array(
                'res' => $this->_getResourcesString($resultArr['resources']),
                'cp' => $resultArr['cp']['cpValue'] . " " . $resultArr['cp']['cpRate'],
                'id' => intval($taskRow['to_village_id'])
            ));
        }
        if (intval(db::get_field("SELECT v.player_id FROM p_villages v WHERE v.id=:id", array(
            'id' => intval($taskRow['village_id'])
        ))) == 0)
        {
            return FALSE;
        }
        db::query("UPDATE p_queue q  SET  q.proc_type=:typ, q.end_date=(q.end_date + INTERVAL q.execution_time SECOND) WHERE q.id=:id", array(
            'typ' => QS_MERCHANT_BACK,
            'id' => intval($taskRow['id'])
        ));
        $timeInSeconds = $taskRow['remainingTimeInSeconds'];
        list($merchantsNum, $body) = explode('|', $taskRow['proc_params']);
        $res      = explode(" ", $body);
        $maxValue = 0;
        $maxIndex = 0 - 1;
        $n        = 0;
        foreach ($res as $v)
        {
            ++$n;
            if ($maxValue < $v)
            {
                $maxValue = $v;
                $maxIndex = $n;
            }
        }
        $reportResult = 10 + $maxIndex;
        $this->load_model('Report', 'r');
        $this->r->createReport($taskRow['player_id'], $taskRow['to_player_id'], $taskRow['village_id'], $taskRow['to_village_id'], 1, $reportResult, $body, $timeInSeconds);
        return TRUE;
    }

    public function returnMerchantTask($taskRow)
    {
        $villageRow = db::get_row("SELECT v.id, v.resources, v.player_id, v.cp, v.crop_consumption, TIME_TO_SEC(TIMEDIFF(NOW(), v.last_update_date)) elapsedTimeInSeconds  FROM p_villages v WHERE v.id=:id", array(
            'id' => intval($taskRow['village_id'])
        ));
        $resultArr       = $this->_getResourcesArray($villageRow, $villageRow['resources'], $villageRow['elapsedTimeInSeconds'], $villageRow['crop_consumption'], $villageRow['cp']);
        $this->load_model('Queue', 'queueModel');
        $this->queueModel->page->data['selected_village_id'] = $taskRow['village_id'];
        $this->queueModel->page->resources = $resultArr['resources'];
        $this->queueModel->page->cpValue = $resultArr['cp']['cpValue'];
        $this->queueModel->page->cpRate = $resultArr['cp']['cpRate'];
        $this->queueModel->page->player = new stdClass();
        $this->queueModel->page->player->playerId = $taskRow['player_id'];
        $this->load_library('QueueTask', 'newTask',
            array(  'taskType'      => QS_MERCHANT_GO,
                    'playerId'      => $taskRow['player_id'],
                    'executionTime' => $taskRow['execution_time']
                    )
                );
        $params = explode('|', $taskRow['proc_params']);
        $merchantsNum = $params[0];
        $body2 = isset($params[2]) ? $params[2] : $params[1];
        $body = explode(' ', $body2);
        $resources = array(
            '1' => $body[0] > $resultArr['resources'][1]['current_value'] ? $resultArr['resources'][1]['current_value'] : $body[0],
            '2' => $body[1] > $resultArr['resources'][2]['current_value'] ? $resultArr['resources'][2]['current_value'] : $body[1],
            '3' => $body[2] > $resultArr['resources'][3]['current_value'] ? $resultArr['resources'][3]['current_value'] : $body[2],
            '4' => $body[3] > $resultArr['resources'][4]['current_value'] ? ($resultArr['resources'][4]['current_value'] < 0 ? 0 : $resultArr['resources'][4]['current_value']) : $body[3]
            );
        $this->newTask->villageId   = $taskRow['village_id'];
        $this->newTask->toPlayerId  = $taskRow['to_player_id'];
        $this->newTask->toVillageId = $taskRow['to_village_id'];
        $this->newTask->procParams  = $merchantsNum.'|'.($resources[1].' '.$resources[2].' '.$resources[3].' '.$resources[4]).'|'.$body2;
        $this->newTask->tag         = $resources;
        $this->newTask->buildingId  = $taskRow['building_id']-1;
        $this->queueModel->addTask($this->newTask);
    }

    public function executeHeroTask($taskRow)
    {
        list($hero_troop_id, $hero_in_village_id) = explode(' ', $taskRow['proc_params']);
        $playerRow = db::get_row("SELECT p.villages_id, p.selected_village_id FROM p_players p WHERE p.id=:id", array(
            'id' => intval($taskRow['player_id'])
        ));
        if ($playerRow == NULL || trim($playerRow['villages_id']) == "")
        {
            return;
        }
        $hasVillage     = FALSE;
        $villages_idArr = explode(",", trim($playerRow['villages_id']));
        foreach ($villages_idArr as $pvid)
        {
            if ($pvid == $hero_in_village_id)
            {
                $hasVillage = TRUE;
                break;
            }
        }
        if (!$hasVillage)
        {
            $hero_in_village_id = $playerRow['selected_village_id'];
        }
        db::query("UPDATE p_players p SET p.hero_name=p.name, p.hero_troop_id=:hid, p.hero_in_village_id=:vid WHERE p.id=:id", array(
            'hid' => intval($hero_troop_id),
            'vid' => intval($hero_in_village_id),
            'id' => intval($taskRow['player_id'])
        ));
    }

    public function executeTroopTrainingTask($taskRow)
    {
        $villageRow = db::get_row("SELECT v.id, v.player_id, v.resources, v.cp, v.crop_consumption, v.time_consume_percent, v.troops_num, TIME_TO_SEC(TIMEDIFF(NOW(), v.last_update_date)) elapsedTimeInSeconds  FROM p_villages v WHERE v.id=:id", array(
            'id' => intval($taskRow['village_id'])
        ));
        if (intval($villageRow['player_id']) == 0 || intval($villageRow['player_id']) != $taskRow['player_id'])
        {
            return;
        }
        $resultArr               = $this->_getResourcesArray($villageRow, $villageRow['resources'], $villageRow['elapsedTimeInSeconds'], $villageRow['crop_consumption'], $villageRow['cp']);
        $troopId                 = $taskRow['proc_params'];
        $troopsNumber            = $taskRow['threads_completed_num'];
        $troops_crop_consumption = $troopsNumber * $GLOBALS['GameMetadata']['troops'][$troopId]['crop_consumption'];
        $troopsArray             = $this->_getTroopsArray($villageRow['troops_num']);
        if (isset($troopsArray[0 - 1]))
        {
            if (isset($troopsArray[0 - 1][$troopId]))
            {
                $troopsArray[0 - 1][$troopId] += $troopsNumber;
            }
            else if ($troopId == 99)
            {
                $troopsArray[0 - 1][$troopId] = $troopsNumber;
            }
        }
        $troopTrainingStr = $this->_getTroopsString($troopsArray);

        db::query("UPDATE p_villages v SET v.resources=:res, v.cp=:cp, v.crop_consumption=v.crop_consumption+$troops_crop_consumption, v.troops_num=:tnum, v.last_update_date=NOW() WHERE v.id=:id", array(
            'res' => $this->_getResourcesString($resultArr['resources']),
            'cp' => $resultArr['cp']['cpValue'] . " " . $resultArr['cp']['cpRate'],
            'tnum' => $troopTrainingStr,
            'id' => intval($taskRow['village_id'])
        ));
    }

    public function executeCelebrationTask($taskRow)
    {
        $villageRow = db::get_row("SELECT v.id, v.player_id, v.resources, v.cp, v.crop_consumption, TIME_TO_SEC(TIMEDIFF(NOW(), v.last_update_date)) elapsedTimeInSeconds  FROM p_villages v WHERE v.id=:id", array(
            'id' => intval($taskRow['village_id'])
        ));
        if (intval($villageRow['player_id']) == 0)
        {
            return;
        }
        $resultArr       = $this->_getResourcesArray($villageRow, $villageRow['resources'], $villageRow['elapsedTimeInSeconds'], $villageRow['crop_consumption'], $villageRow['cp']);
        $celebrationType = $taskRow['proc_params'] == 1 ? "small" : "large";
        $resultArr['cp']['cpValue'] += $GLOBALS['GameMetadata']['items'][24]['celebrations'][$celebrationType]['value'];
        db::query("UPDATE p_villages v  SET v.resources=:res, v.cp=:cp, v.last_update_date=NOW() WHERE v.id=:id", array(
            'res' => $this->_getResourcesString($resultArr['resources']),
            'cp' => $resultArr['cp']['cpValue'] . " " . $resultArr['cp']['cpRate'],
            'id' => intval($taskRow['village_id'])
        ));
    }

    public function executeTroopUpgradeTask($taskRow)
    {
        $villageRow = db::get_row("SELECT v.player_id, v.troops_training FROM p_villages v WHERE v.id=:id", array(
            'id' => intval($taskRow['village_id'])
        ));
        if (intval($villageRow['player_id']) == 0 || intval($villageRow['player_id']) != $taskRow['player_id'])
        {
            return;
        }
        $this->troopsUpgrade = array();
        $_arr                = explode(",", $villageRow['troops_training']);
        foreach ($_arr as $troopStr)
        {
            list($troopId, $researches_done, $defense_level, $attack_level) = explode(' ', $troopStr);
            $this->troopsUpgrade[$troopId] = array(
                "researches_done" => $researches_done,
                "defense_level" => $defense_level,
                "attack_level" => $attack_level
            );
        }
        switch ($taskRow['proc_type'])
        {
            case QS_TROOP_RESEARCH:
            {
                $tid = $taskRow['proc_params'];
                if (isset($this->troopsUpgrade[$tid]))
                {
                    $this->troopsUpgrade[$tid]['researches_done'] = 1;
                }
                break;
            }
            case QS_TROOP_UPGRADE_ATTACK:
            {
                list($tid, $level) = explode(' ', $taskRow['proc_params']);
                if (isset($this->troopsUpgrade[$tid]))
                {
                    $this->troopsUpgrade[$tid]['attack_level'] = $level;
                }
                break;
            }
            case QS_TROOP_UPGRADE_DEFENSE:
            {
                list($tid, $level) = explode(' ', $taskRow['proc_params']);
                if (isset($this->troopsUpgrade[$tid]))
                {
                    $this->troopsUpgrade[$tid]['defense_level'] = $level;
                }
            }
        }
        $troopTrainingStr = "";
        foreach ($this->troopsUpgrade as $k => $v)
        {
            if ($troopTrainingStr != "")
            {
                $troopTrainingStr .= ",";
            }
            $troopTrainingStr .= $k . " " . $v['researches_done'] . " " . $v['defense_level'] . " " . $v['attack_level'];
        }
        db::query("UPDATE p_villages v SET v.troops_training=:tr WHERE v.id=:id", array(
            ':tr' => $troopTrainingStr,
            ':id' => intval($taskRow['village_id'])
        ));
    }

    public function executePlusTask($taskRow, $resource_id)
    {
        $villageRow = db::get_row("SELECT v.id, v.player_id, v.resources, v.cp, v.crop_consumption, TIME_TO_SEC(TIMEDIFF(NOW(), v.last_update_date)) elapsedTimeInSeconds  FROM p_villages v WHERE v.id=:id", array(
            'id' => intval($taskRow['village_id'])
        ));
        if (intval($villageRow['player_id']) == 0)
        {
            return;
        }
        $resultArr = $this->_getResourcesArray($villageRow, $villageRow['resources'], $villageRow['elapsedTimeInSeconds'], $villageRow['crop_consumption'], $villageRow['cp']);
        $resultArr['resources'][$resource_id]['prod_rate_percentage'] -= 1000;
        if ($resultArr['resources'][$resource_id]['prod_rate_percentage'] < 0)
        {
            $resultArr['resources'][$resource_id]['prod_rate_percentage'] = 0;
        }
        db::query("UPDATE p_villages v  SET v.resources=:res, v.cp=:cp, v.last_update_date=NOW() WHERE v.id=:id", array(
            'res' => $this->_getResourcesString($resultArr['resources']),
            'cp' => $resultArr['cp']['cpValue'] . " " . $resultArr['cp']['cpRate'],
            'id' => intval($taskRow['village_id'])
        ));
    }

    public function executeBuildingTask($taskRow, $drop = FALSE)
    {
        return $this->upgradeBuilding($taskRow['village_id'], $taskRow['proc_params'], $taskRow['building_id'], $drop);
    }

    public function executeBuildingDropTask($taskRow)
    {
        return $this->executeBuildingTask($taskRow, TRUE);
    }

    public function executeWarTask($taskRow)
    {
        $this->load_model('Battle', 'm');
        return $this->m->executeWarResult($taskRow);
    }

    public function upgradeBuilding($villageId, $bid, $itemId, $drop = FALSE)
    {
        $customAction = FALSE;
        $GameMetadata = $GLOBALS['GameMetadata'];
        $villageRow   = db::get_row("SELECT v.id, v.player_id, v.alliance_id, v.buildings, v.resources, v.cp, v.crop_consumption, v.time_consume_percent, TIME_TO_SEC(TIMEDIFF(NOW(), v.last_update_date)) elapsedTimeInSeconds  FROM p_villages v WHERE v.id=:id", array(
            'id' => intval($villageId)
        ));
        if (intval($villageRow['player_id']) == 0)
        {
            return $customAction;
        }
        $buildings        = $this->_getBuildingsArray($villageRow['buildings']);
        $build            = $buildings[$bid];
        $buildingMetadata = $GameMetadata['items'][$itemId];
        if ($build['item_id'] != $itemId)
        {
            return $customAction;
        }
        if ($drop && $build['level'] <= 0)
        {
            return $customAction;
        }
        $LevelOffset      = $drop ? 0 - 1 : 1;
        $_resFactor       = $itemId <= 4 ? $GameMetadata['game_speed'] : 1;
        $buildingLevel    = $build['level'];
        $oldValue         = ($buildingLevel == 0 ? $itemId <= 4 ? 2 : 0 : $buildingMetadata['levels'][$buildingLevel - 1]['value']) * $_resFactor;
        $oldCP            = $buildingLevel == 0 ? 0 : $buildingMetadata['levels'][$buildingLevel - 1]['cp'];
        $newBuildingLevel = $buildingLevel + $LevelOffset;
        $newValue         = ($newBuildingLevel == 0 ? $itemId <= 4 ? 2 : 0 : $buildingMetadata['levels'][$newBuildingLevel - 1]['value']) * $_resFactor;
        $newCP            = $newBuildingLevel == 0 ? 0 : $buildingMetadata['levels'][$newBuildingLevel - 1]['cp'];
        $value_inc        = $newValue - $oldValue;
        $people_inc       = $drop ? 0 - 1 * $buildingMetadata['levels'][$buildingLevel - 1]['people_inc'] : $buildingMetadata['levels'][$newBuildingLevel - 1]['people_inc'];
        $resultArr        = $this->_getResourcesArray($villageRow, $villageRow['resources'], $villageRow['elapsedTimeInSeconds'], $villageRow['crop_consumption'], $villageRow['cp']);
        $resultArr['cp']['cpRate'] += $newCP - $oldCP;
        $allegiance_percent_inc = 0;
        switch ($itemId)
        {
            case 1:
            case 2:
            case 3:
            case 4:
                $resultArr['resources'][$itemId]['prod_rate'] += $value_inc;
                break;
            case 5:
            case 6:
            case 7:
            case 8:
                $resultArr['resources'][$itemId - 4]['prod_rate_percentage'] += $value_inc;
                break;
            case 9:
                $resultArr['resources'][4]['prod_rate_percentage'] += $value_inc;
                break;
            case 10:
            case 38:
                $newStorage = $resultArr['resources'][1]['store_max_limit'] == $resultArr['resources'][1]['store_init_limit'] ? 0 : $resultArr['resources'][1]['store_max_limit'];
                $newStorage = $newStorage + $value_inc;
                if ($newStorage < $resultArr['resources'][1]['store_init_limit'])
                {
                    $newStorage = $resultArr['resources'][1]['store_init_limit'];
                }
                $resultArr['resources'][1]['store_max_limit'] = $resultArr['resources'][2]['store_max_limit'] = $resultArr['resources'][3]['store_max_limit'] = $newStorage;
                break;
            case 11:
            case 39:
                $newStorage = $resultArr['resources'][4]['store_max_limit'] == $resultArr['resources'][4]['store_init_limit'] ? 0 : $resultArr['resources'][4]['store_max_limit'];
                $newStorage = $newStorage + $value_inc;
                if ($newStorage < $resultArr['resources'][4]['store_init_limit'])
                {
                    $newStorage = $resultArr['resources'][4]['store_init_limit'];
                }
                $resultArr['resources'][4]['store_max_limit'] = $newStorage;
                break;
            case 15:
                $villageRow['time_consume_percent'] = $newValue == 0 ? 300 : $newValue;
                break;
            case 18:
                if (0 < intval($villageRow['alliance_id']) && !$drop)
                {
                    db::query("UPDATE p_alliances a SET a.max_player_count=:max WHERE a.id=:id AND a.creator_player_id=:pid AND a.max_player_count<:max", array(
                        ':max' => $newValue,
                        ':id' => intval($villageRow['alliance_id']),
                        ':pid' => intval($villageRow['player_id'])
                    ));
                }
                break;
            case 25:
            case 26:
                if (!$drop)
                {
                    $allegiance_percent_inc = 10;
                }
                break;
            case 40:
                global $gameConfig;
                if (($newBuildingLevel == 25 && !$drop) || ($newBuildingLevel == 50 && !$drop) || ($newBuildingLevel == 75 && !$drop) || ( $newBuildingLevel >= 90 && $newBuildingLevel < 100 && !$drop) )
                {
                    $tatarId = db::get_row("SELECT p.id FROM  p_players p WHERE p.tribe_id=5");
                    $tatarvilldgId = db::get_row("SELECT v.id FROM  p_villages v WHERE v.player_id=:id and v.is_capital=1", array('id' => $tatarId['id']));
                    $troop_ids = array();
                    foreach ($GLOBALS['GameMetadata']['troops'] as $k => $v)
                    {
                      if ($v['for_tribe_id'] == 5)
                        {
                            $troop_ids[] = $k;
                        }
                    }
                    $troops_num = "";
                    foreach ($troop_ids as $tid)
                    {
                        if ($troops_num != "")
                        {
                            $troops_num .= ",";
                        }
                       $trnum = explode(',', $gameConfig['troop']['tatarAtt']);
                       $num = $tid == 49 || $tid == 50 ? 0 : mt_rand($trnum[0], $trnum[1]);
                       if ($tid == 47 || $tid == 48)
                        {
                            $trmnum = explode(',', $gameConfig['troop']['tatarAttM']);
                            $num = mt_rand($trmnum[0], $trmnum[1]);
                        }
                       $troops_num .= sprintf("%s %s", $tid, $num);
                     }
                     $troops_num .= '|0|0|2:40 0||||0';

                     $this->load_model('Queue', 'queueModel');
                     $this->load_library('QueueTask', 'newTask',
                        array(  'taskType'      => QS_WAR_ATTACK,
                                'playerId'      => $tatarId['id'],
                                'executionTime' =>  10
                            ));
					 $this->queueModel->page->data['selected_village_id'] = $villageId;
                     $this->newTask->villageId = $tatarvilldgId['id'];
                     $this->newTask->toPlayerId = $villageRow['player_id'];
                     $this->newTask->toVillageId = $villageId;
                     $this->newTask->procParams = $troops_num;
                     $this->queueModel->addTask($this->newTask);
                }
                if ($newBuildingLevel == sizeof($buildingMetadata['levels']))
                {
                    $customAction = TRUE;
                    db::query("DELETE FROM p_queue");
                    $resetTime  = $gameConfig['settings']['resetTime']*3600;
                    $this->load_model('Queue', 'queueModel');
                    $this->load_library('QueueTask', 'QueueTaskaddtask',
                        array(  'taskType'      => QS_SITE_RESET,
                                'playerId'      => 0,
                                'executionTime' => $resetTime
                            ));
                    $this->queueModel->addTask($this->QueueTaskaddtask);

                    db::query("UPDATE g_settings gs SET gs.game_over=1, gs.win_pid=:id", array(
                        'id' => intval($villageRow['player_id'])
                    ));
                    db2::query("UPDATE p_players p SET p.gold_num=p.gold_num+:gold, p.gold_buy=p.gold_buy+:gold WHERE p.id=:id", array(
                        'gold' => $gameConfig['settings']['wingold'],
                        'id' => intval($villageRow['player_id'])
                    ));
					$Allid = db::get_field("SELECT p.alliance_id FROM p_players p WHERE p.id=:id",array('id' => intval($villageRow['player_id'])));
					$allP = db::get_all("SELECT p.id FROM p_players p WHERE p.alliance_id=:id and p.id!=:pid AND p.total_people_count>10000", array('id' => $Allid, 'pid' => intval($villageRow['player_id'])));
                    if($allP != null){
                        $Ids2 = "";
					    foreach($allP as $Ids)
					    {
						    if($Ids['id'] != $villageRow['player_id'])
						    {
							     $Ids2 .= ($Ids2 == "" ) ? $Ids['id'] : ','.$Ids['id'];
						    }
					   }
					   $Ids2 = "(".$Ids2.")";
                       db2::query("UPDATE p_players p SET p.gold_num=p.gold_num+:gold WHERE p.is_active=1 AND p.id IN $Ids2",array('gold' => $gameConfig['settings']['wingold']/10));
                    }
					
                }
        }
        $buildings[$bid]['level'] += $LevelOffset;
        if (!$drop)
        {
            --$buildings[$bid]['update_state'];
        }
        else if ($buildings[$bid]['level'] <= 0 && $buildings[$bid]['update_state'] == 0 && 4 < $buildings[$bid]['item_id'])
        {
            $buildings[$bid]['item_id'] = 0;
        }
        if ($buildings[$bid]['update_state'] < 0)
        {
            $buildings[$bid]['update_state'] = 0;
        }
        $buildingsString = $this->_getBuildingString($buildings);
        if ($drop and $itemId == 40)
        {
            $b_arr = explode( ',', $buildingsString );
            $newbuilding = '';
            foreach( $b_arr as $b_str )
            {
                $b2_arr = explode( ' ', $b_str );
                if ($b2_arr[0] == 40 and $b2_arr[1] == 0){
                $upstat = ($b2_arr[2] - 1);
                if ($upstat <= 0){
                $upstat = 0;
                }
                if ($upstat == 0){
                $b2_arr[0] = 0;
                }
                $b_str = $b2_arr[0].' '.$b2_arr[1].' '.$upstat;
                }
                if ($newbuilding != ''){
                   $newbuilding .= ',';
                    }
                $newbuilding .= $b_str;
            }
            $buildingsString = $newbuilding;
        }
        db::query("UPDATE p_villages v  SET v.buildings=:bu, v.resources=:res, v.cp=:cp, v.crop_consumption=v.crop_consumption+:po, v.people_count=v.people_count+:po, v.time_consume_percent=:ti, v.allegiance_percent=IF(v.allegiance_percent+:ap>=100, 100, v.allegiance_percent+:ap), v.last_update_date=NOW() WHERE v.id=:id", array(
            'bu' => $buildingsString,
            'res' => $this->_getResourcesString($resultArr['resources']),
            'cp' => $resultArr['cp']['cpValue'] . " " . $resultArr['cp']['cpRate'],
            'po' => $people_inc,
            'ti' => $villageRow['time_consume_percent'],
            'ap' => $allegiance_percent_inc,
            'id' => intval($villageId)
        ));
        $devPoint = $people_inc;
        db::query("UPDATE p_players p SET p.total_people_count=p.total_people_count+:to, p.week_dev_points=p.week_dev_points+:we WHERE p.id=:id", array(
            'to' => $people_inc,
            'we' => $devPoint,
            'id' => intval($villageRow['player_id'])
        ));
        if (0 < intval($villageRow['alliance_id']))
        {
            db::query("UPDATE p_alliances a SET a.week_dev_points=a.week_dev_points+:we WHERE a.id=:id", array(
                'we' => $devPoint,
                'id' => intval($villageRow['alliance_id'])
            ));
        }
        return $customAction;
    }

    public function _getTroopsString($troopsArray)
    {
        $result = "";
        foreach ($troopsArray as $vid => $troopsNumArray)
        {
            if ($result != "")
            {
                $result .= "|";
            }
            $innerResult = "";
            foreach ($troopsNumArray as $tid => $num)
            {
                if ($innerResult != "")
                {
                    $innerResult .= ",";
                }
                if ($tid == 0 - 1)
                {
                    $innerResult .= $num . " " . $tid;
                }
                else
                {
                    $innerResult .= $tid . " " . $num;
                }
            }
            $result .= $vid . ":" . $innerResult;
        }
        return $result;
    }

    public function _getTroopsArray($troops_num)
    {
        $troopsArray = array();
        $t_arr       = explode("|", $troops_num);
        foreach ($t_arr as $t_str)
        {
            $t2_arr            = explode(":", $t_str);
            $vid               = $t2_arr[0];
            $troopsArray[$vid] = array();
            $t2_arr            = explode(",", $t2_arr[1]);
            foreach ($t2_arr as $t2_str)
            {
                $t = explode(" ", $t2_str);
                if ($t[1] == 0 - 1)
                {
                    $troopsArray[$vid][$t[1]] = $t[0];
                }
                else
                {
                    $troopsArray[$vid][$t[0]] = $t[1];
                }
            }
        }
        return $troopsArray;
    }

    public function _getBuildingsArray($buildingsString)
    {
        $buildings = array();
        $b_arr     = explode(",", $buildingsString);
        $indx      = 0;
        foreach ($b_arr as $b_str)
        {
            ++$indx;
            $b2               = explode(" ", $b_str);
            $buildings[$indx] = array(
                "index" => $indx,
                "item_id" => $b2[0],
                "level" => $b2[1],
                "update_state" => $b2[2]
            );
        }
        return $buildings;
    }

    public function _getResourcesArray($villageRow, $resourceString, $elapsedTimeInSeconds, $crop_consumption, $cp)
    {
        $this->load_model('Artefacts', 'A');
        $crop = $this->A->CropAndRes($villageRow['player_id'], $villageRow['id'], 5);
        $res  = $this->A->CropAndRes($villageRow['player_id'], $villageRow['id'], 7);
        $resources = array();
        $r_arr     = explode(",", $resourceString);
        foreach ($r_arr as $r_str)
        {
            $r2            = explode(" ", $r_str);
            $prate         = floor($r2[4] * (1 + ($r2[5]+$res) / 100)) - ($r2[0] == 4 ? floor($crop_consumption*$crop) : 0);
            $current_value = floor($r2[1] + $elapsedTimeInSeconds * ($prate / 3600));
            if ($r2[2] < $current_value)
            {
                $current_value = $r2[2];
            }
            $resources[$r2[0]] = array(
                "current_value" => $current_value,
                "store_max_limit" => $r2[2],
                "store_init_limit" => $r2[3],
                "prod_rate" => $r2[4],
                "prod_rate_percentage" => $r2[5]
            );
        }
        list($cpValue, $cpRate) = explode(' ', $cp);
        $cpValue += $elapsedTimeInSeconds * ($cpRate / 86400);
        return array(
                "resources" => $resources,
                "cp" => array(
                "cpValue" => round($cpValue, 4),
                "cpRate" => $cpRate
                )
            );
    }

    public function _getResourcesString($resources)
    {
        $result = "";
        foreach ($resources as $k => $v)
        {
            if ($result != "")
            {
                $result .= ",";
            }
            $result .= $k . " " . $v['current_value'] . " " . $v['store_max_limit'] . " " . $v['store_init_limit'] . " " . $v['prod_rate'] . " " . $v['prod_rate_percentage'];
        }
        return $result;
    }

    public function _getBuildingString($buildings)
    {
        $result = "";
        foreach ($buildings as $build)
        {
            if ($result != "")
            {
                $result .= ",";
            }
            $result .= $build['item_id'] . " " . $build['level'] . " " . $build['update_state'];
        }
        return $result;
    }

}
?>