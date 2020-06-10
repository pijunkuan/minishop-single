<?php

namespace App\Console\Commands;

use App\MiniShop\Setting;
use Illuminate\Console\Command;
use Illuminate\Contracts\Container\BindingResolutionException;
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
     * @throws BindingResolutionException
     */
    public function handle()
    {
        $template = $this->argument('template');
        if(!is_dir("templates/{$template}")){
            $this->line('模板不存在!');
            return;
        }
        $view_path = base_path("templates/{$template}/pages");

        $assets_path = base_path("templates/{$template}/assets");
        $assets_dist = base_path("public/templates/".$template);
        $config = array();
        $config['minishop.system.theme.use'] = $template;
        $config['minishop.system.theme.path'] = $view_path;
        app()->make(Setting::class)->append($config);
        $file = new Filesystem();
        if(is_dir($assets_dist)){
            $file->deleteDirectory($assets_dist);
        }
        if(is_dir($assets_path)){
            $file->copyDirectory($assets_path,$assets_dist);
        }
        $this->line('success');
    }
}
