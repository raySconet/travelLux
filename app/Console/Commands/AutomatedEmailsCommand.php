<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AutomatedEmailService;

class AutomatedEmailsCommand extends Command
{
    protected $signature = 'reminder:automated-emails';

    protected $description = 'Send all automated customer emails';

    public function handle(AutomatedEmailService $service)
    {
        $service->process();

        $this->info('Automated emails finished.');
    }
}