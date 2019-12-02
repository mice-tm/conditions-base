<?php

namespace micetm\conditionsBase\services\builders\elasticsearch\comparisons;

use micetm\conditionsBase\models\AttributeInterface;
use micetm\conditionsBase\models\ComparisonInterface;
use micetm\conditionsBase\models\ConditionInterface;

class EmbeddedComparison implements ComparisonInterface
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

        $query['nested']['path'] = $condition->attribute;
        $query['nested']['score_mode'] = 'avg';
        foreach ($condition->value as $key => $value) {
            $query['nested']['query']['bool']['must'][]["match"][$condition->attribute . '.' . $key]
                = $value;
        }

        return $query;
    }
}
