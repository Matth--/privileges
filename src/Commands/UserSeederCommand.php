<?php

namespace MatthC\Privileges\Commands;


use Illuminate\Console\Command;

class UserSeederCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'privileges:db:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed the database with some users';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->call('db:seed', ['--class' => 'MatthC\Privileges\Seeds\UsersAndUserRolesTableSeeder']);
    }
}