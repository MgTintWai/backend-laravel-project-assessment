<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class RepositoryException extends Exception
{
    /**
     * HTTP status code associated with this exception.
     *
     * @var int
     */
    protected int $statusCode;

    /**
     * RepositoryException constructor.
     *
     * @param string $message
     * @param int $statusCode
     * @param \Throwable|null $previous
     */
    public function __construct(string $message = "Repository Exception", int $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR, \Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
        $this->statusCode = $statusCode;
    }

    /**
     * Get HTTP status code
     *
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Render exception as HTTP response (optional)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function render()
    {
        return response()->json([
            'success' => false,
            'message' => $this->getMessage(),
        ], $this->statusCode);
    }
}
