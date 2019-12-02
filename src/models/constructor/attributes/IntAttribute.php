<?php

namespace micetm\conditionsBase\models\constructor\attributes;

use micetm\conditionsBase\models\AttributeInterface;

class IntAttribute extends AbstractAttribute implements AttributeInterface
{

    /** @var array */
    public $comparisons = [
        self::EQUAL_TO_COMPARISON => self::EQUAL_TO_COMPARISON,
        self::GREATER_THAN_COMPARISON => self::GREATER_THAN_COMPARISON,
        self::GREATER_THAN_OR_EQUAL_TO_COMPARISON => self::GREATER_THAN_OR_EQUAL_TO_COMPARISON,
        self::LESS_THAN_COMPARISON => self::LESS_THAN_COMPARISON,
        self::LESS_THAN_OR_EQUAL_TO_COMPARISON => self::LESS_THAN_OR_EQUAL_TO_COMPARISON,
        self::MORE_THAN_ONE_IN_COMPARISON => self::MORE_THAN_ONE_IN_COMPARISON,
    ];
}
