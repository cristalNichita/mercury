<?php
namespace Modules\Order\Traits;

use Carbon\Carbon;
use Settings;

trait UnitellerPayment {

    public $statuses = [
        'waiting' => 'waiting',
        'canceled' => 'fail',
        'paid' => 'success',
        'authorized' => 'success',
        'not authorized' => 'waiting'
    ];

    public $ACTION = "https://wpay.uniteller.ru/pay";
    public $METHOD = "POST";

    public $CURRENCY = "RUB";

    public $ALGORITHM = "md5";
    public $LIFETIME = 3600;

    public $ORDER_IDP_KEY = 'id';
    public $SUBTOTAL_P_KEY = 'amount';

    public $CallbackFields = ['AcquirerID', 'BillNumber', 'EMoneyType', 'PaymentType'];

    public $payment_status_key = 'status';


    public function _createPaymentForm($orderId, $price, $email = null)
    {
        $Shop_IDP = $this->getUnitellerPointId();
        $password =  $this->getUnitellerPassword();
        $Order_IDP = $orderId;
        $Subtotal_P = $price;
        $Lifetime = $this->LIFETIME;
        $URL_RETURN_OK = $this->getUrlReturnOk();
        $URL_RETURN_NO = $this->getUrlReturnNo();
        $algorithm = $this->ALGORITHM;

        $Signature = mb_strtoupper(
            hash( $algorithm,
                hash($algorithm, $Shop_IDP) . "&" .
                hash($algorithm, $Order_IDP) . "&" .
                hash($algorithm, $Subtotal_P) . "&" .
                hash($algorithm, "") . "&" .
                hash($algorithm, "") . "&" .
                hash($algorithm, $Lifetime) . "&" .
                hash($algorithm, "") . "&" .
                hash($algorithm, "") . "&" .
                hash($algorithm, "") . "&" .
                hash($algorithm,"") . "&" .

                hash($algorithm, $password)
            )
        );

        return [

            "action" => $this->ACTION,
            "method" => $this->METHOD,
            "fields" => [
                "Shop_IDP" => $Shop_IDP,
                "Order_IDP" => $Order_IDP,
                "Subtotal_P" => $Subtotal_P,
                "Lifetime" => $Lifetime,
                'Signature' => $Signature,
                "URL_RETURN_OK" => $URL_RETURN_OK,
                "URL_RETURN_NO" => $URL_RETURN_NO,
                "Currency" => $this->CURRENCY,
                "CallbackFields" => implode(" ", $this->CallbackFields),
                "Email" => $email,
            ]
        ];
    }


    public function getUnitellerStatuses(){
        return $this->statuses;
    }

    public function setUnitellerStatus($payment, $status) {
        $statuses = $this->getUnitellerStatuses();

        if(isset($this->statuses[$status])) {
            $payment->{$this->payment_status_key} = $this->statuses[$status];
            $payment->save();
        } else {
            throw new \Exception('Неожидаемый статус оплаты - ' . $status);
        }

    }

    public function getUnitellerPointId(){
        return Settings::get('uniteller__point_id') ?: config('uniteller.point_id');
    }

    public function getUnitellerPassword(){
        return Settings::get('uniteller__password') ?: config('uniteller.password');
    }

    public function getUnitellerLogin(){
        return Settings::get('uniteller__login') ?: config('uniteller.login');
    }

    public function getUrlReturnNo() {
        return env('FRONT_URL') . '/lk#fail';
    }

    public function getUrlReturnOk() {
        return env('FRONT_URL') . '/lk#success';
    }

    public function checkHash($post = []){
        $string = '';
        foreach($post as $field => $value) {
            if($field != 'Signature') {
                $string .= $value;
            }
        }
        $string .= $this->getUnitellerPassword();

        return $post['Signature'] == mb_strtoupper(hash($this->ALGORITHM, $string));
    }

    // Не используемая функция возможно планировалась проверка платежей по крону
//    public function getInfoUniteller() {
//        $start_date = Carbon::now()->subDays(6);
//        $now = Carbon::now();
//
//        $post_data = [
//            'Shop_ID' => $this->uniteller_point_id,
//            'Login' => $this->uniteller_login,
//            'Password' => $this->uniteller_password,
//            'Format' => 1,
//            'Success' => 2,
//            'StartDay' => $start_date->day,
//            'StartMonth' => $start_date->month,
//            'StartYear' => $start_date->year,
//            'EndDay' => $now->day,
//            'EndMonth' => $now->month,
//            'EndYear' => $now->year,
//            'MeanType' => 0,
//            'EMoneyType' => 0,
//            'S_FIELDS' => 'OrderNumber;Status'
//        ];
//
//
//        $curl = curl_init();
//
//        curl_setopt($curl, CURLOPT_URL, "https://wpay.uniteller.ru/results/");
//        curl_setopt($curl, CURLOPT_POST, 1);
//        curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
//        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
//
//
//
//        $response = curl_exec($curl);
//
//        $statuses = [];
//        $translate_statuses = $this->getUnitellerStatuses();
//
//        foreach(explode("\n", trim($response)) as $row) {
//            $args = explode(';', $row);
//            $statuses[$args[0]] = mb_strtolower($args[1]);
//        }
//
//
//
//        curl_close($curl);
//
//        self::whereDate('updated_at', '>=', $start_date)->chunk(100, function($orders) use($statuses, $translate_statuses){
//            foreach($orders as $order) {
//                if(!empty($statuses[$order->{$this->ORDER_IDP_KEY}])) {
//                    $status = $statuses[$order->{$this->ORDER_IDP_KEY}];
//                    if(!empty($translate_statuses[$status])) {
//                        $translate_status = $translate_statuses[$status];
//                        if($translate_status != $order->{$this->payment_status_key}) {
//                            $order->setUnitellerStatus($status);
//                            $result[] = $order->id;
//                        }
//                    }
//                }
//            }
//        });
//
//
//    }
}
