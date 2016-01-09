<?php

namespace MatthC\Privileges\Commands;

use Illuminate\Console\Command;

class PrivilegesSeederCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'privileges:db:seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed the roles an permissions table with the provided config';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->call('db:seed', ['--class' => 'MatthC\Privileges\Seeds\DatabaseSeeder']);
    }
}