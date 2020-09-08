<?php

namespace test\unit\models\constructor\conditions;

use micetm\conditionsBase\models\constructor\conditions\Condition;
use PHPUnit\Framework\TestCase;

class ConditionTest extends TestCase
{

    /**
     * @dataProvider providerValidate
     * @param array $dataForModel
     * @param bool $validateResult
     */
    public function testValidate(array $dataForModel, bool $validateResult)
    {
        $condition = new Condition();
        $condition->load($dataForModel, '');
        $this->assertEquals($validateResult, $condition->validate());
    }

    /**
     * @return array
     */
    public function providerValidate()
    {
        return [
            'Single condition' => [
                [
                    'operator' => '',
                    'attribute' => 'types',
                    'comparison' => '='
                ],
                true
            ],
            'Empty operator with child condition' => [
                [
                    'operator' => '',
                    'attribute' => 'types',
                    'comparison' => '=',
                    'conditionModels' => [
                        new Condition(
                            [
                                'operator' => 'AND',
                                'attribute' => 'types',
                                'comparison' => '='
                            ]
                        )
                    ]
                ],
                false
            ],
            'Single condition with wrong operator' => [
                [
                    'operator' => 'test',
                    'attribute' => 'types',
                    'comparison' => '='
                ],
                false
            ]
        ];
    }

    /**
     * @dataProvider providerCheckOperator
     * @param string $operator
     * @param Condition[]|array $conditionModels
     * @param bool $result
     */
    public function testCheckOperator(string $operator, array $conditionModels, bool $result)
    {
        $condition = new Condition();
        $condition->operator = $operator;
        $condition->conditionModels = $conditionModels;
        $this->assertEquals($result, $condition->checkOperator());
    }

    /**
     * @return array
     */
    public function providerCheckOperator()
    {
        return [
            'Empty operator with child condition' => [
                'operator' => '',
                'conditionModels' => [
                    new Condition(
                        [
                            'operator' => '',
                            'attribute' => 'types',
                            'comparison' => '='
                        ]
                    )
                ],
                'result' => false
            ],
            'Filled operator with child condition' => [
                'operator' => 'AND',
                'conditionModels' => [
                    new Condition(
                        [
                            'operator' => '',
                            'attribute' => 'types',
                            'comparison' => '='
                        ]
                    )
                ],
                'result' => true
            ],
            'Empty operator with empty child condition' => [
                'operator' => '',
                'conditionModels' => [],
                'result' => true
            ],
            'Filled operator with empty child condition' => [
                'operator' => 'AND',
                'conditionModels' => [],
                'result' => true
            ],
        ];
    }
}
