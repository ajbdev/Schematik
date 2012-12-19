<?php
/**
 * Author: andybaird
 * Date: 12/18/12
 */

namespace Schematik\Db\Column;

use Schematik\Db\Column\ColumnAbstract;
use Schematik\Db\Column\MaxLengthException;

class Varchar extends ColumnAbstract
{
    protected $length;

    public function __construct($name, $int) {
        parent::__construct($name);

        $this->length = (int) $int;
    }

    public function validate($value) {
        if (strlen($value) > $this->length) {
            throw new MaxLengthException('Max length beyond ' . $this->length . ' characters in length');
        }
    }
}
