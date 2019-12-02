<?php
namespace test\unit\models\constructor\comparisons;

use micetm\conditionsBase\services\builders\elasticsearch\comparisons\RangeComparison;
use micetm\conditionsBase\models\conditions\Condition;
use PHPUnit\Framework\TestCase;

class RangeComparisonTest extends TestCase
{

    public function testBuildFilterFail()
    {
        $rangeComparison = new RangeComparison();
        $condition = new Condition();
        $this->assertEmpty($rangeComparison->buildFilter($condition));
    }
}
