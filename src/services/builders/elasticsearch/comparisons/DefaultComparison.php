<?php

namespace micetm\conditionsBase\services\builders\elasticsearch\comparisons;

use micetm\conditionsBase\models\AttributeInterface;
use micetm\conditionsBase\models\ComparisonInterface;
use micetm\conditionsBase\models\ConditionInterface;
use micetm\conditionsBase\services\builders\elasticsearch\QueryBuilder;

class DefaultComparison implements ComparisonInterface
{
    const RANGE_PARAMETER_GREATER_THAN_OR_EQUAL_TO = 'gte';
    const RANGE_PARAMETER_GREATER_THAN = 'gt';
    const RANGE_PARAMETER_LESS_THAN_OR_EQUAL_TO = 'lte';
    const RANGE_PARAMETER_LESS_THAN = 'lt';

    public static function isMaster(ConditionInterface $condition): bool
    {
        return AttributeInterface::EQUAL_TO_COMPARISON === $condition->comparison;
    }

    public function buildFilter(ConditionInterface $condition): array
    {
        if (is_array($condition->value)) {
            $query["bool"][QueryBuilder::OPERATOR_OR][]["terms"][$condition->attribute . ".raw"]
                = $condition->value;
            $query["bool"][QueryBuilder::OPERATOR_OR][]["terms"][$condition->attribute]
                = $condition->value;
            return $query;
        }
        $query["bool"][QueryBuilder::OPERATOR_OR][]["term"][$condition->attribute . ".raw"]
            = $condition->value;
        $query["bool"][QueryBuilder::OPERATOR_OR][]["match_phrase"][$condition->attribute]
            = $condition->value;
        return $query;
    }
}
