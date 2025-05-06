<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DefaultResource extends JsonResource
{

    protected $status;
    protected $statusCode;
    protected $message;

    public function __construct($resource, $status = true, $statusCode = 200, $message = 'OK')
    {
        parent::__construct($resource);

        $this->status = $status;
        $this->statusCode = $statusCode;
        $this->message = $message;
    }
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'status' => $this->status,
            'status_code' => $this->statusCode,
            'message' => $this->message,
            'data' => $this->status ? $this->resource : (object)[],
        ];
    }
}
