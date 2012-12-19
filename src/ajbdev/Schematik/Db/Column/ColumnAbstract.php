<?php
/**
 * Author: andybaird
 * Date: 12/18/12
 */

namespace Schematik\Db\Column;

use Schematik\Attribute\AttributeAbstract;

class ColumnAbstract {
    /**
     * @var array
     */
    protected $attributes = array();
    protected $primaryKey;
    protected $allowNull;
    protected $defaultValue = null;
    protected $name;

    public function getAllowNull() {
        if ($this->primaryKey) {
            return true;
        }
        return $this->allowNull;
    }

    public function setAllowNull($boolean) {
        $this->allowNull = (bool) $boolean;
    }

    public function getDefaultValue() {
        return $this->defaultValue;
    }

    public function setDefaultValue($value) {
        $this->defaultValue = $value;
    }

    public function __construct($name) {
        $this->setName($name);
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function isPrimaryKey() {
        return $this->primaryKey;
    }

    public function setPrimaryKey($boolean) {
        $this->primaryKey = (bool) $boolean;
    }

    public function addAttribute(AttributeAbstract $attribute) {
        $this->attributes[] = $attribute;
    }

    public function getAttributes() {
        return $this->attributes;
    }

    public function hasAttributes() {
        return count($this->attributes) > 0;
    }

    public function validate($value)
    {

    }
}