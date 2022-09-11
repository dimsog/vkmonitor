<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddGroupRequest;
use App\Services\Groups\AddGroupService;
use App\Services\Vk\GroupInfoFetcher;
use VK\Exceptions\Api\VKApiAuthException;
use VK\Exceptions\VKApiException;
use VK\Exceptions\VKClientException;

class GroupController extends Controller
{
    public function __construct(
        private readonly GroupInfoFetcher $groupInfoFetcher,
    )
    {}

    public function create(AddGroupRequest $request, AddGroupService $addGroupService): array
    {
        try {
            $data = $request->validated();
            $group = $addGroupService->add(
                $data['vk_group_link'],
                auth()->id()
            );
            return [
                'success' => true,
                'data' => [
                    'group' => $group
                ]
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'text' => $e->getMessage()
            ];
        }
    }

    public function read(string $vkGroupId): array
    {
        try {
            $vkGroup = $this->groupInfoFetcher->getGroupInfoById($vkGroupId);
            return [
                'success' => true,
                'data' => [
                    'group' => $vkGroup
                ]
            ];
        } catch (VKApiException) {
            return [
                'success' => false,
                'text' => 'Группа не найдена.'
            ];
        } catch (VKClientException) {
            return [
                'success' => false,
                'text' => 'При запросе произошла ошибка. Попробуйте позже.'
            ];
        }
    }
}
