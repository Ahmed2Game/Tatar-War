<?php
load_lang('ui/artefacts');

class Artefacts_Model extends Model
{
    public function createArtefacts()
    {
        $map_size = $GLOBALS['SetupMetadata']['map_size'];
        global $gameConfig;
        $this->load_model('Register', 'm');
        $admin = db2::get_row("SELECT  p.id, p.name FROM p_players p  WHERE  p.name=:name",
            array('name' => LANGUI_ART_1)
        );
        if ($admin === FALSE) {
            $result = $this->m->createMaster(LANGUI_ART_1, "tatar@xtatar.com", "12345609", 0, 1);
            $playerId = $result['result'] > 0 ? db2::get_field("SELECT LAST_INSERT_ID() FROM p_players") : 1;
            $result = $this->m->createNewPlayer($playerId, LANGUI_ART_1, 5, 0, LANGUI_ART_2, $map_size, PLAYERTYPE_TATAR, 46, get_ip());
        } else {
            $result = $this->m->createNewPlayer($admin['id'], LANGUI_ART_1, 5, 0, LANGUI_ART_2, $map_size, PLAYERTYPE_TATAR, 46, get_ip());
        }
        if ($result['hasErrors']) {
            return;
        }
        db::query("UPDATE p_players p SET p.total_people_count=5166, p.description1=:des, p.guide_quiz='-1' WHERE id=:id", array(
            'des' => LANGUI_ART_3,
            'id' => intval($result['playerId'])
        ));
        $this->News(LANGUI_ART_4);
        $troop_ids = array();
        foreach ($GLOBALS['GameMetadata']['troops'] as $k => $v) {
            if ($v['for_tribe_id'] == 5) {
                $troop_ids[] = $k;
            }
        }
        $i = 0;
        $size = 1;
        foreach ($result['villages'] as $createdVillage => $v) {
            if ($i > 0) {
                if ($i <= 9) {
                    $type = $i;
                    $size = 3;
                } elseif ($i <= 18) {
                    $type = $i - 9;
                    $size = 2;
                } elseif ($i <= 27) {
                    $type = $i - 18;
                    $size = 1;
                } elseif ($i <= 36) {
                    $type = $i - 27;
                    $size = 2;
                } elseif ($i <= 45) {
                    $type = $i - 36;
                    $size = 1;
                } elseif ($i <= 54) {
                    $type = $i - 45;
                    $size = 2;
                } elseif ($i <= 63) {
                    $type = $i - 54;
                    $size = 1;
                } elseif ($i <= 72) {
                    $type = $i - 63;
                    $size = 2;
                } elseif ($i <= 81) {
                    $type = $i - 72;
                    $size = 1;
                }
                db::query("INSERT INTO artefacts SET in_village_id=:vid, player_id=:pid, type=:ty, size=:si, conquered=NOW()", array(
                    'pid' => intval($result['playerId']),
                    'vid' => intval($createdVillage),
                    'ty' => $type,
                    'si' => $size
                ));
            }
            $troops_num = "";
            foreach ($troop_ids as $tid) {
                if ($troops_num != "") {
                    $troops_num .= ",";
                }
                $trnum = explode(',', $gameConfig['troop']['inArtef']);
                $num = $tid == 49 || $tid == 50 ? 0 : mt_rand($trnum[0] * $size, $trnum[1] * $size);
                $troops_num .= sprintf("%s %s", $tid, $num);
            }
            $troops_num = "-1:" . $troops_num;
            db::query("UPDATE p_villages v SET v.troops_num=:num, v.is_capital=:is, v.people_count=:pe WHERE v.id=:id", array(
                'num' => $troops_num,
                'is' => ($i == 0) ? "1" : "0",
                'pe' => 63,
                'id' => intval($createdVillage)
            ));
            ++$i;
        }
        unset($result);

    }

    public function createTatarVillages()
    {
        $map_size = $GLOBALS['SetupMetadata']['map_size'];
        $result = db::get_field("SELECT p.id FROM p_players p WHERE p.player_type=:pt", array('pt' => PLAYERTYPE_TATAR));
        $this->load_model('Queuejob', 'mj');
        $this->mj->deletePlayer($result);
        $this->load_model('Register', 'm');
        $admin = db2::get_row("SELECT  p.id, p.name FROM p_players p  WHERE  p.name=:name",
            array('name' => tatar_tribe_player)
        );
        if ($admin === FALSE) {
            $result = $this->m->createMaster(tatar_tribe_player, "tatar2@xtatar.com", "12345609", 0, 1);
            $playerId = $result['result'] > 0 ? db2::get_field("SELECT LAST_INSERT_ID() FROM p_players") : 1;
            $result = $this->m->createNewPlayer($playerId, tatar_tribe_player, 5, 0, tatar_tribe_villages, $map_size, PLAYERTYPE_TATAR, 13, get_ip());
        } else {
            $result = $this->m->createNewPlayer($admin['id'], tatar_tribe_player, 5, 0, tatar_tribe_villages, $map_size, PLAYERTYPE_TATAR, 13, get_ip());
        }
        if ($result['hasErrors']) {
            return;
        }
        db::query("UPDATE p_players p SET p.total_people_count=15045, p.description1=:des, p.guide_quiz='-1' WHERE id=:id", array(
            'des' => tatar_tribe_desc,
            'id' => intval($result['playerId'])
        ));
        $this->News(LANGUI_ART_5);
        $troop_ids = array();
        foreach ($GLOBALS['GameMetadata']['troops'] as $k => $v) {
            if ($v['for_tribe_id'] == 5) {
                $troop_ids[] = $k;
            }
        }
        $firstFlag = TRUE;
        global $gameConfig;
        foreach ($result['villages'] as $createdVillage => $v) {
            $troops_num = "";
            foreach ($troop_ids as $tid) {
                if ($troops_num != "") {
                    $troops_num .= ",";
                }
                $trnum = explode(',', $gameConfig['troop']['inTatar']);
                $num = $tid == 49 || $tid == 50 ? 0 : mt_rand($trnum[0], $trnum[1]);
                $troops_num .= sprintf("%s %s", $tid, $num);
            }
            $troops_num = "-1:" . $troops_num;
            db::query("UPDATE p_villages v SET v.troops_num=:num, v.is_capital=:is, v.people_count=:pe WHERE v.id=:id", array(
                'num' => $troops_num,
                'is' => $firstFlag ? "1" : "0",
                'pe' => $firstFlag ? "864" : "163",
                'id' => intval($createdVillage)
            ));
            $firstFlag = FALSE;
        }
        unset($result);

    }

