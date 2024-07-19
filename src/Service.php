<?php

declare (strict_types = 1);

namespace Laket\Admin\FlashDisable;

use think\facade\Console;

use Laket\Admin\Facade\Flash;
use Laket\Admin\Flash\Service as BaseService;

// 文件夹
use Laket\Admin\FlashDisable\Middleware;

class Service extends BaseService
{
    /**
     * composer
     */
    public $composer = __DIR__ . '/../composer.json';
    
    /**
     * 包名
     */
    protected $pkg = 'laket/laket-flash-disable';
    
    /**
     * 启动
     */
    public function boot()
    {
        Flash::extend('laket/laket-flash-disable', __CLASS__);
    }
    
    /**
     * 在插件安装、插件卸载等操作时有效
     */
    public function action()
    {
        register_install_hook($this->pkg, [$this, 'install']);
    }

    /**
     * 开始，只有启用后加载
     */
    public function start()
    {
        // 配置
        $this->mergeConfigFrom(__DIR__ . '/../resources/config/flash_disable.php', 'flash_disable');
        
        // 检测禁用
        $this->app->middleware->add(Middleware\CheckDisable::class, 'route');
    }
    
    /**
     * 安装后
     */
    public function install()
    {
        // 推送配置文件
        $this->publishes([
            __DIR__ . '/../resources/config/flash_disable.php' => config_path() . 'flash_disable.php',
        ], 'laket-flash-disable-config');
        
        Console::call('laket-admin:publish', [
            '--tag=laket-flash-disable-config',
        ]);
    }
}
