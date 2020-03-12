<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_gp_tasks".
 *
 * @property int $task_id
 * @property int $hotel_id office id
 * @property int $department_id floor id
 * @property int $checklist_id
 * @property int $location_id city id
 * @property int $frequency 0-hourly 1-daily 2-weekly
 * @property int $taskdoer_id user ID of taskdoer
 * @property string $start_date
 * @property string $end_date
 * @property int $back_up_user
 * @property int $created_by id of created user
 * @property string $created_at
 * @property int $updated_by id of user who updated
 * @property string $updated_at
 * @property int $is_deleted
 *
 * @property Checklists $checklist
 * @property User $createdBy
 * @property Departments $department
 * @property Hotels $hotel
 * @property UserLocations $location
 * @property User $taskdoer
 * @property User $updatedBy
 */
class Tasks extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_gp_tasks';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['hotel_id', 'department_id', 'checklist_id', 'location_id', 'frequency', 'taskdoer_id', 'start_date', 'end_date', 'back_up_user', 'created_by', 'created_at', 'updated_by', 'updated_at', 'is_deleted'], 'required'],
            [['hotel_id', 'department_id', 'checklist_id', 'location_id', 'frequency', 'taskdoer_id', 'back_up_user', 'created_by', 'updated_by', 'is_deleted','status'], 'integer'],
            [['start_date', 'end_date', 'created_at', 'updated_at'], 'safe'],
            [['checklist_id'], 'exist', 'skipOnError' => true, 'targetClass' => Checklists::className(), 'targetAttribute' => ['checklist_id' => 'checklist_id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'user_id']],
            [['department_id'], 'exist', 'skipOnError' => true, 'targetClass' => Departments::className(), 'targetAttribute' => ['department_id' => 'department_id']],
            [['hotel_id'], 'exist', 'skipOnError' => true, 'targetClass' => Hotels::className(), 'targetAttribute' => ['hotel_id' => 'hotel_id']],
            [['taskdoer_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['taskdoer_id' => 'user_id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'user_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'task_id' => 'Task ID',
            'hotel_id' => 'Hotel ID',
            'department_id' => 'Department ID',
            'checklist_id' => 'Checklist ID',
            'location_id' => 'Location ID',
            'frequency_interval' => 'Frequency Interval',
            'frequency' => 'Frequency',
            'taskdoer_id' => 'Taskdoer ID',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'back_up_user' => 'Back Up User',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
            'is_deleted' => 'Is Deleted',
            'status' => 'Status'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChecklist()
    {
        return $this->hasOne(Checklists::className(), ['checklist_id' => 'checklist_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['user_id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDepartment()
    {
        return $this->hasOne(Departments::className(), ['department_id' => 'department_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHotel()
    {
        return $this->hasOne(Hotels::className(), ['hotel_id' => 'hotel_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocation()
    {
        return $this->hasOne(UserLocations::className(), ['location_id' => 'location_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTaskdoer()
    {
        return $this->hasOne(User::className(), ['user_id' => 'taskdoer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['user_id' => 'updated_by']);
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['user_id' => 'updated_by']);
    }

    public function getUserLocations()
    {
        return $this->hasMany(UserLocations::className(), [
            'location_id' => 'task_id'
        ]);
    }

    public function getUsers()
    {
        return $this->hasMany(User::className(), [
            'user_id' => 'taskdoer_username'
        ]);
    }

    public function getUserLocationsData()
    {
        $locationsArr=[];
        foreach($this->userLocations as $row){
            $locationsArr[] = $row->location->locationCity->name;
        }
        return implode(',', $locationsArr);
    }

    public static function getHotels($locationId)
    {
        $hotels = [];
        if ($locationId) {
            $hotels = Hotels::find()->where([
                'location_id' => $locationId,
                'is_deleted' => 0
            ])
                ->select([
                    'id' => 'hotel_id',
                    'name' => 'hotel_name'
                ])
                ->asArray()
                ->all();
        }
        return $hotels;
    }
    public static function getHotelDepartments($hotelId)
    {
        $deparments = [];
        if ($hotelId) {
            $deparments = HotelDepartments::find()->joinWith([
                'department' => function ($query) {
                    $query->select([
                        'department_name',
                        'department_id'
                    ]);
                },
                'hotel'
            ])
                ->where([
                    HotelDepartments::tableName() . '.hotel_id' => $hotelId,
                    HotelDepartments::tableName() . '.is_deleted' => 0
                ])
                ->select([
                    HotelDepartments::tableName() . '.id',
                    HotelDepartments::tableName() . '.department_id',
                    HotelDepartments::tableName() . '.hotel_id'
                ])
                ->asArray()
                ->all();
        }
        return $deparments;
    }
}
