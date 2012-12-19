<?php
/**
 * Author: andybaird
 * Date: 12/18/12
 */
namespace Schematik\Db;

use Schematik\Db\Table;
use Schematik\Db\Column\Int;
use Schematik\Db\Column\Varchar;
use Schematik\Db\Column\Enum;

class Parser {
    /**
     * @var PDO
     */
    protected $db;

    protected $tables;

    /**
     * @param \PDO $db
     */
    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    public function parse()
    {

        $stmt = $this->db->query('show tables');
        $tables = $stmt->fetchAll( \PDO::FETCH_COLUMN );
        foreach ($tables as $tableData) {
            $table = new Table($tableData);
            $this->parseTable($table);
            $this->tables[] = $table;
        }
    }

    /**
     * @return array
     */
    public function getTables() {
        return $this->tables;
    }

    /**
     * @param $name
     * @return Schematik\Db\Table|null
     */
    public function getTable($name) {
        foreach ($this->tables as $table) {
            if ($table->getName() == $name) {
                return $table;
            }
        }
    }

    protected function parseTable(Table $table)
    {
        $columns = $this->getColumnData($table->getName());
        foreach ($columns as $columnData) {
            $column = $this->parseColumnType($columnData);
            $this->parsePrimaryKey($columnData,$column);
            $this->parseComments($columnData,$column);
            $table[] = $column;
        }
    }

    protected function parseColumnType($columnData)
    {
        /* @todo: Refactor this to not suck */
        list($type,$length) = explode('(',$columnData['Type']);
        $length = substr($length,0,strlen($length)-1);
        $type = strtolower($type);

        $name = strtolower($columnData['Field']);
        switch ($type) {
            case 'int':
            case 'tinyint':
            case 'bigint':
                $column = new Int($name,$length);
                break;
            case 'enum':
                /* @todo fix this garbage: */
                $scrubbed = str_replace('\'','',$length);
                $options = explode(',',$scrubbed);
                $column = new Enum($name,$options);
                break;
            /* @todo: implement date() */
            case 'varchar':
            default:
                $column = new Varchar($name,$length);
        }

        return $column;
    }

    protected function parsePrimaryKey($columnData,$column)
    {
        if (!empty($columnData['Key'])) {
            /* @todo: cleanup this garbage: */
            if (strpos('PRI',$columnData['Key']) !== false) {
                $column->setPrimaryKey(true);
            }
        }
    }

    protected function parseDefaultValue($columnData,$column)
    {
        if (!empty($columnData['Default'])) {
            $column->setDefaultValue( $columnData['Default'] );
        }
    }

    protected function parseComments($columnData,$column)
    {
        if (!empty($columnData['Comment'])) {
            $attributes = explode(',',$columnData['Comment']);
            foreach ($attributes as $attribute) {
                $className = 'Schematik\\Attribute\\' . ucfirst(strtolower($attribute));
                if (class_exists($className)) {
                    $attr = new $className();
                    $column->addAttribute($attr);
                }
            }
        }
        return $column;
    }

    /**
     * @param $tbl string
     * @return array
     */
    protected function getColumnData($tbl)
    {
        $stmt = $this->db->prepare('show full columns from user'); /* @todo: fix parameter */
        $stmt->bindParam(':table',$tbl);
        $stmt->execute();
        return $stmt->fetchAll( \PDO::FETCH_ASSOC );
    }
}