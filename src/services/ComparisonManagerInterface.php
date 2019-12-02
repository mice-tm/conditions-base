<?php
namespace micetm\conditionsBase\services;

use micetm\conditionsBase\exceptions\WrongComparison;
use micetm\conditionsBase\models\ComparisonInterface;
use micetm\conditionsBase\models\ConditionInterface;

interface ComparisonManagerInterface
{
    /**
     * @param Condition $condition
     * @return ComparisonInterface
     * @throws WrongComparison
     */
    public function getComparison(ConditionInterface $condition): ComparisonInterface;
}
