<?php

namespace LumengPHP\Db\Statement;

use LumengPHP\Db\Misc\FieldHelper;

/**
 * SELECT 语句
 *
 * @author zhengluming <luming.zheng@baozun.cn>
 */
class SelectStatement extends AbstractStatement {

    public function parse() {
        $fields = $this->statementContext->getFields();
        $sql = 'SELECT ' . FieldHelper::quoteFields($fields) .
                ' FROM ' . $this->statementContext->getTableName() .
                $this->statementContext->getJoinClause()->parse() .
                $this->buildWhere();

        $orderBy = $this->statementContext->getOrderBy();
        if (!is_null($orderBy)) {
            $sql = "{$sql} ORDER BY {$orderBy}";
        }

        $limit = $this->statementContext->getLimit();
        if (!is_null($limit)) {
            $sql = "{$sql} LIMIT {$limit}";
        }

        return $sql;
    }

}
