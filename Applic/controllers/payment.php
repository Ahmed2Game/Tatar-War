<?php
load_game_engine('Lite');

class Payment_Controller extends LiteController
{

    public $requestPaymentProvider = FALSE;
    public $providerType = "";
    public $package = NULL;
    public $payment = NULL;
    public $secureId = NULL;
    public $Domain = NULL;
    public $gold = NULL;

    public function __construct()
    {
        parent::__construct();
        $this->layoutViewFile = NULL;
    }

    public function index()
    {
        $this->load_model('Payment', 'P');
        $this->load_model('Servers', 'S');
        if (is_get('pg')) {
            $G2A = json_decode($this->S->GetSettings("G2A"), true);
            $package = $this->S->GetPackage(get('pg'));

            if (isset($G2A, $package)) {
                $playerName = $this->P->getPlayerDataById($this->player->playerId);
                $goldNumber = $package['gold'] + (($package['gold'] * ($package['bonus'] + $G2A['bonus'])) / 100);
                $order_id = $this->P->InsertMoneyLog("", $playerName, $goldNumber, $package['cost'], $G2A['currency'], "G2A", 0);
                $hash = hash('SHA256', $order_id . $package['cost'] . $G2A['currency'] . '=?sh2dINaXA4ASIZl6L*PPye1@Du+l$tknOnFg&o28sCZ&l94@7TJ!$Kz81qs^XR');
                $items = array();
                $items[] = array(
                    "sku" => $package['name'],
                    "name" => $goldNumber . text_gold_lang,
                    "amount" => $package['cost'],
                    "qty" => "1",
                    "price" => $package['cost'],
                    "id" => get('pg'),
                    "url" => URL . "plus"
                );
                $data = array(
                    'api_hash' => $G2A['merchant_id'],
                    'hash' => $hash,
                    'order_id' => $order_id,
                    'amount' => $package['cost'],
                    'currency' => $G2A['currency'],
                    'url_failure' => URL . "plus",
                    'url_ok' => URL . "plus?t=5",
                    'items' => $items
                );
                $obj = $this->request($data);
                if ($obj['status'] == "ok") {
                    $geturl = "https://checkout.pay.g2a.com/index/gateway?token=";
                    header('Location: ' . $geturl . $obj['token']);
                } else {
                    echo "<script type=\"text/javascript\">self.close();</script>";
                }
            } else {
                echo "<script type=\"text/javascript\">self.close();</script>";
            }
        }

    }

    public function request($data)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_URL, "https://checkout.pay.g2a.com/index/createQuote");
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 2);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_VERBOSE, 0);
        curl_setopt($curl, CURLOPT_POST, true);

        if (!empty($data)) {
            $fields = is_array($data) ? http_build_query($data) : (string)$data;
            curl_setopt($curl, CURLOPT_POSTFIELDS, $fields);
        }

        $response = curl_exec($curl);

        return json_decode($response, true);
    }

}

?>