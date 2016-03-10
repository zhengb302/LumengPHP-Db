<?php

namespace LumengPHP\Db\Statement;

/**
 * DELETE 语句
 *
 * @author zhengluming <luming.zheng@baozun.cn>
 */
class DeleteStatement extends StatementBase {

    public function parse() {
        return 'DELETE FROM ' . $this->statementContext->getTableName() .
                $this->buildWhere();
    }

}
