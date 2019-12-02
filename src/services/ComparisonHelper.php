<?php

namespace micetm\conditionsBase\services;

use micetm\conditionsBase\models\AttributeInterface;
use micetm\conditionsBase\models\ConditionInterface;


class ComparisonHelper
{
    const COMPARISONS_ASSERTS = [
        AttributeInterface::EQUAL_TO_COMPARISON => 'eq',
        AttributeInterface::GREATER_THAN_COMPARISON => 'gt',
        AttributeInterface::GREATER_THAN_OR_EQUAL_TO_COMPARISON => 'gte',
        AttributeInterface::LESS_THAN_COMPARISON => 'lt',
        AttributeInterface::LESS_THAN_OR_EQUAL_TO_COMPARISON => 'lte',
        AttributeInterface::LIKE_COMPARISON => 'like',
        AttributeInterface::MORE_THAN_ONE_IN_COMPARISON => 'in',
    ];

    /**
     * @param Condition $condition
     * @param $value
     * @return bool
     */
    public static function compare(ConditionInterface $condition, $value)
    {
        if (empty(self::COMPARISONS_ASSERTS[$condition->comparison])) {
            return self::in($condition->value, $value);
        }

        return self::{self::COMPARISONS_ASSERTS[$condition->comparison]}($condition->value, $value);
    }

    /**
     * @param mixed $a
     * @param mixed $b
     * @return bool
     */
    public static function in($a, $b)
    {
        return !empty(array_intersect((array)$a, (array)$b));
    }

    /**
     * @param mixed $a
     * @param mixed $b
     * @return bool
     */
    public static function eq($a, $b)
    {
        return $a == $b;
    }

    /**
     * @param mixed $a
     * @param mixed $b
     * @return bool
     */
    public static function gt($a, $b)
    {
        return $a > $b;
    }

    /**
     * @param mixed $a
     * @param mixed $b
     * @return bool
     */
    public static function gte($a, $b)
    {
        return $a >= $b;
    }

    /**
     * @param mixed $a
     * @param mixed $b
     * @return bool
     */
    public static function lt($a, $b)
    {
        return $a < $b;
    }

    /**
     * @param mixed $a
     * @param mixed $b
     * @return bool
     */
    public static function lte($a, $b)
    {
        return $a <= $b;
    }

    /**
     * @param mixed $a
     * @param mixed $b
     * @return bool
     */
    public static function like($a, $b)
    {
        return false !== strpos($b, $a);
    }
}
