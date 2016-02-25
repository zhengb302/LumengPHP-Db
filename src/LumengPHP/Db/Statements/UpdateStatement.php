<?php

namespace LumengPHP\Db\Statements;

use \LumengPHP\Db\StatementBase;
use \LumengPHP\Db\Misc\FieldHelper;

/**
 * UPDATE 语句
 *
 * @author zhengluming <luming.zheng@baozun.cn>
 */
class UpdateStatement extends StatementBase {

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

        return 'UPDATE ' . $this->statementContext->getTableName() .
                ' SET ' . implode(', ', $setParameters) .
                $this->buildWhere();
    }

}
