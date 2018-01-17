<?php

namespace App\Http\Api\V1\Controllers;

use App\Services\Targets\TargetService;
use Laravel\Lumen\Routing\Controller;
use Illuminate\Http\Request;
use App\Presenters\JsonApiPresenter;
use GuzzleHttp\Client;
class TargetController extends Controller
{
    private $service;

    public function __construct(TargetService $service)
    {
        $this->service = $service;
        $this->presenter = new JsonApiPresenter;
        $this->client = new Client;
    }

    public function browse()
    {
        $target = $this->service->browse();
        return $this->presenter->renderPaginator($target);
    }

    public function add(Request $request)
    {
        $url = $this->getDetailUrl($request->url);
        $target = $this->service->add($url);
        return $this->presenter->render($target, 200, [
            'Content-Type' => 'application/vnd.api+json',
            'Accept' => 'application/vnd.api+json'
        ]);
    }

    private function getDetailUrl($url)
    {
        $site = 'http://ip-api.com/json/'.$url;
        $client = json_decode($this->client->request('GET', $site)->getBody());
        if($client->status == 'success'){
            $data = [
                'url' => $url,
                'ip' => $client->query,
                'as' => $client->as,
                'city' => $client->city,
                'country' => $client->country,
                'countryCode' => $client->countryCode,
                'isp' => $client->isp,
                'latitude' => $client->lat,
                'longitude' => $client->lon,
                'org' => $client->org,
                'regionName' => $client->regionName,
                'timeZone' => $client->timezone,
                'zip' => $client->zip,
            ];
        }else{
            $data = [
                'message' => $client->message,
            ];
        }
        return $data;
    }
}
