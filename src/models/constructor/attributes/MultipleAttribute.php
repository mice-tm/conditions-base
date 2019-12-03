<?php

namespace micetm\conditionsBase\models\constructor\attributes;

use micetm\conditionsBase\models\AttributeInterface;

/**
 * Class MultipleAttribute
 * @package micetm\conditions\models\constructor\attributes
 * @deprecated any attribute can be multiple and have data
 */
class MultipleAttribute extends AbstractAttribute implements AttributeInterface
{
    /** @var array */
    public $data = [];

    public $multiple = false;

    /** @var array */
    public $comparisons = [
        self::MORE_THAN_ONE_IN_COMPARISON => self::MORE_THAN_ONE_IN_COMPARISON,
        self::EQUAL_TO_COMPARISON => self::EQUAL_TO_COMPARISON,
    ];

    public function getData(){
        return $this->data;
    }
}
