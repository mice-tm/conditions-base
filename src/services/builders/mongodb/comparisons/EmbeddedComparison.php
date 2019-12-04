<?php

namespace micetm\conditionsBase\services\builders\mongodb\comparisons;

use micetm\conditionsBase\models\AttributeInterface;
use micetm\conditionsBase\models\ComparisonInterface;
use micetm\conditionsBase\models\ConditionInterface;

class EmbeddedComparison extends DefaultComparison
{
    public static function isMaster(ConditionInterface $condition): bool
    {
        return AttributeInterface::EMBEDDED_COMPARISON === $condition->comparison;
    }

    public function buildFilter(ConditionInterface $condition): array
    {
        if (!is_array($condition->value)) {
            throw new \RuntimeException('Nested comparison should have value of type Array');
        }

        foreach ($condition->value as $key => $value) {
            $query[$condition->attribute]['$elemMatch'][$key] = $value;
        }

        return $query;
    }
}
