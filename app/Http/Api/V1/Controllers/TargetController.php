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
        $this->endpoint = env("SCANNER_URL");
    }

    public function browse()
    {
        $target = $this->service->browse();
        return $this->presenter->renderPaginator($target);
    }

    public function read($id)
    {
        $target = $this->service->read($id);
        return $this->presenter->render($target);
    }

    public function add(Request $request)
    {
        $this->validate($request, [
             'url' => 'required',
             'email' => 'required|email'
        ]);
        $data = $this->getDetailUrl($request->url, $request->email);
        if ($data['message'] == 'success') {
            $target = $this->service->add($data);
            return $this->presenter->render($target, 200, [
                  'Content-Type' => 'application/vnd.api+json',
                  'Accept' => 'application/vnd.api+json'
             ]);
        } else {
            return response()->json([
                  'message' => 'not_saved'
             ]);
        }
    }

    public function addToScanner($url)
    {
        $site = $this->endpoint."/scanners";
        $client = $this->client->request('POST', $site, [
            'json' => [
                 'url' => "http://".$url
            ]
          ])->getBody();
        $result = json_decode($client);
        return $result->id;
    }

    public function show(Request $request)
    {
        $this->validate($request, [
              'email' => 'required'
         ]);

        $data = $this->service->showByEmail($request->email);
        return $this->presenter->renderPaginator($data);
    }

    private function getDetailUrl($url, $email)
    {
        $site = 'http://ip-api.com/json/'.$url;
        $client = json_decode($this->client->request('GET', $site)->getBody());
        if ($client->status == 'success') {
            $data = [
                'message' => 'success',
                'email' => $email,
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
                'tokenSite' => str_random(50),
                'scanner_id' => $this->addToScanner($url)
            ];
        } else {
            $data = [
                'message' => 'failed',
            ];
        }
        return $data;
    }

    protected function checkSite($url, $token)
    {
        $site = $url.'/'.$token;
        $client = $this->client->request('GET', $site)->getBody();
        return $client;
    }

    protected function updateStatus($id)
    {
        $data = [
              'status' => 'verified'
         ];
        $target = $this->service->update($id, $data);
    }

    public function verified($id)
    {
        $target = $this->service->read($id);
        $check = $this->checkSite($target['url'], $target['tokenSite']);
        if ($target['status'] == 'verified') {
            $data = [
                   'message' => 'your site is verified'
              ];
        } else {
            if ($check == $target['tokenSite']) {
                $this->updateStatus($id);
                $data = [
                        'message' => 'verified'
                   ];
            } else {
                $data = [
                        'message' => 'Invalid'
                   ];
            }
        }
        return response()->json($data);
    }
}
