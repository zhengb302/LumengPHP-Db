<?php

namespace LumengPHP\Db\Statements;

use \LumengPHP\Db\StatementBase;
use \LumengPHP\Db\Misc\FieldHelper;

/**
 * SELECT 语句
 *
 * @author zhengluming <luming.zheng@baozun.cn>
 */
class SelectStatement extends StatementBase {

    public function parse() {
        $fields = $this->statementContext->getFields();
        $sql = 'SELECT ' . FieldHelper::quoteFields($fields) .
                ' FROM ' . $this->statementContext->getTableName() .
                $this->buildWhere();

        $limit = $this->statementContext->getLimit();
        if (!is_null($limit)) {
            $sql = "{$sql} LIMIT {$limit}";
        }

        return $sql;
    }

}
