<?php
namespace micetm\conditionsBase\services\builders\elasticsearch\comparisons;

use micetm\conditionsBase\exceptions\WrongComparison;
use micetm\conditionsBase\models\ComparisonInterface;
use micetm\conditionsBase\models\ConditionInterface;
use micetm\conditionsBase\services\builders\elasticsearch\comparisons\aggregations\SizeComparison;
use micetm\conditionsBase\services\ComparisonManagerInterface;

class ComparisonManager implements ComparisonManagerInterface
{
    const AVAILABLE_COMPARISONS = [
        SizeComparison::class,
        RangeComparison::class,
        LikeComparison::class,
        InComparison::class,
        DefaultComparison::class,
        EmbeddedComparison::class
    ];

    /**
     * @param ConditionInterface $condition
     * @return ComparisonInterface
     * @throws WrongComparison
     */
    public function getComparison(ConditionInterface $condition): ComparisonInterface
    {
        foreach (self::AVAILABLE_COMPARISONS as $className) {
            if ($className::isMaster($condition)) {
                return new $className();
            }
        }

        throw new WrongComparison();
    }
}
