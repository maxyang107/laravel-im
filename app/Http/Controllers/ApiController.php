<?php

namespace App\Http\Controllers;

class ApiController extends Controller
{
    /**
     * api格式化返回.
     * @param int $code
     * @param string $info
     * @param array $data
     * @param string $type
     * @return \Illuminate\Http\JsonResponse
     */
    protected function apiReturn(int $code = 1, string $info = '', array $data = [], string $type = 'json')
    {
        return response()->json([
            "content" => $data,
            "info"    => $info,
            "code"    => $code,
            "type"    => $type,
        ]);
    }
}
