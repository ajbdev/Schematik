<?php
/**
 * Author: andybaird
 * Date: 12/18/12
 */

namespace Schematik;

use Schematik\Db\Parser;
use Schematik\Db\Column\NullNotAllowedException;

class Db {
    protected $db;
    protected $parser;
    protected $production;

    public function __construct(\PDO $db, $debug = true) {
        $this->debug = $debug;
        $this->db = $db;
        $this->parser = new Parser($db);

        /* @todo: Load object graph from cached version if debug mode is off */
        $this->parser->parse();
    }

    public function insert($tableName,$data)
    {
        $table = $this->parser->getTable($tableName);
        foreach ($data as $key => &$value) {
            $column = $table->findColumn($key);
            if (!$column) {
                throw new Exception('Unknown column: ' . $key);
            }
            if ($column->hasAttributes()) {
                foreach ($column->getAttributes() as $attr) {
                    $value = $attr->preInsert($value);
                }
            }
        }
        echo $this->_buildInsertQuery($tableName,$data);

        // Check for null values on un-nullable columns
        $columns = $table->getColumns();
        foreach ($columns as $column) {
            if (!$column->getAllowNull()) {
                if ( empty( $data[$column->getName()] ) ) {
                    $defaultValue = $column->getDefaultValue();
                    if ( $defaultValue === null ) {
                        $data[$column->getName()] = $column->getDefaultValue();
                    }
                    throw new NullNotAllowedException('Column `' . $column->getName() .'` is not nullable');

                }
            }
        }
    }

    protected function _buildInsertQuery($tableName,$data)
    {
        $cols = array_keys($data);
        $vals = array_values($data);
        array_walk($vals, function($val) {
            return '\'' . $val . '\'';
        });
        $sql = 'insert into ' . $tableName;
        $sql .= ' (' . implode(',',$cols) . ') ';
        $sql .= ' values (' . implode(',',$vals) . ')';


        return $sql;

    }
}