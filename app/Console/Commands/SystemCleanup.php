<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SystemCleanup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'system:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up all dummy and testing data to prepare for production.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!$this->confirm('Are you sure you want to delete all dummy data? This action cannot be undone.', false)) {
            $this->info('Cleanup cancelled.');
            return;
        }

        $this->info('Starting system cleanup...');

        try {
            // Disable foreign key checks for truncation
            \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            $this->info('Cleaning up operational data...');
            \Illuminate\Support\Facades\DB::table('tb_logs')->truncate();
            \Illuminate\Support\Facades\DB::table('tb_perbaikans')->truncate();
            \Illuminate\Support\Facades\DB::table('tb_laporan_kerusakans')->truncate();
            \Illuminate\Support\Facades\DB::table('tb_penugasans')->truncate();

            $this->info('Cleaning up vehicle data...');
            \Illuminate\Support\Facades\DB::table('master_kends')->truncate();

            $this->info('Cleaning up dummy users...');
            // Keep admin with ID 1
            \Illuminate\Support\Facades\DB::table('users')->where('id', '>', 1)->delete();

            $this->info('Clearing system cache and sessions...');
            \Illuminate\Support\Facades\DB::table('sessions')->truncate();
            \Illuminate\Support\Facades\DB::table('cache')->truncate();

            // Re-enable foreign key checks
            \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            $this->info('System cleanup completed successfully!');
            $this->info('Main admin account (admin@polda.local) has been preserved.');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            $this->error('An error occurred during cleanup: ' . $e->getMessage());
        }
    }
}
