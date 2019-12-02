<?php

namespace micetm\conditionsBase\services\builders\elasticsearch\comparisons;

use micetm\conditionsBase\models\AttributeInterface;
use micetm\conditionsBase\models\ComparisonInterface;
use micetm\conditionsBase\models\ConditionInterface;
use micetm\conditionsBase\services\builders\elasticsearch\QueryBuilder;

class InComparison implements ComparisonInterface
{
    public static function isMaster(ConditionInterface $condition): bool
    {
        return AttributeInterface::MORE_THAN_ONE_IN_COMPARISON === $condition->comparison;
    }

    public function buildFilter(ConditionInterface $condition): array
    {
        $query["bool"][QueryBuilder::OPERATOR_OR][]["terms"][$condition->attribute . ".raw"]
            = is_array($condition->value) ? $condition->value : [$condition->value];
        $query["bool"][QueryBuilder::OPERATOR_OR][]["terms"][$condition->attribute]
            = is_array($condition->value) ? $condition->value : [$condition->value];
        ;
        return $query;
    }
}
