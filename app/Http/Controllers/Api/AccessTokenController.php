<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Vk\Utils\TokenChecker;
use Illuminate\Http\Request;

class AccessTokenController extends Controller
{
    public function check(TokenChecker $checker, Request $request): array
    {
        if ($checker->check($request->post('token'))) {
            return [
                'success' => true
            ];
        }
        return [
            'success' => false,
            'text' => 'Токен не работает'
        ];
    }
}
