<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ConnectDatabase extends Command
{
    protected $signature = 'db:connect';
    protected $description = 'Connect to TiDB Cloud database';

    public function handle()
    {
        $command = sprintf(
            'mysql --host=%s --port=%s --user=%s --password=%s --database=%s --ssl-mode=VERIFY_IDENTITY --ssl-ca=%s',
            config('database.connections.mysql.host'),
            config('database.connections.mysql.port'),
            config('database.connections.mysql.username'),
            config('database.connections.mysql.password'),
            config('database.connections.mysql.database'),
            env('MYSQL_ATTR_SSL_CA')
        );

        $this->info('Connecting to database...');
        $this->info('Command: ' . $command);

        passthru($command);
    }
}
