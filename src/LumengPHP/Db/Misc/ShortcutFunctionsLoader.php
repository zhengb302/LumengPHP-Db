<?php

namespace LumengPHP\Db\Misc;

/**
 * 快捷函数加载器
 *
 * @author Lumeng <zhengb302@163.com>
 */
class ShortcutFunctionsLoader {

    /**
     * 加载快捷函数
     */
    public static function loadShortcutFunctions() {
        require(dirname(__DIR__) . '/shortcut_functions.php');
    }

}
