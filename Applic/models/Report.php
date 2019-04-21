<?php

class Report_Model extends Model
{
    var $maxReportBoxSize = 50000;

    public function getPlayerAllianceId($playerId)
    {
        return db::get_field('SELECT p.alliance_id FROM p_players p WHERE p.id=:playerId', array(
            'playerId' => $playerId
        ));
    }

    public function getReportListCount($playerId, $cat, $isSpy)
    {
        $expr = ($cat == 0 ? '' : ' AND r.rpt_cat=' . $cat);
        if ($isSpy) {
            return db::get_field("SELECT COUNT(*)
    			FROM p_rpts r
    			WHERE
    				( (r.to_player_id=:playerId) OR (r.from_player_id=:playerId) ) $expr", array(
                'playerId' => $playerId
            ));
        } else {
            return db::get_field("SELECT COUNT(*)  FROM p_rpts r WHERE
    				( (r.to_player_id=:playerId AND r.delete_status!=1) OR (r.from_player_id=:playerId AND r.delete_status!=2) ) $expr",
                array(
                    'playerId' => $playerId
                ));
        }
    }

    public function getReportList($playerId, $cat, $pageIndex, $pageSize, $isSpay)
    {
        $pageindexsize = $pageIndex * $pageSize;
        $expr = ($cat == 0 ? '' : ' AND r.rpt_cat=' . $cat);
        if ($isSpay) {
            return db::get_all("SELECT
    				r.id,
    				r.to_player_id,
    				r.from_player_id,
    				r.from_village_name,
    				r.to_village_name,
    				r.rpt_body,
    				r.rpt_cat,
    				r.rpt_result,
    				IF(r.to_player_id=:playerId, r.read_status=1 OR r.read_status=3, r.read_status=2 OR r.read_status=3) is_readed,
    				DATE_FORMAT(r.creation_date, '%y/%m/%d %H:%i') mdate
    			FROM p_rpts r
    			WHERE ( (r.to_player_id=:playerId) OR (r.from_player_id=:playerId AND r.rpt_cat!=5) ) $expr
    			ORDER BY r.creation_date DESC
    			LIMIT $pageindexsize,$pageSize",
                array(
                    'playerId' => $playerId,
                ));
        } else {
            $pageindexsize = $pageIndex * $pageSize;
            return db::get_all("SELECT
				r.id,
				r.to_player_id,
				r.from_player_id,
				r.from_village_name,
				r.to_village_name,
				r.rpt_body,
				r.rpt_cat,
				r.rpt_result,
				IF(r.to_player_id=:playerId, r.read_status=1 OR r.read_status=3, r.read_status=2 OR r.read_status=3) is_readed,
				DATE_FORMAT(r.creation_date, '%y/%m/%d %H:%i') mdate
			FROM p_rpts r
			WHERE
				( (r.to_player_id=:playerId AND r.delete_status!=1) OR (r.from_player_id=:playerId AND r.delete_status!=2 AND r.rpt_cat!=5) ) $expr
			ORDER BY r.creation_date DESC
			LIMIT $pageindexsize,$pageSize",
                array(
                    'playerId' => $playerId
                ));
        }
    }

    public function deleteReport($playerId, $reportId)
    {
        $result = db::get_row('SELECT r.to_player_id,r.from_player_id,r.read_status,r.delete_status
			FROM p_rpts r
			WHERE
				r.id=:reportId AND (r.from_player_id=:playerId OR r.to_player_id=:playerId)',
            array(
                'reportId' => $reportId,
                'playerId' => $playerId
            )
        );
        if (!$result) {
            return FALSE;
        }
        $deleteStatus = $result['delete_status'];
        $toPlayerId = $result['to_player_id'];
        $fromPlayerId = $result['from_player_id'];
        $readStatus = $result['read_status'];


        db::query('UPDATE p_rpts r
				SET
					r.delete_status=:status
				WHERE
					r.id=:reportId AND (r.from_player_id=:playerId OR r.to_player_id=:playerId)',
            array(
                'status' => ($toPlayerId == $playerId ? 1 : 2),
                'reportId' => $reportId,
                'playerId' => $playerId
            )
        );


        if ($toPlayerId == $playerId) {
            if (($readStatus == 0 || $readStatus == 2)) {
                $this->markReportAsReaded($playerId, $toPlayerId, $reportId, $readStatus);
                return TRUE;
            }
        } else {
            if (($readStatus == 0 || $readStatus == 1)) {
                $this->markReportAsReaded($playerId, $toPlayerId, $reportId, $readStatus);
                return TRUE;
            }
        }
        return FALSE;
    }

    public function markReportAsReaded($playerId, $rtoPlayerId, $reportId, $read_status)
    {
        $newReadStatus = ($playerId == $rtoPlayerId ? 1 : 2) + $read_status;
        db::query('UPDATE p_rpts r SET r.read_status=:newReadStatus WHERE r.id=:reportId', array(
            'newReadStatus' => $newReadStatus,
            'reportId' => $reportId
        ));

        db::query('UPDATE p_players p
			SET p.new_report_count=IF(p.new_report_count-1<0, 0, p.new_report_count-1)
			WHERE p.id=:playerId', array('playerId' => $playerId));
    }

    public function getReport($reportId)
    {
        return db::get_row('SELECT
				r.from_player_id,
				r.to_player_id,
				r.from_village_id,
				r.to_village_id,
				r.from_player_name,
				r.to_player_name,
				r.from_village_name,
				r.to_village_name,
				r.rpt_body,
				r.rpt_cat,
				r.read_status,
				r.delete_status,
				DATE_FORMAT(r.creation_date, \'%y/%m/%d\') mdate,
				DATE_FORMAT(r.creation_date, \'%H:%i:%s\') mtime
			FROM p_rpts r
			WHERE
				r.id=:reportId', array(
            'reportId' => $reportId
        ));
    }

    public function getPlayerID($playerName)
    {
        return db::get_field("SELECT p.id FROM p_players p WHERE p.name=:playerName", array(
            'playerName' => $playerName
        ));
    }

    public function getPlayerName($playerId)
    {
        return db::get_field('SELECT p.name FROM p_players p WHERE p.id=:playerId', array(
            'playerId' => $playerId
        ));
    }

    public function getVillageName($villageId)
    {
        return db::get_field('SELECT v.village_name FROM p_villages v WHERE v.id=:villageId', array(
            'villageId' => $villageId
        ));
    }

    public function createReport($fromPlayerId, $toPlayerId, $fromVillageId, $toVillageId, $reportCategory,
                                 $reportResult, $body, $timeInSeconds)
    {
        $fromPlayerId = intval($fromPlayerId);
        $toPlayerId = intval($toPlayerId);
        $fromVillageId = intval($fromVillageId);
        $toVillageId = intval($toVillageId);
        $fromPlayerName = $this->getPlayerName($fromPlayerId);
        $toPlayerName = $this->getPlayerName($toPlayerId);
        $fromVillageName = $this->getVillageName($fromVillageId);
        $toVillageName = $this->getVillageName($toVillageId);

        db::query('INSERT INTO p_rpts SET

				from_player_id=:fromPlayerId,
				from_player_name=:fromPlayerName,
				to_player_id=:toPlayerId,
				to_player_name=:toPlayerName,
				from_village_id=:fromVillageId,
				from_village_name=:fromVillageName,
				to_village_id=:toVillageId,
				to_village_name=:toVillageName,
				rpt_cat=:reportCategory,
				rpt_result=:reportResult,
				rpt_body=:body,
				creation_date=DATE_ADD(NOW(), INTERVAL :timeInSeconds SECOND),
				read_status=0,
				delete_status=0',
            array(
                'fromPlayerId' => $fromPlayerId,
                'fromPlayerName' => $fromPlayerName,
                'toPlayerId' => $toPlayerId,
                'toPlayerName' => $toPlayerName,
                'fromVillageId' => $fromVillageId,
                'fromVillageName' => $fromVillageName,
                'toVillageId' => $toVillageId,
                'toVillageName' => $toVillageName,
                'reportCategory' => $reportCategory,
                'reportResult' => $reportResult,
                'body' => $body,
                'timeInSeconds' => $timeInSeconds
            ));
        $reportId = intval(db::get_field('SELECT LAST_INSERT_ID() FROM p_rpts'));
        if ($reportCategory != 5) {
            db::query('UPDATE p_players p SET p.new_report_count=p.new_report_count+1 WHERE p.id=:fromPlayerId', array(
                'fromPlayerId' => $fromPlayerId
            ));
        }
        if ($fromPlayerId != $toPlayerId) {
            db::query('UPDATE p_players p SET p.new_report_count=p.new_report_count+1 WHERE p.id=:toPlayerId', array(
                'toPlayerId' => $toPlayerId
            ));
        }
        /*db::query("DELETE FROM p_rpts WHERE TIMESTAMPDIFF(SECOND, creation_date, NOW()) >= 172800");
        while (0 < $rid = db::get_field('SELECT MIN(r.id) id FROM p_rpts r
            WHERE r.delete_status!=1 AND r.to_player_id=:toPlayerId GROUP BY r.from_player_id HAVING COUNT(*)>:maxReportBoxSize', array(
                'toPlayerId'        => $toPlayerId,
                'maxReportBoxSize'  => $this->maxReportBoxSize
        )))
        {
            $this->deleteReport($toPlayerId, $rid);
        }
        if ($fromPlayerId != $toPlayerId)
        {
            while (0 < $rid = db::get_field('SELECT MIN(r.id) id FROM p_rpts r WHERE r.delete_status!=2 AND r.from_player_id=:fromPlayerId GROUP BY r.from_player_id HAVING COUNT(*)>:maxReportBoxSize', array(
                'fromPlayerId'      => $fromPlayerId,
                'maxReportBoxSize'  => $this->maxReportBoxSize
            )))
            {
                $this->deleteReport($fromPlayerId, $rid);
            }
        }*/
        return $reportId;
    }

    public function syncReports($playerId)
    {
        $newCount = intval(db::get_field('SELECT COUNT(*) FROM p_rpts r WHERE
				((r.to_player_id=:playerId AND r.delete_status!=1)
                OR (r.from_player_id=:playerId AND r.delete_status!=2 AND r.rpt_cat!=5))
				AND
				(IF(r.to_player_id=:playerId, r.read_status=1 OR r.read_status=3, r.read_status=2 OR r.read_status=3) = FALSE)',
            array('playerId' => $playerId)));

        if ($newCount < 0) {
            $newCount = 0;
        }

        db::query('UPDATE p_players p SET p.new_report_count=:newCount WHERE p.id=:playerId', array(
            'newCount' => $newCount,
            'playerId' => $playerId
        ));
        return $newCount;
    }

}

?>