<?php

namespace App\Console\Commands;

use App\Models\Voucher;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ExpireVouchers extends Command
{
    protected $signature   = 'vouchers:expire';
    protected $description = 'Mark vouchers whose valid_until date has passed as expired';

    public function handle(): int
    {
        $count = Voucher::where('valid_until', '<', now()->toDateString())
                        ->where('is_expired', false)
                        ->update(['is_expired' => true]);

        $this->info("Marked $count voucher(s) as expired.");
        Log::info('vouchers:expire ran', ['expired_count' => $count]);

        return Command::SUCCESS;
    }
}
