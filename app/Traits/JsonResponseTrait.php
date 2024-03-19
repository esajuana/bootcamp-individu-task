<?php

namespace App\Traits;

trait JsonResponseTrait
{

    /**
     * Standard success response for create request with data and meta.
     * @param array $data
     */
    protected function createdResponse($data)
    {
        return $this->successEnvelope(201, 'Request berhasil dibuat', $data, ['ActionAt' => now()]);
    }

    /**
     * Standard response for get resource
     * @param array $data
     */
    protected function showResponse($data)
    {
        return $this->successEnvelope(200, 'Data berhasil diambil', $data, ['ActionAt' => now(), 'TotalData' => count($data)]);
    }

    /**
     * Standard response for update request with data and meta.
     * @param array $data
     */
    protected function updatedResponse($data)
    {
        return $this->successEnvelope(200, 'Request berhasil diupdate', $data, ['ActionAt' => now()]);
    }

    /**
     * Standard response for delete request with data and meta.
     * @param array $data
     */
    protected function deletedResponse($data = null)
    {
        return $this->successEnvelope(200, 'Request berhasil dihapus', $data, ['ActionAt' => now()]);
    }

    /**
     * Standard response for restore request with data and meta.
     * @param array $data
     */
    protected function restoredResponse($data)
    {
        return $this->successEnvelope(200, 'Request berhasil direstore', $data, ['ActionAt' => now()]);
    }

    /**
     * Standard response for error
     * @param array $data
     */
    protected function errorResponse($error)
    {
        return $this->errorEnvelope(400, 'Bad request', $error, ['ActionAt' => now()]);
    }

    /**
     * standard response for not found resource.
     */
    protected function notFoundResponse()
    {
        return $this->errorEnvelope(404, 'Data tidak ditemukan', [], ['ActionAt' => now()]);
    }

    /**
     * standard response for unauthorized request.
     * @param array $error
     */
    protected function unauthorizedResponse($error)
    {
        return $this->errorEnvelope(401, 'Unauthorized', $error, ['ActionAt' => now()]);
    }

    /**
     * standard response for forbidden request.
     * @param array $error
     */
    protected function forbiddenResponse($error = null)
    {
        return $this->errorEnvelope(403, 'Forbidden', $error, ['ActionAt' => now()]);
    }

    /**
     * standard response for validation error.
     * @param array $error
     */
    protected function validationErrorResponse($error)
    {
        return $this->errorEnvelope(422, 'Validation error', $error, ['ActionAt' => now()]);
    }

    /**
     * Standard success response with data and meta.
     * @param int $status
     * @param string $message
     * @param array $data
     * @param array $meta
     */
    private function successEnvelope($status = 200, $message = 'OK', $data, $meta)
    {
        return response()->json([

            'message' => $message,
            'data' => $data,
            'meta' => $meta
        ], $status);
    }

    /**
     * Standard error response with data and meta.
     * @param int $status
     * @param string $message
     * @param array $error
     * @param array $meta
     */
    private function errorEnvelope($status = 400, $message = 'Bad request', $error, $meta)
    {
        return response()->json([

            'message' => $message,
            'error' => $error,
            'meta' => $meta
        ], $status);
    }
}