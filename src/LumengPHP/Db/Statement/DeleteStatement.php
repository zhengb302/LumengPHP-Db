<?php

namespace LumengPHP\Db\Statement;

use LumengPHP\Db\Exceptions\ForbiddenDatabaseOperationException;

/**
 * DELETE 语句
 *
 * @author zhengluming <908235332@qq.com>
 */
class DeleteStatement extends AbstractStatement {

    public function parse() {
        $where = $this->buildWhere();
        if (empty($where)) {
            $errMsg = 'delete without any conditions is forbidden.';
            throw new ForbiddenDatabaseOperationException($errMsg);
        }

        return 'DELETE FROM ' . $this->statementContext->getTableName() .
                $where;
    }

}
