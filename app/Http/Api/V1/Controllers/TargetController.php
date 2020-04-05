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

    public function getServerInfo(Request $request)
    {
        $this->validate($request, [
            'address' => 'required'
        ]);
        $result = [];
        foreach ($request->address as $key => $value) {
            $site = 'https://api.indoxploit.or.id/domain/'.$value["link"];
            $client = json_decode($this->client->request('GET', $site)->getBody());
            $result[] = [
                "domain" => $value["link"],
                "info" => $client->data->geolocation
            ];
        }
        return response()->json($result);
    }

    public function updateScannerId($id, Request $request)
    {
        $this->validate($request, [
            "scanner_id" => "required"
        ]);

        $target = $this->service->update($id, [
            "scanner_id" => $request->scanner_id,
            "launched" => "1",
        ]);

        if ($target) {
            return response()->json([
                "status" => 1
            ]);
        }
        return response()->json([
            "status" => 1
        ]);
    }

    public function browse(Request $request)
    {
        if (!$request->exists('filter')) {
            $target = $this->service->browse();
            return $this->presenter->renderPaginator($target);
        }
        if ($request->exists('filter')) {
            $target = $this->service->filter($request);
            return $this->presenter->renderPaginator($target);
        }
    }

    public function read($id)
    {
        $target = $this->service->getByScannerId($id);
        if ($target) {
            return $this->presenter->render($target);
        }
        return $this->notFountSetValue();
    }

    public function add(Request $request)
    {
        $this->validate($request, [
             'url' => 'required',
             'query' => 'required'
        ]);
        if ($this->service->checkExist(["email" => $request->email, "url" => $request->url])->count()) {
            return response()->json([
                "status" => "exist",
                "message" => "Domain Sudah Ada!"
            ]);
        }
        $target = $this->service->add($request->all());
        if ($target) {
            return $this->presenter->render($target, 200, [
                'Content-Type' => 'application/vnd.api+json',
                'Accept' => 'application/vnd.api+json'
            ]);
        }
        return response()->json([
            'message' => 'not_saved'
       ]);
    }

    public function delete($id)
    {
        $deleted = $this->service->delete($id);
        if ($deleted) {
            return response()->json([
                'meta' => [
                    'deleted_count' => $deleted,
                ]
            ], 204);
        }
        return $this->notFountSetValue();
    }

    public function show(Request $request)
    {
        $this->validate($request, [
              'email' => 'required'
         ]);

        $data = $this->service->showByEmail($request->email);
        return $this->presenter->renderPaginator($data);
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

    public function getGeo()
    {
        $geos = $this->service->getGeoLocation();
        $data = [];
        foreach ($geos as $key => $value) {
            $data[] = [$value->country, $value->total];
        }
        return response()->json($data);
    }

    private function notFountSetValue()
    {
        return response()->json([
            'meta' => [
                'status' => "Not Found",
            ]
        ], 404);
    }
}
