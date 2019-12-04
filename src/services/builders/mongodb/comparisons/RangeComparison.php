<?php

namespace micetm\conditionsBase\services\builders\mongodb\comparisons;

use micetm\conditionsBase\models\AttributeInterface;
use micetm\conditionsBase\models\ComparisonInterface;
use micetm\conditionsBase\models\ConditionInterface;
use micetm\conditionsBase\services\builders\mongodb\QueryBuilder;

class RangeComparison implements ComparisonInterface
{
    public static function isMaster(ConditionInterface $condition): bool
    {
        return in_array(
            $condition->comparison,
            [
                AttributeInterface::GREATER_THAN_COMPARISON,
                AttributeInterface::LESS_THAN_COMPARISON,
                AttributeInterface::GREATER_THAN_OR_EQUAL_TO_COMPARISON,
                AttributeInterface::LESS_THAN_OR_EQUAL_TO_COMPARISON
            ]
        );
    }

    public function buildFilter(ConditionInterface $condition): array
    {
        if (AttributeInterface::GREATER_THAN_COMPARISON === $condition->comparison) {
            $query[$condition->attribute][DefaultComparison::RANGE_PARAMETER_GREATER_THAN]
                = $condition->value;
            return $query;
        }
        if (AttributeInterface::LESS_THAN_COMPARISON === $condition->comparison) {
            $query[$condition->attribute][DefaultComparison::RANGE_PARAMETER_LESS_THAN]
                = $condition->value;
            return $query;
        }
        if (AttributeInterface::GREATER_THAN_OR_EQUAL_TO_COMPARISON === $condition->comparison) {
            $query[$condition->attribute][DefaultComparison::RANGE_PARAMETER_GREATER_THAN_OR_EQUAL_TO]
                = $condition->value;
            return $query;
        }
        if (AttributeInterface::LESS_THAN_OR_EQUAL_TO_COMPARISON === $condition->comparison) {
            $query[$condition->attribute][DefaultComparison::RANGE_PARAMETER_LESS_THAN_OR_EQUAL_TO]
                = $condition->value;
            return $query;
        }

        return [];
    }
}
