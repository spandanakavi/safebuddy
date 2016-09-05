<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller as BaseController;

class Controller extends BaseController
{
    protected function error($code, $description)
    {
        return response()->json(['code' => $code, "message" => $description], $code);
    }
}
