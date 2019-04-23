<?php

namespace micetm\conditionsBase\models;

use yii\base\Model;
use yii\base\UnknownPropertyException;

interface AttributeInterface
{
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    const TYPE_DEFAULT = 'default';

    const EQUAL_TO_COMPARISON = '=';
    const GREATER_THAN_COMPARISON = '>';
    const LESS_THAN_COMPARISON = '<';
    const GREATER_THAN_OR_EQUAL_TO_COMPARISON = '>=';
    const LESS_THAN_OR_EQUAL_TO_COMPARISON = '<=';
    const LIKE_COMPARISON = 'like';
    const MORE_THAN_ONE_IN_COMPARISON = 'in';
    const EMBEDDED_COMPARISON = 'embedded';

    const availableComparisons = [
        self::EQUAL_TO_COMPARISON => self::EQUAL_TO_COMPARISON,
        self::GREATER_THAN_COMPARISON => self::GREATER_THAN_COMPARISON,
        self::GREATER_THAN_OR_EQUAL_TO_COMPARISON => self::GREATER_THAN_OR_EQUAL_TO_COMPARISON,
        self::LESS_THAN_COMPARISON => self::LESS_THAN_COMPARISON,
        self::LESS_THAN_OR_EQUAL_TO_COMPARISON => self::LESS_THAN_OR_EQUAL_TO_COMPARISON,
        self::LIKE_COMPARISON => self::LIKE_COMPARISON,
        self::MORE_THAN_ONE_IN_COMPARISON => self::MORE_THAN_ONE_IN_COMPARISON,
    ];

    public function value($value);

    public function getData();

}