<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Otp;

class OtpDelete extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'otp:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete expired otp';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $now = date('Y-m-d H:i:s');
        $otps = OTP::where('expiry', '<', $now)->orWhere('used', 1);
        if ($otps->count() > 0) {
            $otps->delete();
        }
    }
}