<?php
require_once LIBRARY_DIR . 'gameEngine/AuthController.php';

class Map_Controller extends AuthController
{
    public $x = null;
    public $y = null;
    public $directionsMatrix = null;
    public $stepDirectionsMatrix = null;
    public $rad = null;
    public $matrixSet = array();
    public $contractsAllianceId = array();
    public $json = null;
    public $largeMap = FALSE;


    public function __construct()
    {
        parent::__construct();
        $this->viewData['contentCssClass'] = 'map';
    }

    public function index()
    {
        if ((is_get('l') && !$this->data['active_plus_account'])) {
            exit(0);
            return null;
        }

        $this->largeMap = (is_get('l') && $this->data['active_plus_account']);
        $this->viewFile = ($this->largeMap) ? 'map2' : 'map';
        if ($this->largeMap) {
            $this->layoutViewFile = 'layout/popup';
        }
        $this->rad = ($this->largeMap ? 6 : 3);
        $map_size = $this->setupMetadata['map_size'];
        $this->x = $this->data['rel_x'];
        $this->y = $this->data['rel_y'];

        $this->load_model('Map', 'm');
        $this->contractsAllianceId = array();
        if (0 < intval($this->data['alliance_id'])) {
            $cont = trim($this->m->getContractsAllianceId($this->data['alliance_id']));
            if ($cont != '') {
                $_arr = explode(',', $cont);
                foreach ($_arr as $contractAllianceId) {
                    list($aid, $pendingStatus) = explode(' ', $contractAllianceId);
                    $this->contractsAllianceId[$aid] = $pendingStatus;
                }
            }
        }
        $_x = $this->data['rel_x'];
        $_y = $this->data['rel_y'];
        if (is_post('mxp')) {
            $_x = intval($_POST['mxp']);
            $_y = intval($_POST['myp']);
        } else {
            if ((is_get('id') && is_numeric(get('id')))) {
                $m_vid = intval(get('id'));
                if ($m_vid < 1) {
                    $m_vid = 1;
                }
                $_x = floor(($m_vid - 1) / $map_size);
                $_y = $m_vid - ($_x * $map_size + 1);
            }
        }
        $map_matrix = $this->__getVillageMatrix($map_size, $_x, $_y, $this->rad);
        $map_matrix_arr = explode('|', $map_matrix);
        $matrixStr = $map_matrix_arr[0];
        $matrixStrArray = explode(',', $matrixStr);
        $this->directionsMatrix = explode(',', $map_matrix_arr[1]);
        $result = $this->m->getVillagesMatrix($matrixStr);

        foreach ($result as $value) {
            $this->matrixSet[$value['id']] = array(
                'vid' => $value['id'],
                'x' => $value['rel_x'],
                'y' => $value['rel_y'],
                'image_num' => $value['image_num'],
                'player_id' => $value['player_id'],
                'tribe_id' => $value['tribe_id'],
                'alliance_id' => $value['alliance_id'],
                'player_name' => $value['player_name'],
                'village_name' => $value['village_name'],
                'alliance_name' => $value['alliance_name'],
                'people_count' => $value['people_count'],
                'is_oasis' => $value['is_oasis'],
                'field_maps_id' => $value['field_maps_id']
            );
        }
        unset($result);

        $i = 0;
        $this->json = '';
        $sjson = '';
        $sortedArray = array();
        foreach ($matrixStrArray as $vid) {
            $mapItem = isset($this->matrixSet[$vid]) ? $this->matrixSet[$vid] : NULL;
            $sortedArray[] = $mapItem;
            if ($sjson != '') {
                $sjson .= ',';
            }
            $sjson .= sprintf('[%s,%s,%s,"%s","%s",%s,%s', $mapItem['vid'], $mapItem['x'], $mapItem['y'], $this->getCssClassNameByItem($mapItem), $this->getMapAreaTitle($mapItem), ($mapItem['player_id'] != '' ? 1 : 0), $mapItem['is_oasis']);
            if ($mapItem['player_id'] != '') {
                $sjson .= sprintf(',%s,%s,"%s","%s","%s"', $mapItem['tribe_id'], $mapItem['people_count'], htmlspecialchars(str_replace('\\', '\\', $mapItem['player_name'])), htmlspecialchars(str_replace('\\', '\\', $mapItem['village_name'])), htmlspecialchars(str_replace('\\', '\\', $mapItem['alliance_name'])));
            } else {
                if (!$mapItem['is_oasis']) {
                    $sjson .= ',' . $mapItem['field_maps_id'];
                }
            }
            $sjson .= ']';
            if (++$i % ($this->rad * 2 + 1) == 0) {
                if ($this->json != '') {
                    $this->json .= ',';
                }
                $this->json .= '[' . $sjson . ']';
                $sjson = '';
            }
        }
        $this->json = '[' . $this->json . ']';
        $this->matrixSet = $sortedArray;
        $centerIndex = 2 * ($this->rad + 1) * $this->rad;
        $this->y = $this->matrixSet[$centerIndex]['y'];
        $this->x = $this->matrixSet[$centerIndex]['x'];
        $this->stepDirectionsMatrix = array(
            $this->matrixSet[$centerIndex - $this->rad * 2 - 1]['vid'],
            $this->matrixSet[$centerIndex + 1]['vid'],
            isset($this->matrixSet[$centerIndex + $this->rad * 2 + 1]['vid']) ? $this->matrixSet[$centerIndex + $this->rad * 2 + 1]['vid'] : NULL,
            $this->matrixSet[$centerIndex - 1]['vid']
        );

        if (is_get('_a1_')) {
            $this->is_ajax = TRUE;
            echo $this->getClientScript();
            exit(0);
        }

        // Pre-rendering
        if (is_get('id')) {
            $this->viewData['villagesLinkPostfix'] .= '&id=' . intval(get('id'));
        }

        ## View

        // map content
        $map_content = array();
        $c = $this->rad * 2 + 1;
        $i = 0;
        while ($i < $c) {
            $j = 0;
            while ($j < $c) {
                $map_content['i_' . $i . '_' . $j] = $this->getCssClassName($i * $c + $j);
                ++$j;
            }
            ++$i;
        }
        $this->viewData['map_content'] = $map_content;

        // map rules
        $map_rules = array();
        $i2 = 0;
        while ($i2 < $c) {
            $x = isset($this->matrixSet[$i2 * $c]['x']) ? $this->matrixSet[$i2 * $c]['x'] : NULL;
            $y = $this->matrixSet[$i2]['y'];

            $map_rules[$i2] = array('x' => $x, 'y' => $y);
            ++$i2;
        }
        $this->viewData['map_rules'] = $map_rules;

        //
        for ($i = 0; $i <= 12; $i++) {
            for ($p = 0; $p <= 12; $p++) {
                $this->viewData['getMapArea_' . $i . '_' . $p] = $this->getMapArea($i, $p);
            }
        }

        //
        $this->viewData['directionsMatrix'] = $this->directionsMatrix;
        $this->viewData['stepDirectionsMatrix'] = $this->stepDirectionsMatrix;
        $this->viewData['x'] = $this->x;
        $this->viewData['y'] = $this->y;
        $this->viewData['stepDirectionsMatrix'] = $this->stepDirectionsMatrix;
        $this->viewData['getClientScript'] = $this->getClientScript();
    }

