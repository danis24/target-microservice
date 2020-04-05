<?php

namespace App\Presenters;

use Illuminate\Support\Collection;
use Uuid;
use Illuminate\Pagination\AbstractPaginator;
use App\Presenters;

class JsonApiPresenter
{
    private function transform(JsonApiPresenterable $transformer)
    {
        $transformed = $transformer->transform();
        $data = [
            'id' => $transformed['id'],
            'type' => $transformer->entityType(),
        ];
        unset($transformed['id']);
        $data['attributes'] = $transformed;
        return $data;
    }
    /**
    * Tranform Colletion in UUID
     * @param Illuminate\Support\Collection $transformer
     */
    public function transformCollection(Collection $transformers)
    {
        return $transformers->map(function (JsonApiPresenterable $transformer) {
            return $this->transform($transformer);
        });
    }

    /**
    * Render
     * @param Uuid $Users
     * @return \Illuminate\Http\JsonResponse
     */
    public function render(JsonApiPresenterable $transformer, $statusCode = 200, $headers = [])
    {
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
    public function renderCollection(Collection $transformer, $statusCode = 200, $headers = [])
    {
        $transformer = $this->transformCollection($transformer);
        return response()->json($transformer, $statusCode, $headers);
    }

    /**
    *
     * @param Illuminate\Pagination\AbstractPaginator
     * @return \Illuminate\Http\JsonResponse
     */
    public function renderPaginator(AbstractPaginator $paginator, $statusCode = 200, $headers = [])
    {
        $collection = $this->transformCollection($paginator->getCollection());
        $nextUrl = null;

        if ($paginator->hasMorePages()) {
            $nextUrl = $paginator->url($paginator->currentPage() + 1);
        }

        $response = [
            'meta' => [
                'total' => $paginator->total(),
                'count' => $paginator->count(),
                'total_page' => $paginator->lastPage()
            ],
            'data' => $collection,
            'links' => [
                'self' => $paginator->url($paginator->currentPage()),
                'first' =>  $paginator->url(0),
                'prev' => $paginator->previousPageUrl(),
                'next' => $nextUrl,
            ]
        ];

        return response()->json($response, $statusCode, $headers);
    }
}
