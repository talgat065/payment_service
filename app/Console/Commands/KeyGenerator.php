<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class KeyGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'key:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate application key.';

    /**
     *
     */
    public function handle()
    {
        $key = \Illuminate\Support\Str::random(32);
        $path = base_path('.env');

        if (file_exists($path)) {
            file_put_contents($path, str_replace(
                'APP_KEY='.$this->laravel['config']['app.key'], 'APP_KEY='.$key, file_get_contents($path)
            ));
        }
    }
}
