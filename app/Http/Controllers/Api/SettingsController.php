<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Settings\SettingsReader;
use App\Services\Settings\SettingsStore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    public function index(): array
    {
        return [
            'success' => true,
            'data' => [
                'settings' => SettingsReader::read()
            ]
        ];
    }

    public function store(SettingsStore $settingsStore, Request $request)
    {
        $validator = Validator::make(json_decode($request->post('settings'), true), [
            'tokens' => 'required|array',
            'tokens.*.id' => 'required|integer',
            'tokens.*.client_id' => 'required|integer',
            'tokens.*.access_token' => 'required|string'
        ], [
            'tokens' => 'Токены не переданы',
            'tokens.*.client_id' => 'Для одного из токенов не заполнен client id',
            'tokens.*.access_token' => 'Для одного из токенов не заполнен access_token'
        ]);

        $settingsStore->store($validator->validated());

        return [
            'success' => true
        ];
    }
}
