<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Services\Statistics\DiffUsersReaderService;
use App\Services\Statistics\Dto\Diff;

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
                'diff' => $diffUsersReaderService->read($group, $page)->map(fn (Diff $diff) => $diff->toArray())
            ]
        ];
    }
}
