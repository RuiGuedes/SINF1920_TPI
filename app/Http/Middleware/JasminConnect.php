<?php

namespace App\Http\Middleware;

use App\JasminToken;
use Exception;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

class JasminConnect
{

    /**
     * Calls jasmin
     *
     * @param String $path
     * @param String $query
     * @return Exception|ClientException|GuzzleException|RequestException|mixed|ResponseInterface
     */
    public static function callJasmin(String $path, String $query = '')
    {
        $token = JasminToken::getToken();

        $client = new Client();
        try {
            return $client->request(
                'GET',
                self::getUri($path, $query),
                ['headers' =>
                    ['Authorization' => $token['token_type'] . ' ' . $token['access_token']],
                    ['Content-Type' => 'application/json']
                ]
            );
        } catch (ClientException $e) {
            return $e;
        } catch (RequestException $e) {
            return $e;
        } catch (GuzzleException $e) {
            return $e;
        }
    }

    /**
     * Generates a new token
     *
     * @return Exception|ClientException|GuzzleException|RequestException|mixed
     */
    public static function generateNewToken()
    {
        $user = "TPI-APP";
        $client_secret = "44ea59c6-68bf-4638-af54-b39620a1cbf1";
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
     * Builds an Uri through the path and query parameters
     *
     * @param String $path
     * @param String $query
     * @return string
     */
    private static function getUri(String $path, String $query = '') {
        $scheme = 'https://';
        $authority = 'my.jasminsoftware.com/api/';
        $tenant = '224978/';
        $organization = '224978-0001/';

        $partialUrl = $scheme . $authority . $tenant . $organization . $path;

        return empty($query) ? $partialUrl : $partialUrl . '?' . $query;
    }
}
