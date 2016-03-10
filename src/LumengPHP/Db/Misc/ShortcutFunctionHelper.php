<?php

namespace LumengPHP\Db\Misc;

/**
 * 快捷函数加载帮助程序
 *
 * @author Lumeng <zhengb302@163.com>
 */
class ShortcutFunctionHelper {

    /**
     * 返回快捷函数文件路径
     */
    public static function getPath() {
        return dirname(__DIR__) . '/shortcut_functions.php';
    }

}
