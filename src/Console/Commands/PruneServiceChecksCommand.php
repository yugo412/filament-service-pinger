<?php

namespace Yugo\FilamentServicePinger\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Yugo\FilamentServicePinger\Support\ModelResolver;

class PruneServiceChecksCommand extends Command
{
    protected $signature = 'service-pinger:prune
        {days : Delete checks older than this many days}';

    protected $description = 'Prune old service check logs';

    public function handle(): int
    {
        $days = (int) $this->argument('days');

        if ($days < 1) {
            $this->error('The number of days must be at least 1.');

            return self::FAILURE;
        }

        $cutoff = Carbon::now()->subDays($days);
        $checkModel = ModelResolver::check();
        $deleted = $checkModel::query()
            ->where('checked_at', '<', $cutoff)
            ->delete();

        $this->info(sprintf('Pruned %d service check log(s) older than %d day(s).', $deleted, $days));

        return self::SUCCESS;
    }
}
