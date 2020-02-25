<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
/**
 * This is the model class for table "tbl_yaxis_cabin".
 *
 * @property int $cabin_id
 * @property int $department_id
 * @property string $cabin_name
 * @property string $cabin_description
 * @property int $created_by
 * @property int $modified_by
 * @property string $created_date
 * @property string $modified_date
 * @property int $is_deleted
 * @property int $is_active
 *
 * @property Departments $department
 */
class Cabins extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_yaxis_cabin';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cabin_name', 'cabin_description' ], 'required'],
            [['department_id', 'is_deleted', 'is_active'], 'integer'],
            [['cabin_description'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['cabin_name'], 'string', 'max' => 100],
            [['department_id'], 'exist', 'skipOnError' => true, 'targetClass' => Departments::className(), 'targetAttribute' => ['department_id' => 'department_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'cabin_id' => 'Cabin ID',
            'department_id' => 'Department ID',
            'cabin_name' => 'Cabin Name',
            'cabin_description' => 'Cabin Description',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'is_deleted' => 'Is Deleted',
            'is_active' => 'Is Active',
        ];
    }

    public function behaviors()
    {
        parent::init();
        // TimestampBehavior also provides a method named touch() that allows you to assign the current timestamp to the specified attribute(s) and save them to the database. For example,
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => date('Y-m-d H:i:s')
            ],
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
                'value' => isset(Yii::$app->user) && isset(Yii::$app->user->id) ? Yii::$app->user->id : 1
            ]
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDepartment()
    {
        return $this->hasOne(Departments::className(), ['department_id' => 'department_id']);
    }

    public static function getHotelAndDepartmentDependCabin($cabin_id, $hotelId)
    {
        return self::find()->where(['cabin_id' => $cabin_id, 'hotel_id' => $hotelId])->one();
    }
}
