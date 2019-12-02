<?php

namespace micetm\conditionsBase\repositories;

use yii\base\BaseObject;
use yii\db\ActiveQueryInterface;

class AttributesRepository extends BaseObject
{

    /**
     * @var ActiveQueryInterface
     */
    public $attributesQuery;

    public function __construct($attributesQuery, $config = [])
    {
        $this->attributesQuery = $attributesQuery;
        parent::__construct($config);
    }

    /**
     * @return AttributeInterface[]
     */
    public function getAvailableAttributes()
    {
        return $this->attributesQuery
            ->where(['status' => AttributeInterface::STATUS_ACTIVE ])
            ->orderBy(['key' => SORT_ASC])
            ->limit(50)
            ->all();
    }

    /**
     * @param $key
     * @return AttributeInterface
     */
    public function getAttribute($key)
    {
        return $this->attributesQuery
            ->where(['key' => $key ])
            ->one();
    }
}
