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

use App;
use Artisan;
use Illuminate\Console\Command;
use Illuminate\Contracts\Cache\Factory as CacheFactory;

class Update extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cscms:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update package views and assets and clear the cache';

    /**
     * Create a new command instance.
     */
    public function __construct(CacheFactory $cache)
    {
        parent::__construct();
        $this->cache = $cache;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (App::environment(['local', 'staging'])) {
            $this->info('Publishing assets and views');
            Artisan::call('vendor:publish', ['--tag' => 'public', '--force' => true]);
            Artisan::call('vendor:publish', ['--tag' => 'views', '--force' => true]);
        }
        $this->cache->flush();
        $this->info('Cache cleared succesfully');
    }
}
