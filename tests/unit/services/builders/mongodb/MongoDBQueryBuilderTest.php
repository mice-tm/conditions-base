<?php

namespace test\unit\models\constructor\services\builders\mongodb;

use micetm\conditionsBase\exceptions\WrongComparison;
use micetm\conditionsBase\models\AttributeInterface;
use micetm\conditionsBase\models\constructor\conditions\Condition;
use micetm\conditionsBase\services\builders\mongodb\comparisons\ComparisonManager;
use micetm\conditionsBase\services\builders\mongodb\QueryBuilder;
use PHPUnit\Framework\TestCase;

class MongoDBQueryBuilderTest extends TestCase
{
    /**
     * @dataProvider providerUnarySuccess
     */
    public function testUnaryQuerySuccessCreation(Condition $condition, $expectedQuery)
    {
        $queryBuilder = new QueryBuilder(new ComparisonManager());
        $query = $queryBuilder->create([$condition]);
        $this->assertEquals($query, $expectedQuery);
    }

    public function providerUnarySuccess()
    {
        $now = time();
        return [
            [
                'condition' => new Condition([
                    'attribute' => 'items.id',
                    'comparison' => AttributeInterface::EQUAL_TO_COMPARISON,
                    'value' => 55555
                ]),
                'expectedQuery' => [
                    'items.id' => 55555
                ],
            ],
            [
                'condition' => new Condition([
                    'attribute' => 'start_at',
                    'comparison' => AttributeInterface::GREATER_THAN_COMPARISON,
                    'value' => $now
                ]),
                'expectedQuery' => [
                    'start_at' => ['$gt' => $now],
                ],
            ],
            [
                'condition' => new Condition([
                    'attribute' => 'start_at',
                    'comparison' => AttributeInterface::GREATER_THAN_OR_EQUAL_TO_COMPARISON,
                    'value' => $now
                ]),
                'expectedQuery' => [
                    'start_at' => ['$gte' => $now],
                ],
            ],
            [
                'condition' => new Condition([
                    'attribute' => 'start_at',
                    'comparison' => AttributeInterface::LESS_THAN_COMPARISON,
                    'value' => $now
                ]),
                'expectedQuery' => [
                    'start_at' => ['$lt' => $now],
                ],
            ],
            [
                'condition' => new Condition([
                    'attribute' => 'start_at',
                    'comparison' => AttributeInterface::LESS_THAN_OR_EQUAL_TO_COMPARISON,
                    'value' => $now
                ]),
                'expectedQuery' => [
                    'start_at' => ['$lte' => $now],
                ],
            ],
            [
                'condition' => new Condition([
                    'attribute' => 'items.properties.topic',
                    'comparison' => AttributeInterface::EQUAL_TO_COMPARISON,
                    'value' => ['Cats', 'Animals'],
                ]),
                'expectedQuery' => [
                    'items.properties.topic' => [
                        '$in' => ['Cats', 'Animals']
                    ]
                ],
            ],
            [
                'condition' => new Condition([
                    'attribute' => 'items.properties.topic',
                    'comparison' => AttributeInterface::MORE_THAN_ONE_IN_COMPARISON,
                    'value' => ['Cats', 'Animals'],
                ]),
                'expectedQuery' => [
                    'items.properties.topic' => [
                        '$in' => ['Cats', 'Animals']
                    ]
                ],
            ],
            [
                'condition' => new Condition([
                    'attribute' => 'items.properties.topic',
                    'comparison' => AttributeInterface::LIKE_COMPARISON,
                    'value' => 'cats',
                ]),
                'expectedQuery' => [
                    '$text' => ['$search' => 'cats']
                ],
            ],
            [
                'condition' => new Condition([
                    'attribute' => 'items.properties.topic',
                    'comparison' => AttributeInterface::LIKE_COMPARISON,
                    'value' => 'Cats',
                ]),
                'expectedQuery' => [
                    '$text' => ['$search' => 'Cats']
                ],
            ],
        ];
    }


    public function testUnaryQueryCreationFail()
    {
        $condition = new Condition([
            'attribute' => 'items.id',
            'comparison' => 'not_existing_comparison',
            'value' => 55555
        ]);
        $queryBuilder = new QueryBuilder(new ComparisonManager());
        $this->expectException(WrongComparison::class);
        $queryBuilder->create([$condition]);
    }

    public function testUnaryQueryCreationEmpty1()
    {
        $condition = new Condition([]);
        $queryBuilder = new QueryBuilder(new ComparisonManager());
        $query = $queryBuilder->create([$condition]);
        $this->assertEquals($query, []);
    }

    public function testUnaryQueryCreationEmpty2()
    {
        $queryBuilder = new QueryBuilder(new ComparisonManager());
        $query = $queryBuilder->create(null);
        $this->assertEquals($query, []);
    }

    /**
     * @dataProvider providerComplexQuerySuccessCreation
     */
    public function testComplexQuerySuccessCreation($condition, $expectedQuery)
    {
        $queryBuilder = new QueryBuilder(new ComparisonManager());
        $query = $queryBuilder->create($condition);
        codecept_debug(json_encode($query));
        $this->assertEquals($query, $expectedQuery);
    }

    public function providerComplexQuerySuccessCreation()
    {
        $now = time();

        return [
            [
                'condition' => [new Condition([
                    'operator' => Condition::OPERATOR_AND,
                    'conditions' => [
                        [
                            'attribute' => 'start_at',
                            'comparison' => AttributeInterface::GREATER_THAN_COMPARISON,
                            'value' => $now
                        ],
                        [
                            'attribute' => 'items.properties.topic',
                            'comparison' => AttributeInterface::LIKE_COMPARISON,
                            'value' => 'cats',
                        ]
                    ]
                ])],
                'expectedQuery' => [
                    '$and' => [
                        [
                            'start_at' => ['$gt' => $now]
                        ],
                        [
                            '$text' => ['$search' => 'cats']
                        ]
                    ]
                ],
            ],
            [
                'condition' => [
                    new Condition([
                        'attribute' => 'start_at',
                        'comparison' => AttributeInterface::GREATER_THAN_COMPARISON,
                        'value' => $now
                    ]),
                    new Condition([
                        'attribute' => 'start_at',
                        'comparison' => AttributeInterface::LESS_THAN_COMPARISON,
                        'value' => $now
                    ]),
                ],
                'expectedQuery' => [
                    '$or' => [
                        ['start_at' => ['$gt' => $now]],
                        ['start_at' => ['$lt' => $now]]
                    ]
                ],
            ],
            [
                'condition' => [new Condition([
                    'operator' => Condition::OPERATOR_AND,
                    'conditions' => [
                        [
                            'attribute' => 'flatConditions',
                            'comparison' => AttributeInterface::EMBEDDED_COMPARISON,
                            'value' => [
                                'attribute' => 'cart.attributes.affiliate',
                                'value' => 'promkod',
                            ]
                        ],
                        [
                            'attribute' => 'start_at',
                            'comparison' => AttributeInterface::LESS_THAN_OR_EQUAL_TO_COMPARISON,
                            'value' => $now,
                        ]
                    ]
                ])],
                'expectedQuery' => [
                    '$and' => [
                        [
                            'flatConditions' => [
                                '$elemMatch' => [
                                    'attribute' => 'cart.attributes.affiliate',
                                    'value' => 'promkod'
                                ]
                            ]
                        ],
                        ['start_at' => ['$lte' => $now]]
                    ]
                ],
            ],
        ];
    }
}
