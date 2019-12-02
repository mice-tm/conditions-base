<?php

namespace micetm\conditionsBase\models;

interface ComparisonInterface
{
    public function buildFilter(ConditionInterface $condition): array;

    public static function isMaster(ConditionInterface $condition): bool;
}