    public function getClientScript()
    {
        return sprintf('_mp={' . '"x":%s,"y":%s,' . '"n1":%s,"n2":%s,"n3":%s,"n4":%s,"n1p7":%s,"n2p7":%s,"n3p7":%s,"n4p7":%s,' . '"mtx":%s' . '};', $this->x, $this->y, $this->stepDirectionsMatrix[1], $this->stepDirectionsMatrix[2], $this->stepDirectionsMatrix[3], $this->stepDirectionsMatrix[0], $this->directionsMatrix[2], $this->directionsMatrix[0], $this->directionsMatrix[3], $this->directionsMatrix[1], $this->json);
    }

    public function isContractWith($allianceId)
    {
        return (isset($this->contractsAllianceId[$allianceId]) && $this->contractsAllianceId[$allianceId] == 0);
    }

    public function getCssClassName($index)
    {
        return isset($this->matrixSet[$index]) ? $this->getCssClassNameByItem($this->matrixSet[$index]) : NULL;
    }

    public function getCssClassNameByItem($mapItem)
    {
        if ($mapItem['is_oasis']) {
            return 'o' . $mapItem['image_num'];
        }
        if ($mapItem['player_id'] != '') {
            $c1 = 0;
            if ($mapItem['people_count'] < 100) {
                $c1 = 0;
            } else if ($mapItem['people_count'] < 250) {
                $c1 = 1;
            } else if ($mapItem['people_count'] < 500) {
                $c1 = 2;
            } else {
                $c1 = 3;
            }
            $c2 = 4;
            if ($this->player->playerId == $mapItem['player_id']) {
                $c2 = 0;
            } else {
                if ($mapItem['alliance_id'] != '') {
                    if ($this->data['alliance_id'] == $mapItem['alliance_id']) {
                        $c2 = 1;
                    } else if ($this->isContractWith($mapItem['alliance_id'])) {
                        $c2 = 3;
                    }
                }
            }
            return 'b' . $c1 . $c2;
        }
        return 't' . $mapItem['image_num'];
    }

