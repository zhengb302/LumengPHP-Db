<?php

namespace LumengPHP\Db\Statement;

use LumengPHP\Db\Misc\FieldHelper;

/**
 * SELECT 语句
 *
 * @author zhengluming <908235332@qq.com>
 */
class SelectStatement extends AbstractStatement {

    public function parse() {
        $fields = $this->statementContext->getFields();
        $sql = 'SELECT ' . FieldHelper::quoteFields($fields) .
                ' FROM ' . $this->statementContext->getTableName() .
                $this->statementContext->getJoinClause()->parse() .
                $this->buildWhere();

        $groupBy = $this->statementContext->getGroupBy();
        if (!is_null($groupBy)) {
            $sql = "{$sql} GROUP BY {$groupBy}";
        }

        $having = $this->statementContext->getHaving();
        if (!is_null($having)) {
            $sql = "{$sql} HAVING {$having}";
        }

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