    public function News($text)
    {
        db::query("UPDATE g_summary g SET g.gnews_text=:news", array(
            'news' => $text
        ));
        db::query("UPDATE p_players p SET p.new_gnews=1");
    }

    public function GetMyArtefacts($villageId, $playerId)
    {
        return db::get_row('SELECT a.*, DATE_FORMAT(a.conquered, "%y/%m/%d %H:%i") mdate FROM artefacts a WHERE a.in_village_id=:vid AND a.player_id=:pid', array(
            'vid' => $villageId,
            'pid' => $playerId
        ));
    }

    public function GetArtefactsPyType($size)
    {
        return db::get_all('SELECT * FROM artefacts WHERE size=:si ORDER BY type ASC', array(
            'si' => $size
        ));
    }

    public function GetVillageDataPyId($villageId)
    {
        return db::get_row('SELECT v.rel_x, v.rel_y, v.player_name, v.village_name, v.alliance_id, v.alliance_name FROM p_villages v WHERE v.id=:id', array(
            'id' => $villageId
        ));
    }

    public function GetArtefactsPyId($id)
    {
        return db::get_row('SELECT a.*, DATE_FORMAT(a.conquered, "%y/%m/%d %H:%i") mdate FROM artefacts a WHERE a.id=:id', array(
            'id' => $id
        ));
    }

    public function GetArtefactsNum($playerId)
    {
        return db::get_field('SELECT COUNT(*) FROM artefacts a WHERE a.player_id=:pid', array(
            'pid' => $playerId
        ));
    }

    public function GetArtefactsNumPig($playerId)
    {
        return db::get_field('SELECT COUNT(*) FROM artefacts a WHERE a.player_id=:pid AND (a.size=2 or a.size=3)', array(
            'pid' => $playerId
        ));
    }

    public function captureArtefacts($villageId, $playerId, $toVillageId, $toPlayerId)
    {
        db::query('UPDATE artefacts a SET a.player_id=:tpid, a.in_village_id=:tvid, a.conquered=NOW() WHERE a.player_id=:pid AND a.in_village_id=:vid', array(
            'tpid' => $toPlayerId,
            'tvid' => $toVillageId,
            'pid' => $playerId,
            'vid' => $villageId
        ));
    }

    public function Artefacts($playerId, $villageId, $type)
    {
        $pigArt = db::get_row('SELECT * FROM artefacts a WHERE a.player_id=:pid AND (a.size=2 or a.size=3) ORDER BY TIMESTAMPDIFF(SECOND, a.conquered, NOW()) DESC LIMIT 1', array(
            'pid' => $playerId
        ));
        $smallNum = ($pigArt) ? 2 : 3;
        $smallArt = db::get_all("SELECT * FROM artefacts a WHERE a.player_id=:pid AND a.size=1 ORDER BY TIMESTAMPDIFF(SECOND, a.conquered, NOW()) DESC LIMIT $smallNum", array(
            'pid' => $playerId
        ));
        $artefact = 0;
        if ($smallArt) {
            foreach ($smallArt as $value) {
                if ($value['type'] == $type AND $value['in_village_id'] == $villageId) {
                    $artefact = 1;
                }
            }
        }
        if ($artefact == 0 AND $pigArt) {
            if ($type == $pigArt['type']) {
                $artefact = $pigArt['size'];
            }
        }
        return $artefact;
    }

    public function CropAndRes($playerId, $villageId, $type)
    {
        if ($type == 5) {
            $artLevel = $this->Artefacts($playerId, $villageId, $type);
            $artPower = ($artLevel == 0) ? 1 : (($artLevel == 1) ? 0.5 : (($artLevel == 2) ? 0.75 : 0.5));
        } else {
            $artLevel = $this->Artefacts($playerId, $villageId, $type);
            $artPower = ($artLevel == 0) ? 0 : (($artLevel == 1) ? 75 : (($artLevel == 2) ? 50 : 125));
        }
        return $artPower;
    }

}