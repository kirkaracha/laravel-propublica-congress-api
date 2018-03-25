<?php declare(strict_types=1);

namespace Kirkaracha\PropublicaCongressApi;

use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Exception\GuzzleException;

class PropublicaCongressApi
{
    const BASE_URI = 'https://api.propublica.org/congress/v1/';

    /** @var int $currentCongress */
    private $currentCongress;

    public function __construct()
    {
        $this->currentCongress = 115;
    }

    /**
     * @param string $uriStub
     * @param int $offset
     * @param array $queries
     * @return mixed
     */
    public function performApiRequest(string $uriStub, int $offset = 0, array $queries = [])
    {
        try {
            $client = new GuzzleHttpClient([
                'base_uri' => self::BASE_URI
            ]);

            $headers = [
                'X-API-KEY' => config('propublica-congress-api.api-key')
            ];

            if ($offset > 0) {
                $queries['offset'] = $offset;
            }

            if (! empty($queries)) {
                $request = $client->get($uriStub, [
                    'headers' => $headers, ['query' => $queries]
                ]);
            } else {
                $request = $client->get($uriStub, [
                    'headers' => $headers
                ]);
            }

//            return json_decode($apiRequest->getBody()->getContents());
        } catch (GuzzleException $e) {
            //For handling exception
        }
    }
}
