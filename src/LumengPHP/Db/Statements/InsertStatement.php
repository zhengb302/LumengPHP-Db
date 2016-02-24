<?php

namespace LumengPHP\Db\Statements;

use \LumengPHP\Db\StatementBase;

/**
 * INSERT 语句
 *
 * @author zhengluming <luming.zheng@baozun.cn>
 */
class InsertStatement extends StatementBase {

    /**
     * @var array 要插入的数据，field => value 形式
     */
    private $data;

    public function __construct($data) {
        $this->data = $data;
    }

    public function parse() {
        $fields = array();
        $placeholders = array();
        foreach ($this->data as $field => $value) {
            $placeholder = $this->makePlaceholder($field);
            $this->statementContext->addParameter($placeholder, $value);

            $fields[] = $field;
            $placeholders[] = $placeholder;
        }

        return 'INSERT INTO ' . $this->statementContext->getTableName() .
                '(' . implode(', ', $fields) . ') ' .
                'VALUES(' . implode(', ', $placeholders) . ')';
    }

}
