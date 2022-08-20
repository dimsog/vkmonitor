<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Services\Statistics\DiffUsersReaderService;

class UsersController extends Controller
{
    public function index(DiffUsersReaderService $diffUsersReaderService, int $groupId, int $page = 1): array
    {
        $group = Group::find($groupId);
        if ($group === null) {
            return [
                'success' => false,
                'text' => 'Группа не найдена'
            ];
        }
        return [
            'success' => true,
            'data' => [
                'users' => $diffUsersReaderService->read($group, $page)
            ]
        ];
    }
}
