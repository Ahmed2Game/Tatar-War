<?php
/**
 * Alliancerole class file.
 *
 * @author Abdulrahman Mohamed <abdokamal15@gmail.com>
 * @copyright Copyright 2013
 * @license http://www.xtatar.com
 */

/**
 * Players Alliancerole
 *
 * @author Abdulrahman Mohamed <abdokamal15@gmail.com>
 * @version $Id$
 * @package app.controllers
 * @since 1.0
 */
load_game_engine('Auth');
class Alliancerole_Controller extends AuthController
{
    public $allianceData = null;
    public $playerName = null;
    public $playerRoles = null;
    
    /**
     * Constructor Method
     * This method defines view file && layoutViewFile and check player login .
    */
    public function __construct()
    {
        parent::__construct();
        if ($this->player == NULL)
        {
            exit(0);
        }
        $this->viewFile              = 'alliancerole';
        $this->layoutViewFile        = "layout/popup";
    }


    /**
     * Index Method 
     *
     * @return void
    */
    public function index()
    {
        $allianceId = intval($this->data['alliance_id']);
        if ((($allianceId == 0 || !$this->hasAllianceSetRoles()) || !is_get('uid')))
        {
            exit(0);
            return null;
        }
        $uid                = intval(get('uid'));
        $this->load_model('Alliance', 'm');
        $this->allianceData = $this->m->getAllianceData($allianceId);
        if (!$this->isMemberOfAlliance($uid))
        {
            exit(0);
            return null;
        }
        if (is_post('a_titel'))
        {
            $roleName = (is_post('a_titel') ? strip_tags(post('a_titel')) : '');
            if (trim($roleName) == '')
            {
                $roleName = '.';
            }
            $roleNumber = 0;
            if (is_post('e1'))
            {
                $roleNumber |= ALLIANCE_ROLE_SETROLES;
            }
            if (is_post('e2'))
            {
                $roleNumber |= ALLIANCE_ROLE_REMOVEPLAYER;
            }
            if (is_post('e3'))
            {
                $roleNumber |= ALLIANCE_ROLE_EDITNAMES;
            }
            if (is_post('e4'))
            {
                $roleNumber |= ALLIANCE_ROLE_EDITCONTRACTS;
            }
            if (is_post('e5'))
            {
                $roleNumber |= ALLIANCE_ROLE_SENDMESSAGE;
            }
            if (is_post('e6'))
            {
                $roleNumber |= ALLIANCE_ROLE_INVITEPLAYERS;
            }
            $this->m->setPlayerAllianceRole($uid, $roleName, $roleNumber);
        }
        $row = $this->m->getPlayerAllianceRole($uid);
        if ($row == NULL)
        {
            exit(0);
            return null;
        }
        $this->playerName = $row['name'];
        $alliance_roles   = trim($row['alliance_roles']);
        if ($alliance_roles == '')
        {
            $this->playerRoles = array(
                'name' => '',
                'roles' => 0
            );
        }
        else
        {
            list($rolesNumber, $roleName) = explode(' ', $alliance_roles, 2);
            $this->playerRoles = array(
                'name' => ($roleName == '.' ? '' : $roleName),
                'roles' => $rolesNumber
            );
        }

		############View###########
		$this->viewData['playerName'] = $this->playerName;
		$this->viewData['playerRoles'] = $this->playerRoles;
		$this->viewData['e1'] = $this->getAllianceRoleCheckValue(ALLIANCE_ROLE_SETROLES);
		$this->viewData['e2'] = $this->getAllianceRoleCheckValue(ALLIANCE_ROLE_REMOVEPLAYER);
		$this->viewData['e3'] = $this->getAllianceRoleCheckValue(ALLIANCE_ROLE_EDITNAMES);
		$this->viewData['e4'] = $this->getAllianceRoleCheckValue(ALLIANCE_ROLE_EDITCONTRACTS);
		$this->viewData['e5'] = $this->getAllianceRoleCheckValue(ALLIANCE_ROLE_SENDMESSAGE);
		$this->viewData['e6'] = $this->getAllianceRoleCheckValue(ALLIANCE_ROLE_INVITEPLAYERS);

    }

    /**
     * isMemberOfAlliance Method 
     *
     * Get Alliance member exists
     *
     * @param playerId int
     * @return bool
    */
    public function isMemberOfAlliance($playerId)
    {
        $players_ids = trim($this->allianceData['players_ids']);
        if ($players_ids == '')
        {
            return FALSE;
        }
        $arr = explode(',', $players_ids);
        foreach ($arr as $pid)
        {
            if ($pid == $playerId)
            {
                return TRUE;
            }
        }
        return FALSE;
    }


    /**
     * _hasAllianceRole Method 
     *
     * Get Alliance roleNumber and roleName
     *
     * @param role string
     * @return void
    */
    public function _hasAllianceRole($role)
    {
        $alliance_roles = trim($this->data['alliance_roles']);
        if ($alliance_roles == '')
        {
            return FALSE;
        }
        list($roleNumber, $roleName) = explode(' ', $alliance_roles, 2);
        return $roleNumber & $role;
    }


    /**
     * hasAllianceSetRoles Method
     *
     * @return bool
    */
    public function hasAllianceSetRoles()
    {
        return $this->_hasAllianceRole(ALLIANCE_ROLE_SETROLES);
    }


    /**
     * _hasAllianceRole Method 
     *
     * @param role string
     * @return string
    */
    public function getAllianceRoleCheckValue($role)
    {
        return ($this->playerRoles['roles'] & $role ? 'value="1" checked="checked"' : 'value="0"');
    }

}
//end file
?>