<?php

namespace App\Console\Commands;

use App\Models\SlotLock;
use Illuminate\Console\Command;

class ClearExpiredSlotLocks extends Command
{
    protected $signature   = 'slots:clear-expired';
    protected $description = 'Remove expired slot locks from the database';

    public function handle(): void
    {
        $deleted = SlotLock::where('expires_at', '<', now())->delete();
        $this->info("Cleared {$deleted} expired slot lock(s).");
    }
}
