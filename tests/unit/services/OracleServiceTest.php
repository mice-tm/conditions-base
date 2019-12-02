<?php
namespace test\unit\models\constructor\services;

use micetm\conditionsBase\models\AttributeInterface;
use micetm\conditionsBase\models\constructor\conditions\Condition;
use micetm\conditionsBase\services\OracleService;
use PHPUnit\Framework\TestCase;

class OracleServiceTest extends TestCase
{
    /**
     * @var OracleService
     */
    protected $oracle;

    public function setUp()
    {
        $this->oracle = new OracleService();
    }

    /**
     * @skip
     * @param $condition
     *
     * @param $expectedResult
     * @dataProvider providerSpeakSuccess
     */
    public function testSpeakSuccess($condition, $expectedResult)
    {
        
        $target = [
            'key1' => 'value1',
            'key2' => 'value2',
            'embeddedKey1' => [
                [
                    'key1' => 'value1',
                    'key2' => 'value2',
                ],
                [
                    'key3' => 'value3',
                    'key4' => 'value4',
                ]
            ],
            'embeddedKey2' => [
                [
                    'key5' => 'value5',
                    'key6' => 'value6',
                ],
                [
                    'key5' => 'value5',
                    'key6' => 'value6',
                ]
            ],
            'state' => 1,
            'propertyValues' =>
                [
                    'topic' => [
                        'id' => '860308',
                        'value' => 'Graphics',
                    ],
                    'types' => [
                        'id' => '50132',
                        'value' => 'PowerPoint Templates',
                    ],
                ],
            
        ];
        
        $this->assertEquals($expectedResult, $this->oracle->speak($condition, $target));
    }

    public function providerSpeakSuccess()
    {
        return [
            [
                'condition' => new Condition([
                    'attribute' => 'key1',
                    'comparison' => AttributeInterface::EQUAL_TO_COMPARISON,
                    'value' => 'value1'
                ]),
                true
            ],
            [
                'condition' => new Condition([
                    'attribute' => 'unknownAttribute',
                    'comparison' => AttributeInterface::EQUAL_TO_COMPARISON,
                    'value' => 'value1'
                ]),
                false
            ],
            [
                'condition' => new Condition([
                    'attribute' => 'embeddedKey1.1.key3',
                    'comparison' => AttributeInterface::EQUAL_TO_COMPARISON,
                    'value' => 'value3'
                ]),
                true
            ],
            [
                'condition' => new Condition([
                    'operator' => Condition::OPERATOR_NOT,
                    'conditions' => [
                        [
                            'attribute' => 'key1',
                            'comparison' => AttributeInterface::EQUAL_TO_COMPARISON,
                            'value' => 'value1'
                        ]
                    ]
                ]),
                false
            ],
            [
                'condition' => new Condition([
                    'operator' => Condition::OPERATOR_NOT,
                    'conditions' => [
                        [
                            'attribute' => 'key2',
                            'comparison' => AttributeInterface::EQUAL_TO_COMPARISON,
                            'value' => 'value1'
                        ]
                    ]
                ]),
                true
            ],
            [
                'condition' => new Condition([
                    'operator' => Condition::OPERATOR_AND,
                    'conditions' => [
                        [
                            'attribute' => 'key1',
                            'comparison' => AttributeInterface::EQUAL_TO_COMPARISON,
                            'value' => 'value1'
                        ],
                        [
                            'attribute' => 'embeddedKey1.1.key3',
                            'comparison' => AttributeInterface::EQUAL_TO_COMPARISON,
                            'value' => 'value3'
                        ],
                    ]
                ]),
                true
            ],
            [
                'condition' => new Condition([
                    'operator' => Condition::OPERATOR_OR,
                    'conditions' => [
                        [
                            'attribute' => 'key1',
                            'comparison' => AttributeInterface::EQUAL_TO_COMPARISON,
                            'value' => 'value2'
                        ],
                        [
                            'attribute' => 'embeddedKey1.1.key3',
                            'comparison' => AttributeInterface::EQUAL_TO_COMPARISON,
                            'value' => 'value4'
                        ],
                    ]
                ]),
                false
            ],
            [
                'condition' => new Condition([
                    'operator' => Condition::OPERATOR_AND,
                    'conditions' => [
                        [
                            'attribute' => 'state',
                            'comparison' => AttributeInterface::EQUAL_TO_COMPARISON,
                            'value' => '1'
                        ],
                        [
                            'operator' => Condition::OPERATOR_NOT,
                            'conditions' => [
                                [
                                    'attribute' => "propertyValues.wordpressgpl.id",
                                    'comparison' => AttributeInterface::MORE_THAN_ONE_IN_COMPARISON,
                                    'value' => [
                                        "208488"
                                    ],
                                ],
                                [
                                    'attribute' => "propertyValues.types.id",
                                    'comparison' => AttributeInterface::MORE_THAN_ONE_IN_COMPARISON,
                                    'value' => [
                                        "50132",
                                        "67711",
                                        "249307",
                                        "644650",
                                        "892363",
                                        "997417"
                                    ],
                                ],
                                [
                                    'attribute' => "propertyValues.topic.id",
                                    'comparison' => AttributeInterface::MORE_THAN_ONE_IN_COMPARISON,
                                    'value' => [
                                        "860308"
                                    ],
                                ],
                                
                            ],
                        ],
                    ]
                ]),
                false //behavior like in elastic search
            ],
        ];
    }
}
