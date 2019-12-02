<?php

namespace micetm\conditionsBase\services;

use micetm\conditionsBase\exceptions\AttributeNotFoundException;
use micetm\conditionsBase\models\constructor\conditions\Condition;
use micetm\conditionsBase\models\constructor\attributes\AbstractAttribute;
use micetm\conditionsBase\models\constructor\attributes\activeRecords\Attribute;
use micetm\conditionsBase\models\constructor\attributes\activeRecords\AttributeInterface;
use micetm\conditionsBase\models\search\AttributeSearchInterface;
use yii\helpers\ArrayHelper;

class ConstructorService
{
    /**
     * @var AttributeInterface[]
     */
    protected $availableAttributes;

    /**
     * @var array
     */
    protected $attributes;

    /**
     * ConstructorService constructor.
     * @param AttributeSearchInterface $attributeSearch
     * @param array $attributes
     */
    public function __construct(AttributeSearchInterface $attributeSearch, array $attributes)
    {
        $this->attributes = $attributes;
        $this->availableAttributes = $this->initAvailableAttributesList($attributeSearch);
    }

    /**
     * @return AbstractAttribute[]
     */
    public function getAvailableAttributes(\ArrayObject $conditions = null)
    {
        if ($conditions) {
            $this->initCustomAttributes($conditions);
        }
        return $this->availableAttributes;
    }

    /**
     * Retrives custom attributes from Conditions
     * @param \ArrayObject|null $conditions
     */
    protected function initCustomAttributes(\ArrayObject $conditions = null)
    {
        foreach (iterator_to_array($conditions->getIterator()) as $condition) {
            /** @var Condition $condition */
            if (!$condition->isUnary()) {
                $this->initCustomAttributes($condition->conditionModels);
                continue;
            }

            $this->initCustomAttributeIfNotExist($condition);
        }
    }

    /**
     * @param AttributeInterface $attribute
     * @return object
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    protected function initAttribute(AttributeInterface $attribute)
    {
        $className = $this->attributes[AbstractAttribute::TYPE_DEFAULT];

        if (!empty($this->attributes[$attribute->getType()])) {
            $className = $this->attributes[$attribute->getType()];
        }
        $model = \Yii::$container->get($className);
        $model->load($attribute->toArray(), '');
        return $model;
    }

    /**
     * @param $title
     * @return AttributeInterface|mixed
     */
    public function getAttribute($title)
    {
        if (!isset($this->availableAttributes[$title])) {
            throw new AttributeNotFoundException($title);
        }
        return $this->availableAttributes[$title];
    }

    /**
     * @param array $rawData
     * @return array
     */
    public function createConditionModels(array $rawData)
    {
        $result = [];

        if (!is_array($rawData['conditions'])) {
            return $result;
        }

        foreach ($rawData['conditions'] as $rawCondition) {
            $condition = new Condition();
            $condition->attributes = $rawCondition;

            if ($condition->attribute) {
                $this->initCustomAttributeIfNotExist($condition);
                $condition->value = $this->getAttribute($condition->attribute)
                    ->value($condition->value);
            }

            $condition->conditionModels = $this->createConditionModels($rawCondition);
            $result[] = $condition;
        }

        return $result;
    }

    /**
     * @param AttributeSearchInterface $attributeSearch
     * @return array
     */
    private function initAvailableAttributesList(AttributeSearchInterface $attributeSearch)
    {
        return ArrayHelper::index(array_map(function (AttributeInterface $attribute) {
            return $this->initAttribute($attribute);
        }, $attributeSearch->search()->all()), 'key');
    }

    /**
     * Init custom attribute if it is set but not in attributes repository
     * @param Condition $condition
     */
    private function initCustomAttributeIfNotExist(Condition $condition)
    {
        if (!empty($this->availableAttributes[$condition->attribute])) {
            return ;
        }

        $this->availableAttributes[$condition->attribute] = $this->initAttribute(
            new Attribute([
                'title' => $condition->attribute,
                'level' => 'not defined',
                'type' => 'default',
                'key' => $condition->attribute,
                'status' => AbstractAttribute::STATUS_INACTIVE,
                'multiple' => is_array($condition->value),
            ])
        );
    }
}
