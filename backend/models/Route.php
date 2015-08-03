<?php
namespace backend\models;
/**
 * Route
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class Route extends \yii\base\Model
{
    const RULE_NAME = 'route_rule';
    /**
     * @var string Route value. 
     */
    public $route;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return[
            [['route'], 'required'],
            [['route'],'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'route' => \Yii::t('app', 'Route'),
        ];
    }
}
