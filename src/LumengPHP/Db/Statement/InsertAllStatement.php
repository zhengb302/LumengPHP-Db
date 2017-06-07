<?php

namespace LumengPHP\Db\Statement;

use LumengPHP\Db\Misc\FieldHelper;

/**
 * INSERT语句，批量插入
 *
 * @author zhengluming <908235332@qq.com>
 */
class InsertAllStatement extends AbstractStatement {

    /**
     * @var array 要批量插入的数据，下标数组，每个数组元素是一个关联数组，field => value 形式
     */
    private $dataArr;

    public function __construct(array $dataArr) {
        $this->dataArr = $dataArr;
    }

    public function parse() {
        $fields = $this->extractFields();

        $placeholders = [];
        foreach ($this->dataArr as $data) {
            $subPlaceholders = [];
            foreach ($data as $field => $value) {
                $placeholder = $this->makePlaceholder($field);
                $this->statementContext->addParameter($placeholder, $value);
                $subPlaceholders[] = $placeholder;
            }

            $placeholders[] = '(' . implode(', ', $subPlaceholders) . ')';
        }

        return 'INSERT INTO ' . $this->statementContext->getTableName() .
                '(' . implode(', ', $fields) . ') ' .
                'VALUES' . implode(', ', $placeholders);
    }

    private function extractFields() {
        $firstRow = $this->dataArr[0];
        $fields = [];
        foreach (array_keys($firstRow) as $field) {
            $fields[] = FieldHelper::quoteField($field);
        }

        return $fields;
    }

}
