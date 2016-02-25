<?php

namespace LumengPHP\Db\Misc;

/**
 * SQL语句字段帮助程序
 *
 * @author Lumeng <zhengb302@163.com>
 */
class FieldHelper {

    /**
     * 给单个字段打引号
     * @param string $field 字段名
     * @return string 如果字段带英文句点，则不打引号直接返回原值，
     * 否则返回打引号之后的字段名
     */
    public static function quoteField($field) {
        if (trim($field) == '*') {
            return $field;
        }

        return strpos($field, '.') !== false ? $field : "`{$field}`";
    }

    /**
     * 给多个字段打引号
     * @param string $fields 以英文逗号分割的字段列表
     * @return string 以英文逗号分割的已打过引号的字段列表
     */
    public static function quoteFields($fields) {
        $fieldArr = explode(',', $fields);
        $resultFieldArr = array();
        foreach ($fieldArr as $field) {
            $resultFieldArr[] = self::quoteField(trim($field));
        }

        return implode(', ', $resultFieldArr);
    }

    /**
     * 构造placeholder使用的字段名
     * @param string $field
     * @return string 
     */
    public static function makePlaceholderField($field) {
        if (strpos($field, '.') !== false) {
            return str_replace('.', '_', $field);
        } else {
            return $field;
        }
    }

}
