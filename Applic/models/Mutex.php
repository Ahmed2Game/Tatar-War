<?php
define("__QS_LOCK_FS_", MODELS_DIR . "lock");

class Mutex_Model extends Model
{

    public function lock()
    {
        if (0 < db::count("UPDATE g_settings gs SET gs.qlocked=1, qlocked_date=NOW() WHERE gs.qlocked=0") && ($fp = fopen(__QS_LOCK_FS_, "r")) != FALSE) {
            if (flock($fp, LOCK_EX)) {
                fclose($fp);
                return TRUE;
            }
            fclose($fp);
        }
        return FALSE;
    }

    public function release()
    {
        $this->_releaseInternal();
        db::query("UPDATE g_settings gs SET gs.qlocked=0");
    }

    public function releaseOnTimeout()
    {
        if (0 < db::count("UPDATE g_settings gs SET gs.qlocked=0 WHERE gs.qlocked=1 AND TIME_TO_SEC(TIMEDIFF(NOW(), gs.qlocked_date)) > 120")) {
            $this->_releaseInternal();
        }
    }

    public function _releaseInternal()
    {
        if (($fp = fopen(__QS_LOCK_FS_, "r")) != FALSE) {
            flock($fp, LOCK_UN);
            fclose($fp);
        }
    }

}

?>
