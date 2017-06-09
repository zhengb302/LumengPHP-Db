<?php

namespace LumengPHP\Db\Statement;

use LumengPHP\Db\Exception\ForbiddenOperationException;

/**
 * DELETE 语句
 *
 * @author zhengluming <908235332@qq.com>
 */
class DeleteStatement extends AbstractStatement {

    public function parse() {
        $where = $this->buildWhere();
        if (empty($where)) {
            throw new ForbiddenOperationException("禁止在不带任何过滤条件的情况下删除数据");
        }

        return 'DELETE FROM ' . $this->statementContext->getTableName() . $where;
    }

}
