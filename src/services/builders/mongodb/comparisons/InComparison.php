<?php

namespace micetm\conditionsBase\services\builders\mongodb\comparisons;

use micetm\conditionsBase\models\AttributeInterface;
use micetm\conditionsBase\models\ComparisonInterface;
use micetm\conditionsBase\models\ConditionInterface;
use micetm\conditionsBase\services\builders\mongodb\QueryBuilder;

class InComparison extends DefaultComparison
{
    public static function isMaster(ConditionInterface $condition): bool
    {
        return AttributeInterface::MORE_THAN_ONE_IN_COMPARISON === $condition->comparison;
    }

    public function buildFilter(ConditionInterface $condition): array
    {
        return parent::buikdNestedFilter($condition);
    }
}
