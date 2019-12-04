<?php
namespace micetm\conditionsBase\services\builders\mongodb;

use micetm\conditionsBase\exceptions\WrongComparison;
use micetm\conditionsBase\models\ConditionInterface;
use micetm\conditionsBase\services\BuilderInterface;
use micetm\conditionsBase\services\builders\mongodb\comparisons\ComparisonManager;

class QueryBuilder implements BuilderInterface
{
    /** @var ComparisonManager */
    public $comparisonManager;

    const OPERATOR_AND = '$and';
    const OPERATOR_OR = '$or';
    const OPERATOR_NOT = '$not';

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
        $query = array_filter($query);

        if (empty($query)) {
            return [];
        }
        if (1 == count($query)) {
            return  array_shift($query);
        }

        return [
            self::OPERATOR_OR => $query
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
                    $query[self::CONDITIONS[$condition->operator]][] = $filter;
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
