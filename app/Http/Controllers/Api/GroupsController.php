<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Groups\GroupsReaderService;

class GroupsController extends Controller
{
    public function index(GroupsReaderService $groupsReaderService): array
    {
        return [
            'success' => true,
            'data' => [
                'groups' => $groupsReaderService->read(auth()->id())
            ]
        ];
    }
}
