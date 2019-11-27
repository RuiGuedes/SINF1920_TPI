<?php

namespace App\Http\Controllers;

use App\SalesOrders;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class JasminConnect extends Controller
{

    public function allSalesOrders() {

        try {
            $result = $this->callJasmin('sales/orders');
        } catch (ClientException $e) {
            return $e->getMessage();
        } catch (RequestException $e) {
            return $e->getMessage();
        } catch (GuzzleException $e) {
            return $e->getMessage();
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        $salesOrders = json_decode($result->getBody(), true);
        $added = false;

        foreach ($salesOrders as $saleOrder) {
            $order = SalesOrders::where('serie_id', $saleOrder['serieId'])->first()['id'];
            if (empty($order)) {
                try {
                    $newSaleOrder = new SalesOrders();
                    $newSaleOrder->create(['serie_id' => $saleOrder['serieId']]);
                } catch (Exception $e) {
                    return  $e;
                }
                $added = true;
            }
        }

        return $added ? response()->json("New sales orders added!", 200) : response()->json("Nothing to add!", 200);
    }

    public function saleOrderBySerieId($serieId)
    {
        try {
            $result = $this->callJasmin('sales/orders' . '/' . $serieId);
        } catch (ClientException $e) {
            return $e->getMessage();
        } catch (RequestException $e) {
            return $e->getMessage();
        } catch (GuzzleException $e) {
            return $e->getMessage();
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        return json_decode($result->getBody(), true);
    }

    public function allPurchaseOrders() {

        try {
            $result = $this->callJasmin('purchases/orders');
        } catch (ClientException $e) {
            return $e->getMessage();
        } catch (RequestException $e) {
            return $e->getMessage();
        } catch (GuzzleException $e) {
            return $e->getMessage();
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        return $result->getBody();
    }

    public function allStock() {

        try {
            $result = $this->callJasmin('materialsmanagement/stockTransferOrders');
        } catch (ClientException $e) {
            return $e->getMessage();
        } catch (RequestException $e) {
            return $e->getMessage();
        } catch (GuzzleException $e) {
            return $e->getMessage();
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        return $result->getBody();
    }

    public function createInvoice() {
        //
    }

    private function getAccessToken() {
        $user = "TPI-APP";
        $client_secret="44ea59c6-68bf-4638-af54-b39620a1cbf1";
        $client_id = '22478-0001';
        $url = "https://identity.primaverabss.com/core/connect/token";

        $client = new Client();
        try {

            $resultToken = $client->request('POST', $url,
                [
                    'form_params' =>
                        [
                            'grant_type' => 'client_credentials',
                            'scope' => 'application',
                            'client_secret' => $client_secret,
                            'client_id' => $client_id
                        ],
                    'auth' => [$user, $client_secret]
                ]
            );
        } catch (ClientException $e) {
            return $e;
        } catch (RequestException $e) {
            return $e;
        } catch (GuzzleException $e) {
            return $e;
        }

        return json_decode($resultToken->getBody(), true);
    }

    /**
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws GuzzleException
     */
    public function callJasmin(String $path, String $query='')
    {
        $token = $this->getAccessToken();

        $client = new Client();
        return $client->request(
            'GET',
            $this->getUri($path, $query),
            ['headers' =>
                ['Authorization' => $token['token_type'] . ' ' . $token['access_token']],
                ['Content-Type' => 'application/json']
            ]
        );
    }

    public function getUri(String $path, String $query = '') {
        $scheme = 'https://';
        $authority = 'my.jasminsoftware.com/api/';
        $tenant = '224978/';
        $organization = '224978-0001/';

        $partialUrl = $scheme . $authority . $tenant . $organization . $path;

        return empty($query) ? $partialUrl : $partialUrl . '?' . $query;
    }
}
