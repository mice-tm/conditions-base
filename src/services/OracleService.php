<?php

namespace micetm\conditionsBase\services;

use micetm\conditionsBase\models\conditions\Condition;
use yii\base\UnknownPropertyException;
use yii\helpers\ArrayHelper;
use yii\helpers\Console;

class OracleService implements OracleInterface
{
    const OPERATORS_ASSERTS = [
        Condition::OPERATOR_AND => 'conditionAND',
        Condition::OPERATOR_OR => 'conditionOR',
        Condition::OPERATOR_NOT => 'conditionNOT',
    ];

    /**
     * Returns true if $target satisfies $condition
     *
     * @param Condition $condition
     * @param array|object $target
     * @return bool
     */
    public function speak(Condition $condition, $target): bool
    {
        if ($condition->isUnary()) {
            try {
                $value = ArrayHelper::getValue($target, $condition->attribute);
            } catch (UnknownPropertyException $exception) {
                return false;
            }
            if (empty($value)) {
                return false;
            }
            return ComparisonHelper::compare($condition, $value);
        }

        return $this->{self::OPERATORS_ASSERTS[$condition->operator]}($condition->conditionModels, $target);
    }

    /**
     * @param $conditions
     * @param $target
     * @return bool
     */
    public function conditionOR($conditions, $target)
    {
        foreach ($conditions as $condition) {
            if ($this->speak($condition, $target)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param $conditions
     * @param $target
     * @return bool
     */
    public function conditionAND($conditions, $target)
    {
        foreach ($conditions as $condition) {
            if (!$this->speak($condition, $target)) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param $conditions
     * @param $target
     * @return bool
     */
    public function conditionNOT($conditions, $target)
    {
        if (count($conditions) > 1) {
            return ! $this->conditionOR($conditions, $target);
        }
        return ! $this->speak(current($conditions), $target);
    }
}
