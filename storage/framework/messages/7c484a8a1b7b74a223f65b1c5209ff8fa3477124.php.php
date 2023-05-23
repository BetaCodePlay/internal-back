<?php


namespace App\Core\Commands;

use Illuminate\Console\Command;

/**
 * Class PermissionsStorage
 *
 * @package App\Core\Commands
 */
class PermissionsStorage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions:file-storage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Permissions for file storage';

    /**
     *
     */
    public function handle()
    {
        exec('sudo chmod -R 777 storage/');
    }
}
