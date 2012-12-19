<?php
/**
 * Author: andybaird
 * Date: 12/18/12
 */

namespace Schematik\Attribute;

class AttributeAbstract {
    public function preInsert($value) {
        return $this->preCommit($value);
    }

    public function preUpdate($value) {
        return $this->preCommit($value);
    }

    public function preCommit($value) {
        return $value;
    }

}