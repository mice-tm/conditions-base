<?php

namespace micetm\conditionsBase\models\conditions;

use micetm\conditionsBase\models\ConditionInterface;
use yii\base\Model;
use yii2tech\embedded\ContainerInterface;
use yii2tech\embedded\ContainerTrait;

/**
 * Class Condition
 * @package micetm\conditionsBase\models\conditions
 */
class Condition extends Model implements ContainerInterface, ConditionInterface
{
    use ContainerTrait;

    /** @var string */
    public $operator;

    /** @var string */
    public $attribute;

    /** @var string */
    public $comparison;

    /** @var mixed */
    public $value;

    public $conditions = [];

    public static $operators = [
        self::OPERATOR_AND => self::OPERATOR_AND,
        self::OPERATOR_OR => self::OPERATOR_OR,
        self::OPERATOR_NOT => self::OPERATOR_NOT,
        self::OPERATOR_STATEMENT => self::OPERATOR_STATEMENT
    ];


    public function embedConditionModels()
    {
        return $this->mapEmbeddedList('conditions', Condition::class, ['unsetSource' => false]);
    }

    public function rules()
    {
        return [
            [['operator', 'attribute', 'comparison'], 'string'],
            ['operator', 'in', 'range' => self::$operators],
//            [
//                ['operator'],
//                'required',
//                'on' => self::SCENARIO_DEFAULT
//            ],
            ['conditionModels', 'yii2tech\embedded\Validator', 'message' => 'Conditions are invalid.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return [
            self::SCENARIO_DEFAULT => [
                'operator',
                'attribute',
                'comparison',
                'conditionModels',
                'value',
            ],
        ] + parent::scenarios();
    }

    public function isUnary():bool
    {
        return !empty($this->attribute) && !empty($this->comparison);
    }

}
