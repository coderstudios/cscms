<?php
/**
 * Part of the CSCMS package by Coder Studios.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the terms of the MIT license https://opensource.org/licenses/MIT
 *
 * @version    1.0.0
 *
 * @author     Coder Studios Ltd
 * @license    MIT https://opensource.org/licenses/MIT
 * @copyright  (c) 2022, Coder Studios Ltd
 *
 * @see       https://www.coderstudios.com
 */

namespace CoderStudios\CsCms\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class DBBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup the database';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $process = new Process('');
        $default_config = sprintf('database.connections.%s', config('database.default'));
        $db = config($default_config);
        if (!is_dir(config('cscms.coderstudios.backup_dir'))) {
            mkdir(config('cscms.coderstudios.backup_dir'));
        }
        $path = config('cscms.coderstudios.backup_dir').'/'.$db['database'].'-'.date('Y-m-d-h-i').'.gz';

        $command = sprintf(
            'mysqldump --host=%s --port=%s --user=%s --password=%s --opt %s | gzip -c | cat > %s',
            $db['host'],
            $db['port'],
            $db['username'],
            $db['password'],
            $db['database'],
            $path
        );
        $process->setCommandLine($command);
        $process->run();
    }
}
