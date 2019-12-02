<?php
namespace test\unit\models\constructor\services;

use micetm\conditionsBase\models\conditions\Condition;
use micetm\conditionsBase\services\ComparisonHelper;
use micetm\conditionsBase\models\AttributeInterface;
use PHPUnit\Framework\TestCase;

class ComparisonHelperTest extends TestCase
{

    /**
     * @skip
     * @param $condition
     *
     * @param $value
     * @param $expectedResult
     * @dataProvider providerSpeakSuccess
     */
    public function testSpeakSuccess($condition, $value, $expectedResult)
    {
        $this->assertEquals(ComparisonHelper::compare($condition, $value), $expectedResult);
    }

    public function providerSpeakSuccess()
    {
        return [
            [
                'condition' => new Condition([
                    'comparison' => AttributeInterface::EQUAL_TO_COMPARISON,
                    'value' => '1',
                ]),
                1,
                true
            ],
            [
                'condition' => new Condition([
                    'comparison' => AttributeInterface::EQUAL_TO_COMPARISON,
                    'value' => '1',
                ]),
                '1',
                true
            ],
            [
                'condition' => new Condition([
                    'comparison' => AttributeInterface::EQUAL_TO_COMPARISON,
                    'value' => '1',
                ]),
                true,
                true
            ],
            [
                'condition' => new Condition([
                    'comparison' => AttributeInterface::EQUAL_TO_COMPARISON,
                    'value' => false,
                ]),
                true,
                false
            ],
            [
                'condition' => new Condition([
                    'comparison' => AttributeInterface::GREATER_THAN_COMPARISON,
                    'value' => 2,
                ]),
                1,
                true
            ],
            [
                'condition' => new Condition([
                    'comparison' => AttributeInterface::GREATER_THAN_COMPARISON,
                    'value' => 1,
                ]),
                2,
                false
            ],
            [
                'condition' => new Condition([
                    'comparison' => AttributeInterface::GREATER_THAN_OR_EQUAL_TO_COMPARISON,
                    'value' => 1,
                ]),
                1,
                true
            ],
            [
                'condition' => new Condition([
                    'comparison' => AttributeInterface::GREATER_THAN_OR_EQUAL_TO_COMPARISON,
                    'value' => 1,
                ]),
                2,
                false
            ],
            [
                'condition' => new Condition([
                    'comparison' => AttributeInterface::LESS_THAN_COMPARISON,
                    'value' => 2,
                ]),
                3,
                true
            ],
            [
                'condition' => new Condition([
                    'comparison' => AttributeInterface::LESS_THAN_COMPARISON,
                    'value' => 3,
                ]),
                2,
                false
            ],
            [
                'condition' => new Condition([
                    'comparison' => AttributeInterface::LESS_THAN_OR_EQUAL_TO_COMPARISON,
                    'value' => 1,
                ]),
                1,
                true
            ],
            [
                'condition' => new Condition([
                    'comparison' => AttributeInterface::LESS_THAN_OR_EQUAL_TO_COMPARISON,
                    'value' => 2,
                ]),
                1,
                false
            ],
            [
                'condition' => new Condition([
                    'comparison' => AttributeInterface::MORE_THAN_ONE_IN_COMPARISON,
                    'value' => 'cat',
                ]),
                ['cat', 'dog'],
                true
            ],
            [
                'condition' => new Condition([
                    'comparison' => AttributeInterface::MORE_THAN_ONE_IN_COMPARISON,
                    'value' => 'cat',
                ]),
                ['car', 'dog'],
                false
            ],
            [
                'condition' => new Condition([
                    'comparison' => AttributeInterface::LIKE_COMPARISON,
                    'value' => 'cat',
                ]),
                'catdog',
                true
            ],
            [
                'condition' => new Condition([
                    'comparison' => AttributeInterface::MORE_THAN_ONE_IN_COMPARISON,
                    'value' => 'cat',
                ]),
                'cart',
                false
            ],
            [
                'condition' => new Condition([
                    'comparison' => 'undefined_comparison_type',
                    'value' => 'cat',
                ]),
                ['cat', 'dog'],
                true
            ],
            [
                'condition' => new Condition([
                    'comparison' => 'undefined_comparison_type',
                    'value' => 'cat',
                ]),
                ['car', 'dog'],
                false
            ],
        ];
    }
}
