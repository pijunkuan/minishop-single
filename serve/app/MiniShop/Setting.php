<?php


namespace App\MiniShop;


use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;

class Setting
{
    protected $files;
    protected $dist;

    public function __construct()
    {
        $this->files = new Filesystem();
        $this->dist = config('minishop.save');
    }

    public function save($param)
    {
        $data = $param;
        if ($data instanceof Request) {
            $data = $param->all();
        }
        $this->put($data);
    }

    public function append($params)
    {
        foreach ($params as $key => $param) {
            config([$key => $param]);
        }
        $this->put($this->getCanEditConfig());
    }

    public function sync()
    {
        $saveConfig = $this->get();
        $arr = array_compress($saveConfig);
        foreach ($arr as $key => $item) {
            config([$key=>$item]);
        }
    }

    public function put(array $setting): void
    {
        $this->files->put($this->dist, json_encode($setting));
    }

    public function get(): array
    {
        if (!$this->files->exists($this->dist)) {
            return [];
        }
        $jsonContent = $this->files->get($this->dist);
        $arrayContent = json_decode($jsonContent, true);

        return $arrayContent;
    }

    public function getCanEditConfig(): array
    {
        $minishop = config('minishop');
        $config = [
            'minishop' => $minishop,
        ];
        return $config;
    }
}
