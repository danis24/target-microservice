<?php

namespace App\Presenters;

use Illuminate\Support\Collection;
use Uuid;
use Illuminate\Pagination\AbstractPaginator;
use App\Presenters;


class JsonApiPresenter {

    private function transform(JsonApiPresenterable $transformer) {
        $transformed = $transformer->transform();
        $data = [
            'id' => $transformed['id'],
            'type' => $transformer->getTable(),
        ];
        unset($transformed['id']);
        $data['attributes'] = $transformed;
        return $data;
    }
    /**
    * Tranform Colletion in UUID
     * @param Illuminate\Support\Collection $transformer
     */
    public function transformCollection(Collection $transformers) {
        return $transformers->map(function(JsonApiPresenterable $transformer){
            return $this->transform($transformer);
        });
    }

    /**
    * Render
     * @param Uuid $Users
     * @return \Illuminate\Http\JsonResponse
     */
    public function render(JsonApiPresenterable $transformer, $statusCode = 200, $headers = []) {

        $response = [
            'data' => $this->transform($transformer),
        ];
        return response()->json($response, $statusCode, $headers);
    }

    /**
    * renderCollection
     * @param Illuminate\Support\Collection $Users
     * @return \Illuminate\Http\JsonResponse
     */
    public function renderCollection(Collection $transformer, $statusCode = 200, $headers = []) {
        $transformer = $this->transformCollection($transformer);
        return response()->json($transformer);
    }

    /**
    *
     * @param Illuminate\Pagination\AbstractPaginator
     * @return \Illuminate\Http\JsonResponse
     */
    public function renderPaginator(AbstractPaginator $paginator, $statusCode = 200, $headers = []) {
        $collection = $this->transformCollection($paginator->getCollection());
        $paginator->setCollection($collection);
        return response()->json($paginator);
    }
}
