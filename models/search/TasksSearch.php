<?php
use Yii;
namespace app\models\search;
use app\models\Preferences;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Tasks;
use app\models\User;

/**
 * TasksSearch represents the model behind the search form of `app\models\Tasks`.
 */
class TasksSearch extends Tasks
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['task_id', 'hotel_id', 'department_id', 'checklist_id', 'location_id', 'frequency', 'taskdoer_id', 'back_up_user', 'is_deleted', 'created_by', 'updated_by'], 'integer'],
            [['start_date', 'end_date', 'created_at','status', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchArchivedTickets($params)
    {
        $query = Tasks::find()->where(['status'=>0]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'task_id' => $this->task_id,
            'hotel_id' => $this->hotel_id,
            'department_id' => $this->department_id,
            'checklist_id' => $this->checklist_id,
            'location_id' => $this->location_id,
            'frequency' => $this->frequency,
            'taskdoer_id' => $this->taskdoer_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'back_up_user' => $this->back_up_user,
            'is_deleted' => $this->is_deleted,
            'status' => $this->status,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
        ]);

        return $dataProvider;
    }

    public function searchActiveTickets($params)
    {
        $query = Tasks::find()->joinWith('location')->joinWith('hotel')->joinWith('department')->joinWith('taskdoer')->where(['status'=>1]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'task_id' => $this->task_id,
            'hotel_id' => $this->hotel_id,
            'department_id' => $this->department_id,
            'checklist_id' => $this->checklist_id,
            'location_id' => $this->location_id,
            'frequency' => $this->frequency,
            'taskdoer_id' => $this->taskdoer_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'back_up_user' => $this->back_up_user,
            'is_deleted' => $this->is_deleted,
            'status' => $this->status,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
        ]);

        return $dataProvider;
    }
}
