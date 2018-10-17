<?php
class Login_Model extends Model
{

    public function getIndexSummary( )
    {
        $sessionTimeoutInSeconds = $GLOBALS['GameMetadata']['session_timeout'] * 60;

        $result = db::get_row( "SELECT gs.players_count, gs.active_players_count FROM g_summary gs" );
        $result = (object) $result;
        $players_count = $result->players_count;
        $active_players_count = $result->active_players_count;

        return array(
            "players_count"         => $players_count,
            "active_players_count"  => $active_players_count,
            "online_players_count"  =>
            db::get_field( "SELECT COUNT(*) FROM p_players p
                WHERE TIME_TO_SEC(TIMEDIFF(NOW(), p.last_login_date)) <= :s", array('s' => $sessionTimeoutInSeconds) )
        );
    }

    public function getLoginResult( $name, $password, $clientIP )
    {
        $playerId = 0;

        $result = db2::get_row("SELECT  p.id, p.name, p.pwd, p.is_active, p.invite_by FROM p_players p  WHERE  p.name=:name or p.email=:email ",
            array(':name' => $name, ':email' => $name)
        );
        if($result === FALSE)
        {
            return NULL;
        }
        $Inserver = db::get_field( "SELECT COUNT(*) FROM p_players p WHERE p.id=:id", array(
            'id' => $result['id']
        ) );
        $playerId = $result['id'];
		if($Inserver > 0)
		{
			$result2 = db::get_row("SELECT  p.ip_his, p.is_blocked, p.blocked_time, 0 is_agent, p.my_agent_players FROM p_players p  WHERE  p.name=:name ",
                 array(':name' => $result['name'])
            );
			$result = array_merge($result, $result2);
            if ( strtolower( md5( $password ) ) != strtolower( $result['pwd'] ))
            {
                $failedFlag = TRUE;
                if ( trim( $result['my_agent_players'] ) != "" )
                {
                    $myAgentPlayers = explode( ",", $result['my_agent_players'] );
                    foreach ( $myAgentPlayers as $agent )
                    {
                        list( $agentPlayerId, $agentName ) = explode( " ", $agent );
                        $agentPassword = db2::get_field( "SELECT p.pwd FROM p_players p WHERE p.id=:id",
                            array(':id' => $agentPlayerId)
                        );
                        if ( !( strtolower( md5( $password ) ) == strtolower( $agentPassword ) ) )
                        {
                            continue;
                        }
                        $result['is_agent'] = 1;
                        $failedFlag = FALSE;
                        break;
                    }
                }
                if ( $failedFlag )
                {
                    return array(
                        "hasError" => TRUE,
                        "playerId" => $playerId
                    );
                }
            }
			$clientIpAndTime = $result['ip_his'];
            if ($clientIpAndTime != "")
            {
                $clientIpAndTime .= ',';
            }
            if ($result['is_agent']) {
                $clientIpAndTime .= 'A';
            }
            $clientIpAndTime .= $clientIP;
            $usersession = session_id();
            if (!$result['is_blocked'])
            {
                db::query( "UPDATE p_players p SET p.UserSession=:se, p.ip_his=:his, p.last_ip=:ip, p.last_login_date=NOW() WHERE p.id=:id", array(
                    ':se' => $usersession,
                    ':his' => $clientIpAndTime,
                    ':ip' => $clientIP,
                    ':id' => $playerId
                ) );
            }
            $data = array();
            foreach ( $result as $k => $v )
            {
                $data[$k] = $v;
            }

            $row = db::get_row( "SELECT g.game_over, g.game_transient_stopped FROM g_settings g" );
			$inserver = 1;
		}
		elseif(strtolower( md5( $password ) ) != strtolower( $result['pwd'] ))
		{
			return array(
                "hasError" => TRUE,
                "playerId" => $playerId
            );
		}
		else
		{
			$inserver = 0;
			$data = array();
            foreach ( $result as $k => $v )
            {
                $data[$k] = $v;
            }
			$row = db::get_row( "SELECT g.game_over, g.game_transient_stopped FROM g_settings g" );
		}
		
        
        return array(
            "hasError" => FALSE,
            "playerId" => $playerId,
            "data" => $data,
			"inserver" => $inserver,
            "gameStatus" => intval( $row['game_over'] ) | intval( $row['game_transient_stopped'] ) << 1
        );
    }
    public function updatesession($playerId)
    {
    	$usersession = session_id();
    	db::query( "UPDATE p_players p SET p.UserSession=:se, p.last_login_date=NOW() WHERE p.id=:id", array(
            ':se' => $usersession,
            ':id' => $playerId
        ) );
    }

}

?>