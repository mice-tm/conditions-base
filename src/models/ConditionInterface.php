<?php

namespace micetm\conditionsBase\models;

use yii\base\Model;
use yii2tech\embedded\ContainerInterface;
use yii2tech\embedded\ContainerTrait;

/**
 * Interface ConditionInterface
 * @package micetm\conditionsBase\models
 */
interface ConditionInterface
{
    const OPERATOR_AND = 'AND';
    const OPERATOR_OR = 'OR';
    const OPERATOR_NOT = 'NOT';
    const OPERATOR_STATEMENT = null;

    /**
     * @return bool
     */
    public function isUnary(): bool;
}
