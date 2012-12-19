<?php
/**
 * Author: andybaird
 * Date: 12/19/12
 */

namespace Schematik\Attribute;

use Schematik\Db\Table;
use Schematik\Db\Column\NullNotAllowedException;

class ChainExecutor {
    protected $table;
    protected $data;

    public function __construct(Table $table,$data)
    {
        $this->table = $table;
        $this->data = $data;
    }

    public function execute()
    {
        foreach ($this->data as $key => &$value) {
            $column = $this->table->findColumn($key);
            if (!$column) {
                throw new Exception('Unknown column: ' . $key);
            }
            if ($column->hasAttributes()) {
                foreach ($column->getAttributes() as $attr) {
                    $value = $attr->preInsert($value);
                }
            }
        }

        // Check for null values on un-nullable columns

        $columns = $this->table->getColumns();
        foreach ($columns as $column) {
            if (!$column->getAllowNull()) {
                if ( empty( $this->data[ $column->getName() ] ) ) {
                    $defaultValue = $column->getDefaultValue();
                    if ( $defaultValue === null ) {
                        $data[$column->getName()] = $column->getDefaultValue();
                    }
                    throw new NullNotAllowedException('Column `' . $column->getName() .'` is not nullable');

                }
            }
        }

        return $this->data;
    }
}