<?php
/**
 * Author: andybaird
 * Date: 12/18/12
 */
namespace Schematik\Attribute;

use Schematik\Attribute\AttributeAbstract;
use Schematik\Attribute\ValidateException;

class Email extends AttributeAbstract {
    public function preCommit($value) {
        if (strpos($value,'@') === false) {
            throw new ValidateException('Invalid e-mail address');
        }
        return $value;
    }
}