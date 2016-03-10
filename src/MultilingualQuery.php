<?php
namespace frontend\components\multilingual;

use yii\mongodb\ActiveQuery;

class MultilingualQuery extends ActiveQuery
{
    use MultilingualTrait;
}