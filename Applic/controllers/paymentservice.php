<?php

class Paymentservice_Controller extends Controller
{

    public function index()
    {
        if ($_POST) {
            require_once(MODELS_DIR . "/Payment.php");
            $m = new Payment_Model();
            $TransData = $m->getMonaydata($_POST['userOrderId']);
            $key = '=?sh2dINaXA4ASIZl6L*PPye1@Du+l$tknOnFg&o28sCZ&l94@7TJ!$Kz81qs^XR';
            $hash = hash('SHA256', post('transactionId') . post('userOrderId') . post('amount') . $key);
            if (post('hash') == $hash && $TransData != null && post('status') == "complete" && post('type') == "payment" && post('amount') == $TransData['money'] && post('refundedAmount') == 0 && $TransData['status'] == 0) {
                $m->incrementPlayerGold($TransData['usernam'], $TransData['golds']);
                $m->upMoneyLog($TransData['id'], post('transactionId'), 1);
                echo Success;
            } elseif (post('refundedAmount') > 0 && post('hash') == $hash && $TransData != null && $TransData['status'] != 0) {
                $m->cutNameGold($TransData['usernam'], $TransData['golds']);
            } else {
                echo Error;
            }

        } else {
            redirect("index.php");
        }

    }

}

?>