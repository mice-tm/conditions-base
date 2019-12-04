<?php

namespace micetm\conditionsBase\services\builders\mongodb\comparisons;

use micetm\conditionsBase\models\AttributeInterface;
use micetm\conditionsBase\models\ComparisonInterface;
use micetm\conditionsBase\models\ConditionInterface;
use micetm\conditionsBase\services\builders\mongodb\QueryBuilder;

class LikeComparison implements ComparisonInterface
{
    public static function isMaster(ConditionInterface $condition): bool
    {
        return AttributeInterface::LIKE_COMPARISON === $condition->comparison;
    }

    public function buildFilter(ConditionInterface $condition): array
    {
        $query['$text']['$search'] = $condition->value;
        return $query;
    }
}
