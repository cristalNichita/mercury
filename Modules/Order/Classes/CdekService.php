<?php


namespace Modules\Order\Classes;


use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use Illuminate\Http\Client\Request;
use GuzzleHttp\Client as GuzzleClient;
use Http\Adapter\Guzzle6\Client as GuzzleAdapter;
use \CdekSDK2\Client as CdekSDK2Client;
use Modules\Order\Entities\Cart;

class CdekService
{

    private CdekSDK2Client $client;
    private string $country_codes = 'RU';
    private bool $is_test;
    private string $clientId;
    private string $clientSecret;

    public function __construct() {

        $this->clientId = getenv('CDEK_ACCOUNT');
        $this->clientSecret = getenv('CDEK_PASSWORD');
        $this->is_test = (bool) getenv('CDEK_TEST');

        $guzzle = new GuzzleClient([]);
        $adapter = new GuzzleAdapter($guzzle);
        $cdek = new CdekSDK2Client($adapter, $this->clientId, $this->clientSecret);
        $cdek->setTest($this->is_test);
        $this->client = $cdek;

    }

    public function getRegions() {
        $regions = Cache::tags(['cdek'])->get('regions', collect([]));

        if($regions->isEmpty()) {

            $result = $this->client->regions()->getFiltered(['country_codes' => $this->country_codes]);

            if($result->isOk()) {
                $response = $this->client->formatResponseList($result, \CdekSDK2\Dto\RegionList::class);
                $regions = collect(json_decode(json_encode($response->items, true), true));

                Cache::tags(['cdek'])->put('regions', collect($regions), now()->addDay());
            }
        }

        return $regions;
    }

    public function isRegionCode(int $code = null) {

        $regions = $this->getRegions();
        return $regions->where('region_code', $code)->count() > 0;

    }

    public function isCityCode(int $regionCode = null, int $cityCode = null) {

        $cities = $this->getCities($regionCode);
        return $cities->where('code', $cityCode)->count() > 0;

    }


    /**
     * Лучше не использовать $city, потому что мы забираем города 1 запросом, а потом уже у нас фильтруем
     * @param int|null $regionCode
     * @param string $city
     * @return \Illuminate\Support\Collection|mixed
     */
    public function getCities(int $regionCode = null, string $city = '') {

        $cities = Cache::tags(['cdek', 'cities'])->get("{$regionCode}.{$city}", collect([]));

        if($cities->isEmpty()) {
            $for_filter = ['country_codes' => $this->country_codes];
            if($regionCode) {
                $for_filter['region_code'] = $regionCode;
            }
            if($city) {
                $for_filter['city'] = $city;
            }

            $result = $this->client->cities()->getFiltered($for_filter);
            if ($result->isOk()) {
                $response = $this->client->formatResponseList($result, \CdekSDK2\Dto\CityList::class);
                $cities = collect(json_decode(json_encode($response->items, true), true));
                Cache::tags(['cdek', 'cities'])->put("{$regionCode}.{$city}", $cities, now()->addDay());
            }
        }

        return $cities;
    }

    public function getPvz(int $cityCode = null, bool $is_handout = true) {

        $pvz = Cache::tags(['cdek', 'pvz'])->get($cityCode, collect([]));

        if($pvz->isEmpty()) {
            $for_filter = ['country_codes' => $this->country_codes, 'is_handout' => (int) $is_handout ];

            if ($cityCode) {
                $for_filter['city_code'] = $cityCode;
            }

            $result = $this->client->offices()->getFiltered($for_filter);
            if ($result->isOk()) {
                $response = $this->client->formatResponseList($result, \CdekSDK2\Dto\PickupPointList::class);
                $pvz = collect(json_decode(json_encode($response->items, true), true));
                Cache::tags(['cdek', 'pvz'])->put($cityCode, $pvz, now()->addDay());
            }
        }

        return $pvz;

    }


    public function calculate(array $data, Cart $cart) {

        if(!$this->client->authorize()) {
            throw new \ErrorException('Ошибка авторизации');
        }

        $url = $this->is_test ? 'https://api.edu.cdek.ru/v2/calculator/tarifflist' : 'https://api.cdek.ru/v2/calculator/tarifflist';

        $data = [
            'currency' => 1,
            'to_location' => $data['to_location'],
            'from_location' => [
                'address' => 'волгоград проспект ленина 28',
                "code" => 137
            ],
            "packages" => [
                "height" => $cart->items->max('product.volume'),
                "weight" => $cart->items->sum('product.weight'),
                "width" => 10,
                "length" => $cart->items->count() * 2,
            ]
        ];

        $request = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->client->getToken()
        ])->post($url, $data);

        return $request->json();
    }


    public function createOrder() {



    }

}
