<?php
/**
 * Cropfinder class file.
 *
 * @author Abdulrahman Mohamed <abdokamal15@gmail.com>
 * @copyright Copyright 2013
 * @license http://www.xtatar.com
 */

/**
 * user Cropfinder page
 *
 * @author Abdulrahman Mohamed <abdokamal15@gmail.com>
 * @version $Id$
 * @package app.controllers
 * @since 1.0
 */
load_game_engine('Auth');
class Cropfinder_Controller extends AuthController
{
    var $cropData = array();

    public function __construct()
    {
        parent::__construct();
        $this->viewFile                    = 'cropfinder';
        $this->viewData['contentCssClass'] = 'cropfinder';
    }

    public function index()
    {
        if (!$this->data['active_plus_account'])
        {
            $this->is_redirect = TRUE;
            redirect('plus?t=2');
        }
        $this->viewData['rely'] = $this->data['rel_y'];
        $this->viewData['relx'] = $this->data['rel_x'];
        if (is_post('type'))
        {
            $this->load_model('Cropfinder', 'm');
            $villages1 = $this->m->getVillagesData(post('type'));
            $mxp = is_numeric(post('mxp')) ? post('mxp') : 0;
            $myp = is_numeric(post('myp')) ? post('myp') : 0;
            if ($villages1 != null)
            {
                foreach ($villages1 as $value)
                {
                    $Distance                  = $this->getDistance($mxp, $myp, $value['rel_x'], $value['rel_y']);
                    $this->cropData[$Distance] = $value;
                }
                ksort($this->cropData);
                $cropdata = array();
                $i        = 0;
                foreach ($this->cropData as $dist => $row)
                {
                    if ((is_post('only_free') and !$row['player_name']) || !is_post('only_free'))
                    {
                        ++$i;
                        $cropdata[$dist] = array(
                            'player_name' => $row['player_name'],
                            'id' => $row['id'],
                            'rel_x' => $row['rel_x'],
                            'rel_y' => $row['rel_y'],
                            'field_maps' => $row['field_maps_id']
                        );
                    }
                    if ($i >= 20)
                    {
                        break;
                    }
                }
                //viewData
                $this->viewData['cropdata'] = $cropdata;
                $this->viewData['myp']      = post('myp');
                $this->viewData['mxp']      = post('mxp');
                unset($villages1);
                unset($this->cropData);
            }
        }
    }
    function getDistance($dx, $dy, $rx, $ry)
    {
        $speed    = $this->gameMetadata['troops'][10]['velocity'];
        $factor   = $this->gameMetadata['game_speed'];
        $speed    = $speed * $factor;
        $distance = getdistance($dx, $dy, $rx, $ry, $this->setupMetadata['map_size'] / 2);
        $dis      = intval($distance / $speed * 60);
        return round($dis, 3);
    }

}
?>