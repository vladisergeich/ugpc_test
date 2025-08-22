<?php

namespace App\Services;

use App\Services\SOAP\SoapClient;

class NavisionService
{
    private array $navisionUrls;
    private array $soapOptions;

    public function __construct()
    {
        $this->navisionUrls = [
            'a' => env('NAV_ALABUGA_PAGE_SERVICE_URL') . 'ProdOrderAndLinePage',
            'n' => env('NAV_NANO_PAGE_SERVICE_URL') . 'ProdOrderAndLinePage',
            'default' => env('NAV_ZAO_PAGE_SERVICE_URL') . 'ProdOrderAndLinePage',
        ];

        $this->soapOptions = [
            'login' => env('SOAP_NAV_USER'),
            'password' => env('SOAP_NAV_PASSWORD'),
        ];
    }

    private function getNavisionUrl($orderNumber): string
    {
        $prefix = strtolower(mb_substr($orderNumber, 0, 1));
        return $this->navisionUrls[$prefix] ?? $this->navisionUrls['default'];
    }

    public function getOrderData($orderNumber)
    {
        try {
            $url = $this->getNavisionUrl($orderNumber);
            
            $client = new SoapClient($url, $this->soapOptions);

            $result = $client->ReadMultiple([
                'filter' => [
                    ['Field' => 'No', 'Criteria' => "'{$orderNumber}'"],
                ],
                'setSize' => 1
            ]);

            return $result->ReadMultiple_Result->ProdOrderAndLinePage ?? null;
        } catch (\Exception $e) {
            \Log::error("Ошибка SOAP-запроса: " . $e->getMessage());
            return null;
        }
    }
}
