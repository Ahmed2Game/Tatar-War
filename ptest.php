<?php

$tasksInQueue = array('buildsNum' => 0, 'fieldsNum' => 0, 'out_merchants_num' => 0, 'merchant_travel' => array(), 'merchant_coming' => array(), 'war_troops' => array('to_village' => array(), 'from_village' => array(), 'to_oasis' => array()), 'war_troops_summary' => array('total_number' => 0, 'to_me' => array('attacks' => array('number' => 0, 'min_time' => -1), 'reinforce' => array('number' => 0, 'min_time' => -1)), 'from_me' => array('attacks' => array('number' => 0, 'min_time' => -1), 'reinforce' => array('number' => 0, 'min_time' => -1)), 'to_my_oasis' => array('attacks' => array('number' => 0, 'min_time' => -1), 'reinforce' => array('number' => 0, 'min_time' => -1))));
echo json_encode($tasksInQueue);
if($_POST['transactionId'] and $_POST['userOrderId'] and $_POST['amount'])
{
    $key = '=?sh2dINaXA4ASIZl6L*PPye1@Du+l$tknOnFg&o28sCZ&l94@7TJ!$Kz81qs^XR';
    $hash = hash('SHA256', $_POST['transactionId'].$_POST['userOrderId'].$_POST['amount'].$key);
    echo "transaction hash code : ". $hash;
}
?>

<form action="ptest.php" method="post">
    <table cellpadding="1" cellspacing="1">
        <tbody>
            <tr>userOrderId<input class="text" type="text" name="userOrderId" maxlength="20" value=""></tr>
            <tr>transactionId<input class="text" type="text" name="transactionId" maxlength="100" value=""></tr>
            <tr>amount<input class="text" type="text" name="amount" maxlength="20" value=""></tr>
            <tr><button type="submit">Submit</button></tr>
        </tbody>
    </table>
</form>

<form action="http://www.xtatar.com/paymentservice" method="post">
    <table cellpadding="1" cellspacing="1">
        <tbody>
            <tr>userOrderId<input class="text" type="text" name="userOrderId" maxlength="20" value=""></tr>
            <tr>transactionId<input class="text" type="text" name="transactionId" maxlength="100" value=""></tr>
            <tr>amount<input class="text" type="text" name="amount" maxlength="20" value=""></tr>
            <tr>hash<input class="text" type="text" name="hash" maxlength="100" value=""></tr>
            <tr><input class="text" type="text" name="refundedAmount" maxlength="20" value="0"></tr>
            <tr><input class="text" type="text" name="status" maxlength="20" value="complete"></tr>
            <tr><input class="text" type="text" name="type" maxlength="20" value="payment"></tr>
            <tr><button type="submit">Submit</button></tr>
        </tbody>
    </table>
</form>