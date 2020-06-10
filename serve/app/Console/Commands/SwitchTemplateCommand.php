<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class SwitchTemplateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'template:switch {template}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '切换模板';

    /**
     * Create a new command instance.
     *
     * @return void
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
        $template = $this->argument('template');

        $path = base_path("templates/{$template}/asset");
        if(!is_dir($path)){
            $this->line('无需操作');
            return;
        }
        $dist = base_path("public/templates/{$template}");
        if(!is_dir($dist)){
            $file = new Filesystem();
            $file->link($path,$dist);
        }
        $this->line('success');
    }
}
