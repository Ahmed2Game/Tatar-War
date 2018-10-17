<?php

class QueueTask
{

    public $playerId = NULL;
    public $villageId = NULL;
    public $toPlayerId = NULL;
    public $toVillageId = NULL;
    public $taskType = NULL;
    public $threads = NULL;
    public $executionTime = NULL;
    public $procParams = NULL;
    public $buildingId = NULL;
    public $tag = NULL;

    public function __construct( $params = array())
    {
        $this->threads = 1;
        $this->taskType = $params['taskType'];
        $this->playerId = $params['playerId'];
        $this->executionTime = $params['executionTime'];
    }

    public static function isCancelableTask( $taskType )
    {
        switch ( $taskType )
        {
        case QS_ACCOUNT_DELETE :
        case QS_BUILD_CREATEUPGRADE :
        case QS_BUILD_DROP :
        case QS_WAR_REINFORCE :
        case QS_WAR_ATTACK :
        case QS_WAR_ATTACK_PLUNDER :
        case QS_WAR_ATTACK_SPY :
        case QS_LEAVEOASIS :
            return TRUE;
        }
        return FALSE;
    }

    public static function getMaxCancelTimeout( $taskType )
    {
        switch ( $taskType )
        {
        case QS_ACCOUNT_DELETE :
            return 259200;
        case QS_WAR_REINFORCE :
        case QS_WAR_ATTACK :
        case QS_WAR_ATTACK_PLUNDER :
        case QS_WAR_ATTACK_SPY :
            return 86400;
        }
        return 0 - 1;
    }

}

// END
