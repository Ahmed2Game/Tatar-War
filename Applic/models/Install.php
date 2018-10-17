<?php
class Install_Model extends Model
{

    public function processSetup( $map_size )
    {
    	global $gameConfig;
        global $serv;
        $this->restserver($serv);
	    try {
	        $result = db::query("SELECT 1 FROM p_players LIMIT 1");
	    } catch (Exception $e) {
	        // We got an exception == table not found
	        return FALSE;
	    }


        $this->_createTables();
        $this->_createMap( $map_size );
        
        if ( $this->_createAdminPlayer( $map_size, $gameConfig['system']['admin_email'] ) )
        {
            $raiseTime = ($gameConfig['settings']['over']*24*60*60);
            $Crop = $gameConfig['settings']['Crop']*60;
            $Artefacts = ($gameConfig['settings']['Artefacts']*24*60*60);
            $this->load_model('Queue', 'queueModel');
            $this->load_library('QueueTask', 'newTask',
                array(  'taskType'      => QS_TATAR_RAISE,
                        'playerId'      => 0,
                        'executionTime' =>  $raiseTime
                    )
            );
            $this->queueModel->addTask($this->newTask);
            $this->load_library('QueueTask', 'newTask',
                array(  'taskType'      => QS_CROP_DELETE,
                        'playerId'      => 0,
                        'executionTime' =>  $Crop
                    )
            );
            $this->queueModel->addTask($this->newTask);
            $this->load_library('QueueTask', 'newTask',
                array(  'taskType'      => QS_ARTEFACTS_RAISE,
                        'playerId'      => 0,
                        'executionTime' =>  $Artefacts
                    )
            );
            $this->queueModel->addTask($this->newTask);
            
        }
    }
    
    public function restserver($serv)
    {
        db2::query("UPDATE servers SET players_count=1, start_date=NOW() WHERE id=:id", array(
            'id' => $serv
        ));
    }


