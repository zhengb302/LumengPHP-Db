<?php

namespace LumengPHP\Db\Join;

/**
 * Join子句(单个)
 *
 * @author Lumeng <zhengb302@163.com>
 */
class Join {

    const INNER_JOIN = 0;
    const LEFT_JOIN = 1;
    const RIGHT_JOIN = 2;

    /**
     * @var string 真实表名，包括表前缀
     */
    private $table;

    /**
     * @var string 表别名
     */
    private $tableAlias;

    /**
     * @var string 连接条件
     */
    private $on;

    /**
     * @var int 连接类型：inner join、left join、right join
     */
    private $type;

    public function __construct($table, $tableAlias, $on) {
        $this->table = $table;
        $this->tableAlias = $tableAlias;
        $this->on = $on;

        $this->type = self::INNER_JOIN;
    }

    public function setType($type) {
        $this->type = $type;
    }

    public function parse() {
        $join = '';
        switch ($this->type) {
            case self::LEFT_JOIN:
                $join = 'LEFT JOIN';
                break;
            case self::RIGHT_JOIN:
                $join = 'RIGHT JOIN';
                break;
            case self::INNER_JOIN:
            default:
                $join = 'INNER JOIN';
        }

        $alias = $this->tableAlias ? " AS {$this->tableAlias}" : '';

        return " {$join} {$this->table}{$alias} ON {$this->on}";
    }

}
