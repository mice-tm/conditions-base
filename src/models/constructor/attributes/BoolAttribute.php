<?php

namespace micetm\conditionsBase\models\constructor\attributes;

use micetm\conditionsBase\models\AttributeInterface;

class BoolAttribute extends AbstractAttribute implements AttributeInterface
{
    public $comparisons = self::availableComparisons;

    const availableComparisons =  [
        self::EQUAL_TO_COMPARISON => self::EQUAL_TO_COMPARISON,
    ];
}
