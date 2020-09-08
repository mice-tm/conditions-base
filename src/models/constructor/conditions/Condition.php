<?php

namespace micetm\conditionsBase\models\constructor\conditions;

use micetm\conditionsBase\models\ConditionInterface;
use yii\base\Model;
use yii2tech\embedded\ContainerInterface;
use yii2tech\embedded\ContainerTrait;

/**
 * Class Condition
 * @package micetm\conditionsBase\models\constructor\conditions
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
            ['operator', 'checkOperator', 'skipOnEmpty' => false],
            ['conditionModels', 'yii2tech\embedded\Validator']
        ];
    }

    public function checkConditions($attribute, $params)
    {
        if (empty($this->operator) && sizeof($this->conditionModels) != 0) {
            $this->addError('operator', 'Operator cannot be empty with filled conditions!');
            return false;
        }
        return true;
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
