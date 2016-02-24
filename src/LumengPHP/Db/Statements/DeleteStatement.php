<?php

namespace LumengPHP\Db\Statements;

use \LumengPHP\Db\StatementBase;

/**
 * DELETE 语句
 *
 * @author zhengluming <luming.zheng@baozun.cn>
 */
class DeleteStatement extends StatementBase {

    public function parse() {
        return 'DELETE FROM ' . $this->statementContext->getTableName() .
                ' WHERE ' . $this->condition->parse();
    }

}
