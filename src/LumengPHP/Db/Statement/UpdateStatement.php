<?php

namespace LumengPHP\Db\Statement;

use LumengPHP\Db\Misc\FieldHelper;
use LumengPHP\Db\Exceptions\ForbiddenDatabaseOperationException;

/**
 * UPDATE 语句
 *
 * @author zhengluming <908235332@qq.com>
 */
class UpdateStatement extends AbstractStatement {

    /**
     * @var array 要保存的数据，field => value 形式
     */
    private $data;

    public function __construct($data) {
        $this->data = $data;
    }

    public function parse() {
        $setParameters = array();
        foreach ($this->data as $field => $value) {
            $placeholder = $this->makePlaceholder($field);
            $this->statementContext->addParameter($placeholder, $value);

            $quotedField = FieldHelper::quoteField($field);
            $setParameters[] = "{$quotedField} = {$placeholder}";
        }

        $where = $this->buildWhere();
        if (empty($where)) {
            $errMsg = 'update without any conditions is forbidden.';
            throw new ForbiddenDatabaseOperationException($errMsg);
        }

        return 'UPDATE ' . $this->statementContext->getTableName() .
                ' SET ' . implode(', ', $setParameters) .
                $where;
    }

}
