<?php

namespace App\Managers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;

class CommonResponseManager
{
    public const STATUS = 200;
    public $data = null;
    public bool $success = true;
    public string $message = '';
    public array $meta = [];
    public array $links = [];
    public JsonResponse $response;

    final public function commonApiResponse():void
    {
        $this->response = response()->json([
            'success' => $this->success,
            'message' => $this->message,
            'data'    => $this->data,
            'meta'    => $this->meta,
            'links'   => $this->links,
        ], self::STATUS);
    }
}
