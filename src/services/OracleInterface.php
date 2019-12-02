<?php

namespace micetm\conditionsBase\services;

use micetm\conditionsBase\models\conditions\Condition;

interface OracleInterface
{
    /**
     * @param Condition $condition Consists of attribute name, its value and a comparison type
     * @param array|object $target Where to find coresponding attribute value for comparison
     * @return bool
     */
    public function speak(Condition $condition, $target): bool;
}
