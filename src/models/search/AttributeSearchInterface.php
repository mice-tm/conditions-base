<?php
namespace micetm\conditionsBase\models\search;

use yii\db\ActiveQueryInterface;

interface AttributeSearchInterface
{
    public function search(): ActiveQueryInterface;
}
