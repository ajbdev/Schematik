<?php
/**
 * Author: andybaird
 * Date: 12/18/12
 */
namespace Schematik\Attribute;

use Schematik\Attribute\AttributeAbstract;
use Schematik\Attribute\ValidateException;

/** @todo: Percolate -- how to add a salt column? */
class Password extends AttributeAbstract {
    public function preCommit($value) {
        for ($i=0;$i<10;$i++) {
            $value = sha1($value);
        }
        return $value;
    }
}