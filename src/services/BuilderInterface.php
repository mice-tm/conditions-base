<?php

namespace micetm\conditionsBase\services;

use micetm\conditionsBase\exceptions\WrongComparison;

interface BuilderInterface
{
    /**
     * Create query for
     * @param $conditions
     * @return array
     * @throws WrongComparison
     */
    public static function create($conditions):array;

}
