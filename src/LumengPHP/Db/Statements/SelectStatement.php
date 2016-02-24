<?php

namespace LumengPHP\Db\Statements;

use \LumengPHP\Db\StatementBase;

/**
 * SELECT 语句
 *
 * @author zhengluming <luming.zheng@baozun.cn>
 */
class SelectStatement extends StatementBase {

    public function parse() {
        $sql = 'SELECT ' . $this->statementContext->getFields() .
                ' FROM ' . $this->statementContext->getTableName() .
                ' WHERE ' . $this->condition->parse();

        $limit = $this->statementContext->getLimit();
        if (!is_null($limit)) {
            $sql = "{$sql} LIMIT {$limit}";
        }

        return $sql;
    }

}
