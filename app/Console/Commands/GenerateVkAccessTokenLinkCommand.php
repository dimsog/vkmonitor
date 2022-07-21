<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\Vk\Utils\GenerateVkAccessTokenLink;
use Illuminate\Console\Command;

class GenerateVkAccessTokenLinkCommand extends Command
{
    protected $signature = 'vk:token';

    protected $description = 'Команда создает ссылку на получение access token';


    public function handle(GenerateVkAccessTokenLink $generateVkAccessTokenLink): int
    {
        $this->output->writeln($generateVkAccessTokenLink->generate());
        return 0;
    }
}
