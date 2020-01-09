<?php

namespace app\models;

use Yii;
use DateTime;

/**
 * This is the model class for table "{{%preferences}}".
 *
 * @property integer $preferences_id
 * @property string $preferences_name
 * @property string $preferences_lable
 * @property string $preferences_value
 * @property string $preferences_description
 * @property string $preferences_type
 * @property string $preferences_options
 * @property integer $created_by
 * @property integer $update_by
 * @property string $created_at
 * @property string $update_at
 *
 * @property User $createdBy
 * @property User $updateBy
 */
class Preferences extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%preferences}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [
                [
                    'preferences_name',
                    'preferences_value',
                    'preferences_description',
                    'preferences_type',
                    'created_by',
                    'update_by',
                    'created_at',
                    'update_at'
                ],
                'required'
            ],
            [
                [
                    'preferences_description',
                    'preferences_options'
                ],
                'string'
            ],
            [
                [
                    'created_by',
                    'update_by'
                ],
                'integer'
            ],
            [
                [
                    'created_at',
                    'update_at'
                ],
                'safe'
            ],
            [
                [
                    'preferences_name',
                    'preferences_value',
                    'preferences_type'
                ],
                'string',
                'max' => 100
            ],
            [
                [
                    'created_by'
                ],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::className(),
                'targetAttribute' => [
                    'created_by' => 'user_id'
                ]
            ],
            [
                [
                    'update_by'
                ],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::className(),
                'targetAttribute' => [
                    'update_by' => 'user_id'
                ]
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'preferences_id' => Yii::t('app', 'Preferences ID'),
            'preferences_name' => Yii::t('app', 'Preferences Name'),
            'preferences_lable' => Yii::t('app', 'Preferences Lable'),
            'preferences_value' => Yii::t('app', 'Preferences Value'),
            'preferences_description' => Yii::t('app', 'Preferences Description'),
            'preferences_type' => Yii::t('app', 'Preferences Type'),
            'preferences_options' => Yii::t('app', 'Preferences Options'),
            'created_by' => Yii::t('app', 'Created By'),
            'update_by' => Yii::t('app', 'Update By'),
            'created_at' => Yii::t('app', 'Created At'),
            'update_at' => Yii::t('app', 'Update At')
        ];
    }

    /**
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy() {
        return $this->hasOne(User::className(), [
                    'user_id' => 'created_by'
        ]);
    }

    /**
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUpdateBy() {
        return $this->hasOne(User::className(), [
                    'user_id' => 'update_by'
        ]);
    }

    public static function getSelectAuditorReminder() {
        return $newValueArry = [
            "1" => "1",
            "2" => "2"
        ];
    }

    public static function getSelectEventReminder() {
        return $newValueArry = [
            "1" => "1",
            "2" => "2"
        ];
    }

    public static function getSelectTimeSlotsFrom() {
        return $newValueArry = [
            "0" => "12 AM",
            "1" => "1 AM",
            "2" => "2 AM",
            "3" => "3 AM",
            "4" => "4 AM",
            "5" => "5 AM",
            "6" => "6 AM",
            "7" => "7 AM",
            "8" => "8 AM",
            "9" => "9 AM",
            "10" => "10 AM",
            "11" => "11 AM",
            "12" => "12 PM",
            "13" => "1 PM",
            "14" => "2 PM",
            "15" => "3 PM",
            "16" => "4 PM",
            "17" => "5 PM",
            "18" => "6 PM",
            "19" => "7 PM",
            "20" => "8 PM",
            "21" => "9 PM",
            "22" => "10 PM",
            "23" => "11 PM",
            "24" => "12 PM"
        ];
    }

    /**
     * Get preference value by name of the preference
     *
     * @param
     *            $name
     * @return mixed|string
     */
    public static function getPrefValByName($name) {
        $model = Preferences::find()->select([
                    'preferences_value',
                    'preferences_type'
                ])
                ->where([
                    'preferences_name' => $name
                ])
                ->one();
        if ($model->preferences_type == 'multipleselect') {
            $result = json_decode($model->preferences_value);
        } else {
            $result = $model->preferences_value;
        }
        return $result;
    }

    /**
     * Get preference value by name of the preference
     *
     * @param
     *            $name
     * @return mixed|string
     */
    public static function getAuditSlot() {
        $return = [];
        $model = Preferences::find()->select([
                    'preferences_value',
                    'preferences_type'
                ])
                ->where([
                    'preferences_id' => 11
                ])
                ->one();
        if (!$model) {
            throw new \Exception('Error in Preference Settings for Hourly Audit Slot');
        }
        $times = json_decode($model->preferences_value, true);
        $fromTime = $times['from'];
        $toTime = $times['to'];
        $date1 = new DateTime($fromTime);
        $date2 = new DateTime($toTime);
        $interval = $date1->diff($date2);

        $return['count'] = $interval->h;
        $return['start_time'] = $fromTime;
        return $interval->h ? $return : false;
    }

    public static function getSelectNewValueArry() {
        return $newValueArry = [
            "10" => "10",
            "20" => "20",
            "30" => "30",
            "40" => "40",
            "50" => "50"
        ];
    }

    public static function getSelectRatingSliderArry() {
        return $newValueArry = [
            "0" => "0",
            "1" => "1",
            "2" => "2",
            "3" => "3",
            "4" => "4",
            "5" => "5",
            "6" => "6",
            "7" => "7",
            "8" => "8",
            "9" => "9",
            "10" => "10"
        ];
    }

}
