<?php

declare (strict_types = 1);

namespace Laket\Admin\FlashDisable\Middleware;

use Closure;
use think\App;

use Laket\Admin\Http\Request;
use Laket\Admin\Http\Traits\Jump as JumpTrait;

/**
 * 检测禁用
 *
 * @create 2021-4-3
 * @author deatil
 */
class CheckDisable
{
    use JumpTrait;
    
    /** @var App */
    protected $app;
    
    public function __construct(App $app)
    {
        $this->app  = $app;
    }
    
    /**
     * @var
     */
    public function handle($request, Closure $next)
    {
        $this->check();
        
        return $next($request);
    }
    
    /**
     * 检测禁用
     */
    protected function check()
    {
        $config = config('flash_disable');
        
        // 闪存插件包名
        $name = request()->param('name');
        
        // 安装
        if (Request::matchPath('post:admin.flash.install')) {
            if (in_array($name, $config['install'])) {
                $this->error('当前闪存插件('.$name.')禁止安装！');
            }
        }
        
        // 卸载
        if (Request::matchPath('post:admin.flash.uninstall')) {
            if (in_array($name, $config['uninstall'])) {
                $this->error('当前闪存插件('.$name.')禁止卸载！');
            }
        }
        
        // 更新
        if (Request::matchPath('post:admin.flash.upgrade')) {
            if (in_array($name, $config['upgrade'])) {
                $this->error('当前闪存插件('.$name.')禁止更新！');
            }
        }
        
        // 启用
        if (Request::matchPath('post:admin.flash.enable')) {
            if (in_array($name, $config['enable'])) {
                $this->error('当前闪存插件('.$name.')禁止启用！');
            }
        }
        
        // 禁用
        if (Request::matchPath('post:admin.flash.disable')) {
            if (in_array($name, $config['disable'])) {
                $this->error('当前闪存插件('.$name.')禁止禁用！');
            }
        }
        
        // 排序
        if (Request::matchPath('post:admin.flash.listorder')) {
            if (in_array($name, $config['listorder'])) {
                $this->error('当前闪存插件('.$name.')禁止更改排序！');
            }
        }
        
        // 设置
        if (Request::matchPath('post:admin.flash.setting')) {
            if (in_array($name, $config['setting'])) {
                $this->error('当前闪存插件('.$name.')禁止设置！');
            }
        }
    }
}
