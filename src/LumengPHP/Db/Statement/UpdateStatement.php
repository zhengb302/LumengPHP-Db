<?php

namespace LumengPHP\Db\Statement;

use LumengPHP\Db\Misc\FieldHelper;
use LumengPHP\Db\Exception\ForbiddenOperationException;
use LumengPHP\Db\Exception\SqlException;

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
        $setParameters = [];
        foreach ($this->data as $field => $value) {
            $setParameters[] = $this->buildSetParameter($field, $value);
        }

        $where = $this->buildWhere();
        if (empty($where)) {
            $errMsg = 'update without any conditions is forbidden.';
            throw new ForbiddenOperationException($errMsg);
        }

        return 'UPDATE ' . $this->statementContext->getTableName() .
                ' SET ' . implode(', ', $setParameters) .
                $where;
    }

    private function buildSetParameter($field, $value) {
        //如果字段值是表达式
        if (is_array($value)) {
            list($op, $operand) = $value;
            $this->verifyOp($op);

            $quotedField = FieldHelper::quoteField($field);
            return "{$quotedField} = {$operand}";
        }

        $placeholder = $this->makePlaceholder($field);
        $this->statementContext->addParameter($placeholder, $value);

        $quotedField = FieldHelper::quoteField($field);
        return "{$quotedField} = {$placeholder}";
    }

    private function verifyOp($op) {
        //目前只支持exp操作
        if ($op != 'exp') {
            throw new SqlException("UPDATE语句不支持此操作：{$op}");
        }
    }

}
