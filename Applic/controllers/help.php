<?php
load_game_engine('Lite');

class Help_Controller extends LiteController
{
    public $state = 0;
    public $id = NULL;
    public $tribeId = NULL;
    public $buildingGroup = NULL;
    public $build = NULL;
    public $troopId = NULL;
    public $troop = NULL;
    public $plusIndex = NULL;
    public $nextLink = NULL;
    public $previousLink = NULL;

    public function __construct()
    {
        $this->layoutViewFile = 'layout/popup';
        $this->viewFile = 'help';
        parent::__construct();
    }

    public function index()
    {
        $this->nextLink = "";
        $this->previousLink = "";
        $this->state = is_get('c') && is_numeric(get('c')) && 0 <= intval(get('c')) && intval(get('c')) <= 7 ? intval(get('c')) : 0;
        $id = is_get('id') && is_numeric(get('id')) ? get('id') : 0;
        switch ($this->state) {
            case 1:
                {
                    if (((($id != 1 && $id != 2) && $id != 3) && $id != 7)) {
                        $this->state = 0;
                    } else {
                        $this->tribeId = $id;
                        if ($id == 1) {
                            $next = 2;
                            $prev = 7;
                        } else {
                            if ($id == 2) {
                                $next = 3;
                                $prev = 1;
                            } else {
                                if ($id == 3) {
                                    $next = 6;
                                    $prev = 2;
                                } else {
                                    if ($id == 7) {
                                        $next = 1;
                                        $prev = 6;
                                    }
                                }

                            }
                        }
                        $this->nextLink = '?c=1&id=' . $next;
                        $this->previousLink = '?c=1&id=' . $prev;
                    }
                    break;
                }
            case 2:
                {
                    if (($id <= 0 || 4 < $id)) {
                        $this->state = 0;
                    } else {
                        $this->buildingGroup = $id;
                        if ($id == 1) {
                            $next = 2;
                            $prev = 3;
                        } else {
                            if ($id == 2) {
                                $next = 3;
                                $prev = 1;
                            } else {
                                if ($id == 3) {
                                    $next = 1;
                                    $prev = 2;
                                }
                            }
                        }
                        $this->nextLink = '?c=2&id=' . $next;
                        $this->previousLink = '?c=2&id=' . $prev;
                    }
                    break;
                }
            case 3:
                {
                    if (!isset($this->gameMetadata['troops'][$id])) {
                        $this->state = 0;
                    } else {
                        $this->troopId = $id;
                        $this->troop = $this->gameMetadata['troops'][$id];
                        if ($id == 1) {
                            $next = 2;
                            $prev = 109;
                        } else {
                            if ($id == 30) {
                                $next = 100;
                                $prev = 29;
                            } else {

                                if ($id == 100) {
                                    $next = 101;
                                    $prev = 30;
                                } else {
                                    if ($id == 109) {
                                        $next = 1;
                                        $prev = 108;
                                    } else {
                                        $next = $id + 1;
                                        $prev = $id - 1;
                                    }
                                }
                            }
                        }
                        $this->nextLink = '?c=3&id=' . $next;
                        $this->previousLink = '?c=3&id=' . $prev;
                    }
                    break;
                }
            case 4:
                {
                    if (!isset($this->gameMetadata['items'][$id])) {
                        $this->state = 0;
                    } else {
                        $this->viewData['itemId'] = $id;
                        $this->build = $this->gameMetadata['items'][$id];
                        if ($id == 1) {
                            $next = 2;
                            $prev = 40;
                        } else {
                            if ($id == 14) {
                                $next = 16;
                                $prev = 13;
                            } else {
                                if ($id == 16) {
                                    $next = 19;
                                    $prev = 14;
                                } else {
                                    if ($id == 19) {
                                        $next = 20;
                                        $prev = 16;
                                    } else {
                                        if ($id == 22) {
                                            $next = 29;
                                            $prev = 21;
                                        } else {
                                            if ($id == 29) {
                                                $next = 30;
                                                $prev = 22;
                                            } else {
                                                if ($id == 30) {
                                                    $next = 36;
                                                    $prev = 29;
                                                } else {
                                                    if ($id == 36) {
                                                        $next = 37;
                                                        $prev = 30;
                                                    } else {
                                                        if ($id == 37) {
                                                            $next = 42;
                                                            $prev = 36;
                                                        } else {
                                                            if ($id == 42) {
                                                                $next = 15;
                                                                $prev = 37;
                                                            } else {
                                                                if ($id == 15) {
                                                                    $next = 17;
                                                                    $prev = 42;
                                                                } else {
                                                                    if ($id == 17) {
                                                                        $next = 18;
                                                                        $prev = 15;
                                                                    } else {
                                                                        if ($id == 18) {
                                                                            $next = 23;
                                                                            $prev = 17;
                                                                        } else {
                                                                            if ($id == 23) {
                                                                                $next = 24;
                                                                                $prev = 18;
                                                                            } else {
                                                                                if ($id == 26) {
                                                                                    $next = 28;
                                                                                    $prev = 25;
                                                                                } else {
                                                                                    if ($id == 28) {
                                                                                        $next = 34;
                                                                                        $prev = 26;
                                                                                    } else {
                                                                                        if ($id == 34) {
                                                                                            $next = 35;
                                                                                            $prev = 28;
                                                                                        } else {
                                                                                            if ($id == 35) {
                                                                                                $next = 38;
                                                                                                $prev = 34;
                                                                                            } else {
                                                                                                if ($id == 38) {
                                                                                                    $next = 39;
                                                                                                    $prev = 35;
                                                                                                } else {
                                                                                                    if ($id == 39) {
                                                                                                        $next = 41;
                                                                                                        $prev = 38;
                                                                                                    } else {
                                                                                                        if ($id == 41) {
                                                                                                            $next = 40;
                                                                                                            $prev = 39;
                                                                                                        } else {
                                                                                                            if ($id == 40) {
                                                                                                                $next = 1;
                                                                                                                $prev = 41;
                                                                                                            } else {
                                                                                                                $next = $id + 1;
                                                                                                                $prev = $id - 1;
                                                                                                            }
                                                                                                        }
                                                                                                    }
                                                                                                }
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        $this->nextLink = '?c=4&id=' . $next;
                        $this->previousLink = '?c=4&id=' . $prev;
                    }
                    break;
                }
            case 5:
                {
                    $this->plusIndex = $id;
                    break;
                }
            case 6:
                {
                }
            case 7:
                {
                    $this->id = $id;
                }
        }
        $this->viewData['id'] = $this->id;
        $this->viewData['tribeId'] = $this->id;
        $this->viewData['id'] = $this->id;
        $this->viewData['state'] = $this->state;
        $this->viewData['id'] = $this->id;
        $this->viewData['tribeId'] = $this->tribeId;
        $this->viewData['buildingGroup'] = $this->buildingGroup;
        $this->viewData['build'] = $this->build;
        $this->viewData['troopId'] = $this->troopId;
        $this->viewData['troop'] = $this->troop;
        $this->viewData['plusIndex'] = $this->plusIndex;
        $this->viewData['nextLink'] = $this->nextLink;
        $this->viewData['previousLink'] = $this->previousLink;
    }
}

?>