    public function getMapAreaTitle($mapItem)
    {
        $title = '';
        if ($mapItem['is_oasis']) {
            $title = ($mapItem['player_id'] != '' ? oasis_place_owned : oasis_place_empty);
        } else if ($mapItem['player_id'] != '') {
            $title = $mapItem['village_name'];
        }
        return htmlspecialchars($title);
    }

    public function getMapArea($x, $y)
    {
        $mapItem = isset($this->matrixSet[$x * ($this->rad * 2 + 1) + $y]) ? $this->matrixSet[$x * ($this->rad * 2 + 1) + $y] : NULL;
        $title = $this->getMapAreaTitle($mapItem);
        return sprintf(' title="%s" %shref="village3?id=%s" onmouseover="showInfo(%s,%s)" onmouseout="hideInfo()"', $title, ($this->largeMap ? 'onclick="opener.location.href=this.href;return false;" ' : ''), $mapItem['vid'], $x, $y);
    }

    public function __getCoordInRange($map_size, $x)
    {
        if ($map_size <= $x) {
            $x -= $map_size;
        } else {
            if ($x < 0) {
                $x = $map_size + $x;
            }
        }
        return $x;
    }

    public function __getVillageId($map_size, $x, $y)
    {
        return $x * $map_size + ($y + 1);
    }

    public function __getVillageMatrix($map_size, $x, $y, $scale)
    {
        $matrix = '';
        $i = 0 - $scale;
        while ($i <= $scale) {
            $j = 0 - $scale;
            while ($j <= $scale) {
                if ($matrix != '') {
                    $matrix .= ',';
                }
                $matrix .= $this->__getVillageId($map_size, $this->__getCoordInRange($map_size, $x + $i), $this->__getCoordInRange($map_size, $y + $j));
                ++$j;
            }
            ++$i;
        }
        $matrix .= '|';
        $matrix .= $this->__getVillageId($map_size, $this->__getCoordInRange($map_size, $x + ($scale * 2 + 1)), $this->__getCoordInRange($map_size, $y));
        $matrix .= ',';
        $matrix .= $this->__getVillageId($map_size, $this->__getCoordInRange($map_size, $x - ($scale * 2 + 1)), $this->__getCoordInRange($map_size, $y));
        $matrix .= ',';
        $matrix .= $this->__getVillageId($map_size, $this->__getCoordInRange($map_size, $x), $this->__getCoordInRange($map_size, $y + ($scale * 2 + 1)));
        $matrix .= ',';
        $matrix .= $this->__getVillageId($map_size, $this->__getCoordInRange($map_size, $x), $this->__getCoordInRange($map_size, $y - ($scale * 2 + 1)));
        return $matrix;
    }
}

?>