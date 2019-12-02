<?php
namespace micetm\conditionsBase\models\constructor\attributes\activeRecords\mongodb;

use micetm\conditionsBase\models\constructor\attributes\activeRecords\AttributeInterface;
use yii\mongodb\ActiveQuery;
use yii\mongodb\ActiveRecord;

/**
 * @property string  $title
 * @property string  $level
 * @property string  $type
 * @property string  $status
 * @property string  $key
 * @property bool    $multiple
 * @property array   $data
 */
class Attribute extends ActiveRecord implements AttributeInterface
{

    public function attributes()
    {
        return [
            '_id',
            'title',
            'level',
            'type',
            'key',
            'status',
            'multiple',
            'data',
        ];
    }

    /**
     * Returns repository
     *
     * @return ActiveQuery
     */
    public static function find()
    {
        return new ActiveQuery(get_called_class());
    }

    /**
     * Returns collection name
     *
     * @return string
     */
    public static function collectionName()
    {
        return \Yii::$app->params['constructorAttributesCollectionName'];
    }

    /**
     * Get Db object
     *
     * @return null|object
     */
    public static function getDb()
    {
        return \Yii::$app->get('constructorAttributesDb');
    }

    public function getType(): string
    {
        return $this->type;
    }
}
