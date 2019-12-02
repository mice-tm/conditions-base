<?php

namespace micetm\conditionsBase\models\constructor\attributes;

use micetm\conditionsBase\models\AttributeInterface;
use yii\base\Model;
use yii\base\UnknownPropertyException;

class AbstractAttribute extends Model implements AttributeInterface
{

    /** @var string */
    public $title;

    /** @var string */
    public $description;

    /** @var string */
    public $type;

    /** @var string */
    public $level;

    /** @var string */
    public $key;

    /** @var string */
    public $status = self::STATUS_ACTIVE;

    /** @var bool  */
    public $multiple = false;

    /** @var array */
    public $data = [];

    /** @var array */
    public $comparisons = self::availableComparisons;

    public static $types = [
        'int', 'string', 'bool', 'multiple'
    ];
    public static $statuses = [
        self::STATUS_ACTIVE,
        self::STATUS_INACTIVE
    ];

    public function rules()
    {
        return [
            ['multiple', 'boolean'],
            [['title', 'description', 'status', 'type', 'level', 'key'], 'string'],
            ['type', 'in', 'range' => self::$types],
            ['status', 'in', 'range' => self::$statuses],
            ['comparisons', 'each', 'rule' => ['in', 'range' => self::availableComparisons]],
            ['data', 'safe'],
        ];
    }

    public function __set($name, $value)
    {
        try {
            return parent::__set($name, $value); // TODO: Change the autogenerated stub
        } catch (UnknownPropertyException $exception) {

        }
    }

    public function value($value)
    {
        return $value;
    }

    public function getData()
    {
        return $this->data;
    }
}
