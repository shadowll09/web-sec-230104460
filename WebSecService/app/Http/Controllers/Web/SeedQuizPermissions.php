<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Database\Seeders\QuizPermissionsSeeder;

class SeedQuizPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quiz:seed-permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed the quiz permissions into the database';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Seeding quiz permissions...');
        $seeder = new QuizPermissionsSeeder();
        $seeder->setCommand($this);
        $seeder->run();
        
        $this->info('Done!');
        
        return 0;
    }
}
