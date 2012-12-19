<?php
/**
 * Author: andybaird
 * Date: 12/18/12
 */

namespace Schematik;

use Schematik\Db\Parser;
use Schematik\Attribute\ChainExecutor;


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

        $chainExecutor = new ChainExecutor($table,$data);
        $data = $chainExecutor->execute();


        echo $this->_buildInsertQuery($tableName,$data);

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