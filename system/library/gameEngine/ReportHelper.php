<?php

class ReportHelper
{

    public static function getReportResultRelative($result, $isAttack)
    {
        if ($result < 15 || $result == 100) {
            return $result;
        }
        return intval(substr(strval($result), $isAttack ? 1 : 0, 1));
    }

    public static function getReportResultText($result)
    {
        return constant("report_result_text" . $result);
    }

    public static function getReportActionText($cat)
    {
        return " " . constant("report_action_text" . $cat) . " ";
    }

}

// END
