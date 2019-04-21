<?php

load_core('Admin');

class Payment_Controller extends AdminController
{
    public $selectedRank = null;
    public $pageSize = 20;
    public $pageCount = null;
    public $pageIndex = null;
    public $dataList = null;

    /**
     * Constructor Method
     * This method defines template layout && view file and load model
     */
    public function __construct()
    {
        parent::__construct();
        $this->viewFile = "payment";
    }

    /**
     * Index Method
     *
     * @return void
     */
    public function index()
    {
        $this->load_model('Payment', 'm');
        global $gameConfig;
        $this->viewData['url'] = $gameConfig['system']['server_url'];
        if (is_get('type')) {
            $rowsCount = $this->m->PayhisListByType(get('type'));
            $this->pageCount = (0 < $rowsCount ? ceil($rowsCount / $this->pageSize) : 1);
            $this->pageIndex = (is_get('p') && is_numeric(get('p'))) ? intval(get('p')) : 0;
            if ($this->pageCount <= $this->pageIndex) {
                $this->pageIndex = $this->pageCount - 1;
            }
            $this->dataList = $this->m->PayhisByType(get('type'), $this->pageIndex, $this->pageSize);
        }

        if (is_get('word')) {
            if (get('id') == 1) {
                $this->dataList = $this->m->PayhisPyTransid(get('word'));
            } else {
                $this->dataList = $this->m->PayhisPyName(get('word'));
            }
        }

        if (is_get('type') || is_get('word')) {
            $rowIndex = 0;
            $dataList = array();
            foreach ($this->dataList as $value) {
                ++$rowIndex;
                $rank = $rowIndex + $this->pageIndex * $this->pageSize;
                $playerName = $value['usernam'];
                $userid = $this->m->getPlayerDataByName($playerName);

                $dataList[$rank] = array(
                    'userid' => $userid,
                    'transID' => $value['transID'],
                    'usernam' => $value['usernam'],
                    'golds' => $value['golds'],
                    'money' => $value['money'],
                    'currency' => $value['currency'],
                    'time' => $value['mdate'],
                );
            }
            $this->viewData['dataList'] = $dataList;
            unset($dataList);
            unset($this->dataList);
        }
        $this->viewData['getPreviousLink'] = $this->getPreviousLink();
        $this->viewData['getNextLink'] = $this->getNextLink();
    }

    public function getNextLink()
    {
        $link = '';
        if ($this->pageIndex + 1 == $this->pageCount) {
            return $link;
        }
        if (is_get('type')) {
            $link .= 'type=' . get('type');
        }
        if ($link != '') {
            $link .= '&';
        }
        $link .= 'p=' . ($this->pageIndex + 1);
        $link = '?' . $link;
        return $link;
    }

    public function getPreviousLink()
    {
        $link = '';
        if ($this->pageIndex == 0) {
            return $link;
        }
        $link = '';
        if (is_get('type')) {
            $link .= 'type=' . get('type');
        }
        if (0 < $this->pageIndex) {
            if ($link != '') {
                $link .= '&';
            }
            $link .= 'p=' . ($this->pageIndex - 1);
        }
        if ($link != '') {
            $link = '?' . $link;
        }
        return $link;
    }

}
//end file