    public function _createTables()
    {
        db::query( "
		DROP TABLE IF EXISTS `g_settings`;
		DROP TABLE IF EXISTS `g_summary`;
		DROP TABLE IF EXISTS `money_log`;
		DROP TABLE IF EXISTS `gold_trans`;
		DROP TABLE IF EXISTS `money_total`;
		DROP TABLE IF EXISTS `p_alliances`;
		DROP TABLE IF EXISTS `p_merchants`;
		DROP TABLE IF EXISTS `p_msgs`;
		DROP TABLE IF EXISTS `p_players`;
		DROP TABLE IF EXISTS `p_queue`;
		DROP TABLE IF EXISTS `p_rpts`;
		DROP TABLE IF EXISTS `p_villages`;
		DROP TABLE IF EXISTS `g_chat`;
		DROP TABLE IF EXISTS `artefacts`;
        DROP TABLE IF EXISTS `p_farm`;
        DROP TABLE IF EXISTS `support_tickets`;
        DROP TABLE IF EXISTS `support_tickets_replaies`;

        CREATE TABLE IF NOT EXISTS `artefacts` (
        `id` int(3) NOT NULL AUTO_INCREMENT,
        `in_village_id` int(6) NOT NULL,
        `player_id` int(5) NOT NULL,
        `type` int(2) NOT NULL,
        `size` int(2) NOT NULL,
        `conquered` datetime NOT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

        CREATE TABLE `p_farm` (
        `id` int(5) NOT NULL AUTO_INCREMENT,
        `player_id` int(5) DEFAULT NULL,
        `from_village_id` int(6) DEFAULT NULL,
        `to_village_id` int(6) DEFAULT NULL,
        `troops` text,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

		CREATE TABLE `g_settings` (
		`start_date` datetime DEFAULT NULL,
		`license_key` varchar(50) DEFAULT NULL,
		`game_over` tinyint(1) DEFAULT '0',
		`game_transient_stopped` tinyint(1) DEFAULT '0',
		`cur_week` smallint(6) DEFAULT '0',
		`win_pid` bigint(20) DEFAULT '0',
		`qlocked_date` datetime DEFAULT NULL,
		`qlocked` tinyint(1) DEFAULT '0')
		ENGINE=InnoDB DEFAULT CHARSET=utf8;

		CREATE TABLE `g_summary` (
		`players_count` int(5) DEFAULT '0',
		`active_players_count` int(5) DEFAULT '0',
		`Arab_players_count` int(5) DEFAULT '0',
		`Roman_players_count` int(5) DEFAULT '0',
		`Teutonic_players_count` int(5) DEFAULT '0',
		`Gallic_players_count` int(5) DEFAULT '0',
        `truce_time` datetime DEFAULT NULL,
		`truce_reason` text, 
		`news_text` text,  
		`gnews_text` text
		)
		ENGINE=InnoDB DEFAULT CHARSET=utf8;

		CREATE TABLE `p_alliances` (
		`id` int(5) NOT NULL AUTO_INCREMENT,
		`name` varchar(25) NOT NULL DEFAULT '',
		`name2` varchar(25) DEFAULT NULL,
		`creator_player_id` int(5) DEFAULT NULL,
		`rating` int(11) DEFAULT NULL,
		`creation_date` datetime DEFAULT NULL,
		`contracts_alliance_id` text,
		`player_count` tinyint(2) DEFAULT NULL,
		`max_player_count` tinyint(2) DEFAULT '1',
		`players_ids` text,
		`invites_player_ids` text,
		`description1` text,
		`description2` text,
		`medals` varchar(300) DEFAULT NULL,
		`attack_points` bigint(20) DEFAULT '0',
		`defense_points` bigint(20) DEFAULT '0',
		`week_attack_points` bigint(20) DEFAULT '0',
		`week_defense_points` bigint(20) DEFAULT '0',
		`week_dev_points` bigint(20) DEFAULT '0',
		`week_thief_points` bigint(20) DEFAULT '0',
		PRIMARY KEY (`id`),
		KEY `NewIndex1` (`name`),
		KEY `NewIndex2` (`rating`),
		KEY `NewIndex3` (`attack_points`),
		KEY `NewIndex4` (`defense_points`),
		KEY `NewIndex5` (`week_attack_points`),
		KEY `NewIndex6` (`week_defense_points`),
		KEY `NewIndex7` (`week_dev_points`),
		KEY `NewIndex8` (`week_thief_points`))
		ENGINE=InnoDB DEFAULT CHARSET=utf8;

		CREATE TABLE `p_players` (
		`id` int(5) NOT NULL DEFAULT '0',
		`tribe_id` tinyint(1) DEFAULT NULL,
		`alliance_id` int(5) DEFAULT NULL,
		`alliance_name` varchar(25) DEFAULT NULL,
		`alliance_roles` text,
		`invites_alliance_ids` text,
		`name` varchar(15) DEFAULT NULL,
		`invite_by` int(5) DEFAULT NULL ,
		`is_blocked` tinyint(1) DEFAULT '0',
        `blocked_time` datetime DEFAULT NULL,
        `blocked_reason` text,
        `protection` varchar(20) DEFAULT '0,0',
        `holiday` varchar(20) DEFAULT '0,0',
        `UserSession` varchar(50) DEFAULT NULL,
		`player_type` tinyint(1) DEFAULT '0',
		`active_plus_account` tinyint(1) DEFAULT '0',
		`last_login_date` datetime DEFAULT NULL,
        `block_player_id` text,
		`ip_his` text,
        `last_ip` varchar(20) DEFAULT NULL,
		`description1` text,  `description2` text,
		`registration_date` datetime DEFAULT NULL,
		`show_ref` int(11) DEFAULT '0',
		`agent_for_players` varchar(50) DEFAULT NULL,
		`my_agent_players` varchar(50) DEFAULT NULL,
		`custom_links` text,
		`medals` varchar(300) DEFAULT NULL,
		`total_people_count` int(5) DEFAULT '2',
		`selected_village_id` int(6) DEFAULT NULL,
		`villages_count` tinyint(3) DEFAULT '1',
		`villages_id` text,  `villages_data` text,
		`friend_players` text,  `notes` text,
		`hero_troop_id` tinyint(3) DEFAULT NULL,
		`hero_level` SMALLINT(6) DEFAULT '0',
		`hero_points` int(11) DEFAULT '0',
		`hero_name` varchar(15) DEFAULT NULL,
		`hero_in_village_id` int(6) DEFAULT NULL,
		`attack_points` bigint(20) DEFAULT '0',
		`defense_points` bigint(20) DEFAULT '0',
		`week_attack_points` bigint(20) DEFAULT '0',
		`week_defense_points` bigint(20) DEFAULT '0',
		`week_dev_points` bigint(20) DEFAULT '0',
		`week_thief_points` bigint(20) DEFAULT '0',
		`new_report_count` smallint(6) DEFAULT '0',
		`new_mail_count` smallint(6) DEFAULT '0',
		`guide_quiz` varchar(50) DEFAULT NULL,
		`new_gnews` tinyint(1) DEFAULT '0',
		`create_nvil` tinyint(1) DEFAULT '0',
		UNIQUE KEY `NewIndex1` (`id`),
		UNIQUE KEY `NewIndex2` (`name`),
		KEY `NewIndex3` (`attack_points`),
		KEY `NewIndex6` (`defense_points`),
		KEY `NewIndex5` (`last_login_date`),
		KEY `NewIndex7` (`week_attack_points`),
		KEY `NewIndex8` (`week_defense_points`),
		KEY `NewIndex9` (`week_dev_points`),
		KEY `NewIndex10` (`week_thief_points`))
		ENGINE=InnoDB DEFAULT CHARSET=utf8;

		
		CREATE TABLE `p_villages` (
		`id` int(6) NOT NULL AUTO_INCREMENT,
		`rel_x` smallint(6) DEFAULT NULL,
		`rel_y` smallint(7) DEFAULT NULL,
		`field_maps_id` tinyint(4) DEFAULT NULL,
		`rand_num` int(11) DEFAULT NULL,
		`image_num` tinyint(4) DEFAULT NULL,
		`parent_id` int(6) DEFAULT NULL,
		`tribe_id` tinyint(1) DEFAULT NULL,
		`player_id` int(5) DEFAULT NULL,
		`alliance_id` int(5) DEFAULT NULL,
		`player_name` varchar(15) DEFAULT NULL,
		`village_name` varchar(35) DEFAULT NULL,
		`alliance_name` varchar(25) DEFAULT NULL,
		`is_capital` tinyint(1) DEFAULT '0',
		`is_special_village` tinyint(1) DEFAULT '0',
		`is_oasis` tinyint(1) DEFAULT NULL,
		`people_count` int(11) DEFAULT '2',
		`crop_consumption` BIGINT(16) DEFAULT '2',
		`time_consume_percent` float DEFAULT '100',
		`offer_merchants_count` tinyint(4) DEFAULT '0',
		`resources` text,
		`cp` varchar(300) DEFAULT NULL,
		`buildings` text,
		`troops_training` text,
		`troops_num` text,
		`troops_out_num` text,
		`troops_intrap_num` text,
		`troops_out_intrap_num` text,
		`troops_trapped_num` int(11) DEFAULT '0',
		`allegiance_percent` int(11) DEFAULT '100',
		`child_villages_id` text,
		`village_oases_id` text,
		`creation_date` datetime DEFAULT NULL,
		`update_key` varchar(5) DEFAULT NULL,
		`last_update_date` datetime DEFAULT NULL,
		PRIMARY KEY (`id`),
		KEY `NewIndex2` (`player_id`),
		KEY `NewIndex3` (`is_special_village`),
		KEY `NewIndex4` (`is_oasis`),
		KEY `NewIndex5` (`people_count`),
		KEY `NewIndex1` (`village_name`),
		KEY `NewIndex6` (`player_id`,`is_oasis`))
		ENGINE=InnoDB DEFAULT CHARSET=utf8;

		CREATE TABLE `p_queue` (
		`id` bigint(20) NOT NULL AUTO_INCREMENT,
		`player_id` int(5) NOT NULL DEFAULT '0',
		`village_id` int(6) DEFAULT NULL,
		`to_player_id` int(5) DEFAULT NULL,
		`to_village_id` int(6) DEFAULT NULL,
		`proc_type` tinyint(4) DEFAULT NULL,
		`building_id` int(2) DEFAULT NULL,
		`proc_params` text,
		`threads` BIGINT(25) DEFAULT '1',
		`end_date` datetime DEFAULT NULL,
		`execution_time` int(11) DEFAULT NULL,
		PRIMARY KEY (`id`),
		KEY `NewIndex1` (`player_id`),
		KEY `NewIndex2` (`village_id`),
		KEY `NewIndex3` (`to_player_id`),
		KEY `NewIndex4` (`to_village_id`),
		KEY `NewIndex5` (`end_date`))
		ENGINE=InnoDB DEFAULT CHARSET=utf8;

		CREATE TABLE `p_msgs` (
		`id` int(6) NOT NULL AUTO_INCREMENT,
		`from_player_id` int(5) DEFAULT NULL,
		`to_player_id` int(5) DEFAULT NULL,
		`from_player_name` varchar(15) DEFAULT NULL,
		`to_player_name` varchar(15) DEFAULT NULL,
		`msg_title` varchar(80) DEFAULT NULL,
		`msg_body` text,
		`creation_date` datetime DEFAULT NULL,
		`is_readed` tinyint(1) DEFAULT '0',
		`delete_status` tinyint(2) DEFAULT '0',
		PRIMARY KEY (`id`),
		KEY `NewIndex1` (`from_player_id`),
		KEY `NewIndex2` (`to_player_id`))
		ENGINE=InnoDB DEFAULT CHARSET=utf8;

		CREATE TABLE `p_merchants` (
		`id` int(6) NOT NULL AUTO_INCREMENT,
		`player_id` int(6) DEFAULT NULL,
		`player_name` varchar(15) DEFAULT NULL,
		`village_id` int(6) DEFAULT NULL,
		`village_x` smallint(6) DEFAULT NULL,
		`village_y` smallint(6) DEFAULT NULL,
		`offer` varchar(300) DEFAULT NULL,
		`merchants_num` tinyint(2) DEFAULT NULL,
		`merchants_speed` smallint(6) DEFAULT NULL,
		`alliance_only` tinyint(1) DEFAULT NULL,
		`max_time` tinyint(4) DEFAULT NULL,
		PRIMARY KEY (`id`),
		KEY `NewIndex1` (`player_id`),
		KEY `village_x` (`village_x`),
		KEY `village_y` (`village_y`))
		ENGINE=InnoDB DEFAULT CHARSET=utf8;

		CREATE TABLE `p_rpts` (
		`id` int(6) NOT NULL AUTO_INCREMENT,
		`from_player_id` int(5) DEFAULT NULL,
		`from_player_name` varchar(15) DEFAULT NULL,
		`from_village_id` int(6) DEFAULT NULL,
		`from_village_name` varchar(35) DEFAULT NULL,
		`to_player_id` int(5) DEFAULT NULL,
		`to_player_name` varchar(15) DEFAULT NULL,
		`to_village_id` int(6) DEFAULT NULL,
		`to_village_name` varchar(35) DEFAULT NULL,
		`rpt_body` text,
		`creation_date` datetime DEFAULT NULL,
		`read_status` tinyint(2) DEFAULT '0',
		`delete_status` tinyint(2) DEFAULT '0',
		`rpt_cat` tinyint(4) DEFAULT NULL,
		`rpt_result` tinyint(4) DEFAULT '0',
		PRIMARY KEY (`id`),
		KEY `NewIndex1` (`from_player_id`),
		KEY `NewIndex2` (`to_player_id`),
		KEY `NewIndex3` (`rpt_cat`))
		ENGINE=InnoDB DEFAULT CHARSET=utf8;

		INSERT INTO `g_settings`(`start_date`,`license_key`) VALUES (NOW(),NULL);
		INSERT INTO `g_summary`(`players_count`,`active_players_count`,`Arab_players_count`,`Roman_players_count`,`Teutonic_players_count`,`Gallic_players_count`,`news_text`) VALUES ( '0','0','0','0','0','0',NULL);" );

  }

    public function _createMap( $map_size )
    {
        $maphalf_size = floor( $map_size / 2 );
        $oasis_troop_ids = array( );
        foreach ( $GLOBALS['GameMetadata']['troops'] as $k => $v )
        {
            if ( $v['for_tribe_id'] == 4 )
            {
                $oasis_troop_ids[] = $k;
            }
        }
        $i = 0;
        while ( $i < $map_size )
        {
            $queryBatch = array( );
            $j = 0;
            while ( $j < $map_size )
            {
                $rel_x = $maphalf_size < $i ? $i - $map_size : $i;
                $rel_y = $maphalf_size < $j ? $j - $map_size : $j;
                $troops_num = "";
                $field_maps_id = 0;
                $rand_num = "NULL";
                $creation_date = "NULL";
                if ( $rel_x == 0 && $rel_y == 0 )
                {
                    $r = 1;
                }
                else
                {
                    $r_arr = array(0,1,1,1,1,1,0,1,mt_rand( 0, 1 ),mt_rand( 0, 1 ),1,1,1,1,1,1,1,0,1,1,1,1,1,1,1,0,1,1,1,1,1,1,0,1,1,1,1,1,0,1,1,1,1,1,1,0,1,1,mt_rand( 0, 1 ));
                    $r = $r_arr[mt_rand( 0, 48 )];
                }
                if ( $r == 1 )
                {
                    $image_num = mt_rand( 0, 9 );
                    $is_oasis = 0;
                    $tribe_id = 0;
                    if ( $rel_x == 0 && $rel_y == 0 )
                    {
                        $field_maps_id = 3;
                    }
                    else
                    {
                        $fr_arr = array(
                            3,
                            mt_rand( 1, 12 ),
                            3,
                            mt_rand( 1, 4 ),
                            mt_rand( 1, 5 ),
                            3,
                            mt_rand( 1, 12 ),
                            3,
                            mt_rand( 7, 11 ),
                            mt_rand( 7, 12 ),
                            3,
                            3,
                            mt_rand( 1, 12 )
                        );
                        $field_maps_id = $fr_arr[mt_rand( 0, 12 )];
                    }
                    if ( $field_maps_id == 3 )
                    {
                        $pr_arr = array(
                            0,
                            1,
                            0,
                            0,
                            mt_rand( 0, 1 )
                        );
                        $pr = $pr_arr[mt_rand( 0, 4 )];
                        $rand_num = $pr == 1 ? abs( $rel_x ) + abs( $rel_y ) : 310;
                    }
                }
                else
                {
                    $image_num = mt_rand( 1, 12 );
                    $is_oasis = 1;
                    $tribe_id = 4;
                    $creation_date = "NOW()";
                    $troops_num = $oasis_troop_ids[mt_rand( 0, 2 )]." ".mt_rand( 1, 5 );
                    $troops_num .= ",".$oasis_troop_ids[mt_rand( 3, 5 )]." ".mt_rand( 2, 6 );
                    $troops_num .= ",".$oasis_troop_ids[mt_rand( 6, 8 )]." ".mt_rand( 3, 7 );
                    if ( mt_rand( 0, 1 ) == 1 )
                    {
                        $troops_num .= ",".$oasis_troop_ids[9]." ".mt_rand( 2, 8 );
                    }
                    $troops_num = "-1:".$troops_num;
                }
                $queryBatch[] = "(".$rel_x.",".$rel_y.",".$image_num.",".$rand_num.",".$field_maps_id.",".$tribe_id.",".$is_oasis.",'".$troops_num."',".$creation_date.")";
                ++$j;
            }
            db::query( "INSERT INTO p_villages (rel_x,rel_y,image_num,rand_num,field_maps_id,tribe_id,is_oasis,troops_num,creation_date) VALUES".implode( ",", $queryBatch ) );
            unset( $queryBatch );
            $queryBatch = NULL;
            ++$i;
        }
    }


    public function _createAdminPlayer( $map_size, $adminEmail )
    {
    	global $gameConfig;

        $this->load_model('Register', 'm');
		$admin = db2::get_row("SELECT  p.id, p.name FROM p_players p  WHERE  p.name=:name",
            array('name' => $gameConfig['system']['adminName'])
        );
		if($admin === FALSE)
		{
			$result = $this->m->createMaster( $gameConfig['system']['adminName'], $adminEmail, $gameConfig['system']['adminPassword'], 0, 1);
			$playerId = $result['result'] > 0 ? db2::get_field( "SELECT LAST_INSERT_ID() FROM p_players" ) : 1;
			$result = $this->m->createNewPlayer( $playerId, $gameConfig['system']['adminName'], 7, 1, $gameConfig['system']['adminName'], $map_size, PLAYERTYPE_ADMIN, 1, get_ip());
		}
		else
		{
			$result = $this->m->createNewPlayer( $admin['id'], $admin['name'], 7, 0, $admin['name'], $map_size, PLAYERTYPE_ADMIN, 1, get_ip());
		}

        $cstorge = 1200*$gameConfig['settings']['capacity'];
        $mstorge = 1500*$gameConfig['settings']['capacity'];
        $poasis = 8*$GLOBALS['GameMetadata']['game_speed'];
        $pplus = 25;

        $resources_osias = "1 ".$cstorge." ".$mstorge." ".$mstorge." ".$poasis." ".$pplus.",2 ".$cstorge." ".$mstorge." ".$mstorge." ".$poasis." ".$pplus.",3 ".$cstorge." ".$mstorge." ".$mstorge." ".$poasis." ".$pplus.",4 ".$cstorge." ".$mstorge." ".$mstorge." ".$poasis." ".$pplus;

        db::query(" UPDATE p_villages set resources='$resources_osias', cp='0 2' where is_oasis=1 ");

        if ( $result['hasErrors'] )
        {
            return FALSE;
        }
        
        return TRUE;
    }

}

?>