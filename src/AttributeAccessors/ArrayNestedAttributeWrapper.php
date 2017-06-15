<?php

namespace PHP\Cast\AttributeAccessors;

use PHP\Cast\AttributeAccessor;

class ArrayNestedAttributeWrapper extends NestedAttribute {

    public function __construct(AttributeAccessor $attributeAccessor) {
        parent::__construct($attributeAccessor);
    }

    public function getValue(array $target) {
        if (empty($target)) {
            return array();
        }

        $ret = array();

        $total = count($target);

        for ($i = 0; $i < $total; $i++) {
            $ret[] = parent::getValue($target[$i]);
        }

        return $ret;
    }

    public function setValue(array &$target, $newValue) {
        if (empty($target)) {
            return;
        }

        foreach ($target as &$value) {
            parent::getValue($value);
        }
    }

    public function cast(array $target) {
        if (empty($target)) {
            return array();
        }

        $ret = array();

        $total = count($target);

        for ($i = 0; $i < $total; $i++) {
            $ret[] = parent::cast($target[$i]);
        }

        return $ret;
    }

    protected function appendAttrToFailMessages($index, $failMsgs) {
        foreach ($failMsgs as &$failMsg) {
            $failMsg = "$index." . $failMsg;
        }

        return $failMsgs;
    }

    public function validate($target) {
        if ($target == null) {
            return parent::validate(null);
        }

        $ret = array();

        $total = count($target);

        for ($i = 0; $i < $total; $i++) {
            $ret = array_merge($ret, $this->appendAttrToFailMessages($i, parent::validate($target[$i])));
        }

        return $ret;
    }
}
