<?php

namespace App\Traits;

use GuzzleHttp\Client;

trait ConsumesExternalServices
{

    /**
     * Undocumented function
     *
     * @param String $method
     * @param String $requestUrl
     * @param array $queryParams
     * @param array $formParams
     * @param array $headers
     * @param boolean $isJsonRequest
     * @return void
     */
    public function makeRequest($method, $requestUrl, $queryParams = [], $formParams = [], $headers = [], $isJsonRequest = false)
    {
        $client = new Client([
            'base_uri' => $this->baseUri,
        ]);
        if (method_exists($this, 'resolveAuthorization')) {
            $this->resolveAuthorization($queryParams, $formParams, $headers);
        }
        // dd($formParams, $requestUrl);
        $response = $client->request($method, $requestUrl, [
            $isJsonRequest ? 'json' : 'form_params' => $formParams,
            'headers' => $headers,
            'query' => $queryParams
        ]);
        $response = $response->getBody()->getContents();
        if (method_exists($this, 'decodeResponse')) {
            $response = $this->decodeResponse($response);
        }
        return $response;
    }
}
