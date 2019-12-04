<?php

namespace micetm\conditionsBase\services\builders\mongodb\comparisons;

use micetm\conditionsBase\models\AttributeInterface;
use micetm\conditionsBase\models\ComparisonInterface;
use micetm\conditionsBase\models\ConditionInterface;
use micetm\conditionsBase\services\builders\mongodb\QueryBuilder;

class DefaultComparison implements ComparisonInterface
{
    const RANGE_PARAMETER_GREATER_THAN_OR_EQUAL_TO = '$gte';
    const RANGE_PARAMETER_GREATER_THAN = '$gt';
    const RANGE_PARAMETER_LESS_THAN_OR_EQUAL_TO = '$lte';
    const RANGE_PARAMETER_LESS_THAN = '$lt';
    const IN_COMPARISON = '$in';

    public static function isMaster(ConditionInterface $condition): bool
    {
        return AttributeInterface::EQUAL_TO_COMPARISON === $condition->comparison;
    }

    public function buildFilter(ConditionInterface $condition): array
    {
        if (is_array($condition->value)) {
            return $this->buikdNestedFilter($condition);
        }
        $query[$condition->attribute] = $condition->value;
        return $query;
    }

    protected function buikdNestedFilter(ConditionInterface $condition): array
    {
        $query[$condition->attribute][self::IN_COMPARISON] = array_values($condition->value);
        return $query;
    }
}
