<?php

namespace micetm\conditionsBase\services\builders\elasticsearch\comparisons;

use micetm\conditionsBase\models\AttributeInterface;
use micetm\conditionsBase\models\ComparisonInterface;
use micetm\conditionsBase\models\ConditionInterface;
use micetm\conditionsBase\services\builders\elasticsearch\QueryBuilder;

class LikeComparison implements ComparisonInterface
{
    public static function isMaster(ConditionInterface $condition): bool
    {
        return AttributeInterface::LIKE_COMPARISON === $condition->comparison;
    }

    public function buildFilter(ConditionInterface $condition): array
    {
        $query["bool"][QueryBuilder::OPERATOR_OR][]["match"][$condition->attribute] = $condition->value;
        $query["bool"][QueryBuilder::OPERATOR_OR][]["wildcard"][$condition->attribute . '.raw'] =
            '*' . strtolower($condition->value) . '*';
        return $query;
    }
}
