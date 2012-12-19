<?php
/**
 * Author: andybaird
 * Date: 12/18/12
 */
namespace Schematik\Db\Column;

use Schematik\Db\Column\ColumnAbstract;

class Enum extends ColumnAbstract {
    protected $options = array();

    public function __construct($name,$options) {
        parent::__construct($name);
        if (is_array($options)) {
            $this->options = $options;
        }
    }

    public function validate($value) {
        if (!in_array($value,$this->options)) {
            throw new InvalidEnumOptionException('Unknown option: ' . $value);
        }
    }
}