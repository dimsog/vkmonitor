<?php

declare(strict_types=1);

namespace App\Services\Statistics;

use App\Services\Statistics\Utils\DiffGroupUsersHandlerLog;
use Throwable;
use App\Models\Group;
use App\Models\GroupUser;
use App\Services\Telemetry\Telemetry;
use App\Services\Vk\GroupInfoFetcher;
use App\Services\Vk\GroupUsers;

/**
 * Сервис занимается поиском подписавшихся и отписавшихся пользователей
 */
class DiffGroupUsersHandler
{
    public function __construct(
        private readonly GroupInfoFetcher $groupInfoFetcher,
        private readonly GroupUsers $usersFetcher,
        private DiffGroupUsersHandlerLog $logger
    )
    {}

    public function handle(int $groupId): void
    {
        try {
            $group = Group::findOrFail($groupId);

            $this->logger->log('Загрузка информация о группе: ' . $groupId . ' (vk: ' . $group->vk_group_id . ')');
            // данные о группе из вк
            $groupInfo = $this->groupInfoFetcher->getGroupInfoById($group->vk_group_id);
            $this->logger->log('Загрузка информации о группе закончилась');
            $this->logger->log('Ссылка на группу: https://vk.com/' . $groupInfo->screenName);

            $this->logger->log('Загрузка списка пользователей');
            $users = $this->usersFetcher->fetch($groupInfo);
            $this->logger->log('Загрузка списка пользователей закончилась');

            if ($group->isNew()) {
                $this->logger->log('Группа новая');
                $this->logger->log('Инициализируем пользователей');
                $group->initUsers($users);
                $this->logger->log('Инициализация пользователей успешно завершена');
                return;
            }

            $this->logger->log('Получаем список старых пользователей у группы');
            $oldUsers = $group->userIds();
            $this->logger->log('Список старых пользователей успешно получен');

            $this->logger->log('Добавляем подписавшихся пользователей');
            $group->subscribeUsers($diffUsers = array_diff($users, $oldUsers));
            $this->logger->log('Подписалось: ' . count($diffUsers));

            $this->logger->log('Добавляем отписавшихся пользователей');
            $group->unsubscribeUsers($diffUsers = array_diff($oldUsers, $users));
            $this->logger->log('Отписалось: ' . count($diffUsers));

            $this->logger->log('Меняем список пользователей на новый');
            $group->replaceAllUsers($users);
            $this->logger->log('Список пользователей успешно изменен');

            $this->logger->sendLog();
        } catch (Throwable $e) {
            Telemetry::exception($e);
            throw $e;
        }
    }
}
