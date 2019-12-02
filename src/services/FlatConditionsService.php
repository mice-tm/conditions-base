<?php

namespace micetm\conditionsBase\services;

use micetm\conditionsBase\models\ConditionInterface;
use micetm\conditionsBase\models\constructor\conditions\Condition;

class FlatConditionsService
{
    /**
     * @param array|\ArrayObject $conditionsList
     * @return ConditionInterface[]
     */
    public function create($conditionsList): array
    {
        $result = [];

        /** @var ConditionInterface $condition */
        foreach ($conditionsList as $condition) {
            if (count($condition->conditionModels)) {
                $result = array_merge($result, $this->create($condition->conditionModels));
            }

            $result[] = $this->makeFlatClone($condition);
        }

        return $result;
    }

    private function makeFlatClone(ConditionInterface $condition)
    {
        $flatCondition = new Condition();
        $flatCondition->load([
            'operator' => $condition->operator,
            'attribute' => $condition->attribute,
            'value' => $condition->value,
            'comparison' => $condition->comparison,
        ], '');

        return $flatCondition;
    }
}
