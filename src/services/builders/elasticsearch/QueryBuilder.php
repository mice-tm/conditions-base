<?php
namespace micetm\conditionsBase\services\builders\elasticsearch;

use micetm\conditionsBase\services\builders\elasticsearch\comparisons\ComparisonManager;
use micetm\conditionsBase\exceptions\WrongComparison;
use micetm\conditionsBase\models\ConditionInterface;
use micetm\conditionsBase\services\BuilderInterface;

class QueryBuilder implements BuilderInterface
{
    /** @var ComparisonManager */
    public $comparisonManager;

    const OPERATOR_AND = "must";
    const OPERATOR_OR = "should";
    const OPERATOR_NOT = "must_not";

    const CONDITIONS = [
        ConditionInterface::OPERATOR_AND => self::OPERATOR_AND,
        ConditionInterface::OPERATOR_OR => self::OPERATOR_OR,
        ConditionInterface::OPERATOR_NOT => self::OPERATOR_NOT,
        ConditionInterface::OPERATOR_STATEMENT => self::OPERATOR_OR,
    ];

    public function __construct(ComparisonManager $comparisonManager)
    {
        $this->comparisonManager = $comparisonManager;
    }

    /**
     * @param $conditions
     * @return array
     * @throws WrongComparison
     */
    public function create($conditions):array
    {
        $query = [];

        if ($conditions instanceof \ArrayObject || is_array($conditions)) {
            foreach ($conditions as $condition) {
                $query[] = $this->getQuery($condition);
            }
        }

        if (empty($query)) {
            return [];
        }
        if (1 == count($query)) {
            return [
                "query" => array_shift($query)
            ];
        }

        return [
            "query" => [
                "bool" => [
                    QueryBuilder::OPERATOR_OR => $query
                ]
            ]
        ];
    }

    /**
     * @return array|null
     * @throws WrongComparison
     */
    protected function getQuery(ConditionInterface $condition)
    {
        if (count($condition->conditionModels)) {
            $query = [];
            foreach ($condition->conditionModels as $childCondition) {
                if (!empty($filter = $this->getQuery($childCondition))) {
                    $query["bool"][self::CONDITIONS[$condition->operator]][] = $filter;
                }
            }
            return $query;
        } elseif ($condition->attribute) {
            $comparison = $this->comparisonManager->getComparison($condition);
            return $comparison->buildFilter($condition);
        }
        return;
    }
}
