<?php

namespace App\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

class ApiResponse extends JsonResponse
{
    /**
     * apiResponse constructor.
     * @param $data
     * @param int $status
     * @param array $errors
     * @param array $headers
     * @param bool $json
     */
    public function __construct($data = null, int $status = 200, array $errors = [], array $headers = [], bool $json = false)
    {
        $response = [
            'data' => $data,
            'status' => $status,
            'error' => $errors
        ];
        parent::__construct($response, $status, $headers, $json);
    }
}