<?php
namespace Modules\Order\Traits;

use Carbon\Carbon;



trait UnitellerTrait {

    public $ACTION = "https://wpay.uniteller.ru/pay";
    public $METHOD = "POST";

    public $CURRENCY = "RUB";

    public $ALGORITHM = "md5";
    public $LIFETIME = 3600;

    public $ORDER_IDP_KEY = 'id';
    public $SUBTOTAL_P_KEY = 'amount';

    protected $URL_RETURN_OK = '';
    protected $URL_RETURN_NO = '';

    public $CallbackFields = ['AcquirerID', 'BillNumber', 'EMoneyType', 'PaymentType'];

    public $payment_status_key = 'status';


    public function getStatusCanceledAttribute() {
        return $this->un_status_canceled ?: 'canceled';
    }
    public function getStatusWaitingAttribute() {
        return $this->un_status_waiting ?: 'waiting';
    }
    public function getStatusPaidAttribute() {
        return $this->un_status_paid ?: 'paid';
    }
    public function getStatusAuthorizedAttribute() {
        return $this->un_status_authorized ?: 'authorized';
    }

    public function getStatusNotAuthorizedAttribute() {
        return $this->un_status_not_authorized ?: 'not authorized';
    }


    public function getUnitellerStatuses(){
        return [
            'waiting' => $this->status_waiting,
            'canceled' => $this->status_canceled,
            'paid' => $this->status_paid,
            'authorized' => $this->status_authorized,
            'not authorized' => $this->status_not_authorized
        ];
    }

    public function setUnitellerStatus($value) {
        $statuses = $this->getUnitellerStatuses();
        $status = $value;
        if(!empty($statuses[$value])) {
            $status = $statuses[$value];
        }
        $this->{$this->payment_status_key} = $status;
        $this->save();
    }

    public function getUnitellerPointIdAttribute(){
        return config('uniteller.point_id');
    }

    public function getUnitellerPasswordAttribute(){
        return config('uniteller.password');
    }

    public function getUnitellerLoginAttribute(){
        return config('uniteller.login');
    }

    public function getUrlReturnNoAttribute() {
        return $this->URL_RETURN_NO ?: url()->current();
    }


    public function setUrlReturnNoAttribute($value) {
        $this->URL_RETURN_NO = $value;
    }

    public function getUrlReturnOkAttribute() {
        return $this->URL_RETURN_OK ?: url()->current();
    }


    public function setUrlReturnOkAttribute($value) {
        $this->URL_RETURN_OK = $value;
    }

    public function createPaymentForm($email = null)
    {
        $Shop_IDP = $this->uniteller_point_id;
        $password = $this->uniteller_password;
        $Order_IDP = $this->{$this->ORDER_IDP_KEY};
        $Subtotal_P = $this->{$this->SUBTOTAL_P_KEY};
        $Lifetime = $this->LIFETIME;
        $URL_RETURN_OK = $this->url_return_ok;
        $URL_RETURN_NO = $this->url_return_no;
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
                "CallbackFields" => join(" ", $this->CallbackFields),
                "Email" => $email,
            ]
        ];
    }

    public function checkHash($post = []){
        $string = '';
        foreach($post as $field => $value) {
            if($field != 'Signature') {
                $string .= $value;
            }
        }
        $string .= $this->uniteller_password;

        return $post['Signature'] == mb_strtoupper(hash($this->ALGORITHM, $string));
    }

    public function getInfoUniteller() {
        $start_date = Carbon::now()->subDays(6);
        $now = Carbon::now();

        $post_data = [
            'Shop_ID' => $this->uniteller_point_id,
            'Login' => $this->uniteller_login,
            'Password' => $this->uniteller_password,
            'Format' => 1,
            'Success' => 2,
            'StartDay' => $start_date->day,
            'StartMonth' => $start_date->month,
            'StartYear' => $start_date->year,
            'EndDay' => $now->day,
            'EndMonth' => $now->month,
            'EndYear' => $now->year,
            'MeanType' => 0,
            'EMoneyType' => 0,
            'S_FIELDS' => 'OrderNumber;Status'
        ];


        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, "https://wpay.uniteller.ru/results/");
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);



        $response = curl_exec($curl);

        $statuses = [];
        $translate_statuses = $this->getUnitellerStatuses();

        foreach(explode("\n", trim($response)) as $row) {
            $args = explode(';', $row);
            $statuses[$args[0]] = mb_strtolower($args[1]);
        }



        curl_close($curl);

        self::whereDate('updated_at', '>=', $start_date)->chunk(100, function($orders) use($statuses, $translate_statuses){
            foreach($orders as $order) {
                if(!empty($statuses[$order->{$this->ORDER_IDP_KEY}])) {
                    $status = $statuses[$order->{$this->ORDER_IDP_KEY}];
                    if(!empty($translate_statuses[$status])) {
                        $translate_status = $translate_statuses[$status];
                        if($translate_status != $order->{$this->payment_status_key}) {
                            $order->setUnitellerStatus($status);
                            $result[] = $order->id;
                        }
                    }
                }
            }
        });


    }
}
