<?php
namespace app\controllers;
use app\models\GridColumns;
use yii\filters\AccessControl;
use app\components\AccessRule;
use Yii;
use app\models\Tasks;
use app\models\User;
use app\models\Audits;
use app\models\search\TasksSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;

/**
 * TasksController implements the CRUD actions for Tasks model.
 */
class TasksController extends Controller
{
    public $layout = 'dashboard_layout';
    public static $columnsArr=[
        'c1'=>true,
        'c3'=>true,
        'c4'=>true,
        'c5'=>true,
        'c6'=>true,
        'c7'=>true,
        'c8'=>true,
        'c9'=>true,
        'c14'=>true,
        'c10'=>true
    ];
    public static $tableColumns=[
        'c1'=>'Task ID',
        'c14'=>'Location',
        'c3'=>'Office',
        'c4'=>'Floor',
        'c5'=>'Cabin',
        'c6'=>'Frequency',
        'c7'=>'Internal Frequency',
        'c8'=>'Start Date',
        'c9'=>'End Date',
        'c10'=>'TaskDoer ID'
    ];
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {           
        $behaviors['access'] = [
        'class' => AccessControl::className(),
        'ruleConfig' => [
            'class' => AccessRule::className()
        ],
        'only' => [
            'create',
            'update',
            'delete',
            'index',
            'view',
            'view-task',
            'reports'
        ], // only be applied to
        'rules' => [
            [
                'allow' => Yii::$app->authManager->checkPermissionAccess('tasks/create'),
                'actions' => [
                    'create'
                ],
                'roles' => [
                    'rbac'
                ]
            ],
            [
                'allow' => Yii::$app->authManager->checkPermissionAccess('tasks/update'),
                'actions' => [
                    'update'
                ],
                'roles' => [
                    'rbac'
                ]
            ],
            [
                'allow' => Yii::$app->authManager->checkPermissionAccess('tasks'),
                'actions' => [
                    'index',
                ],
                'roles' => [
                    'rbac'
                ]
            ],
            [
                'allow' => Yii::$app->authManager->checkPermissionAccess('tasks/delete'),
                'actions' => [
                    'delete'
                ],
                'roles' => [
                    'rbac'
                ]
            ]
        ]
        ];   
        }
    
    public function actionTasks() {
        $searchModel = new Tasks();

        // echo "<pre>"; print_r(Yii::$app->request->queryParams); die();

        $dataProviderAudits = $searchModel->searchAudits(Yii::$app->request->queryParams);
    

        return $this->render('tasks', [
                    'searchModel' => $searchModel,
                  
        ]);
    }
    /**
     * Lists all Tasks models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TasksSearch();
        $dataScheduledProvider = $searchModel->searchArchivedTickets(Yii::$app->request->queryParams);
        $dataArchivedProvider = $searchModel->searchActiveTickets(Yii::$app->request->queryParams);

        $gridColumns = GridColumns::find()->where(['grid_type'=>'tasks'])->one();
        if($gridColumns){
            $gridColumns = $gridColumns->columns_data ? json_decode($gridColumns->columns_data)  : [];
            foreach(self::$columnsArr as $key=>$column){
                self::$columnsArr[$key]=false;
                if(in_array($key,$gridColumns)){
                    self::$columnsArr[$key]=true;
                }
            }
        }
        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataScheduledProvider' => $dataScheduledProvider,
                    'dataArchivedProvider' => $dataArchivedProvider,
                    'columnsArr'=> self::$columnsArr,
                    'tableColumnsArr'=>self::$tableColumns

        ]);
    }

    /**
     * Creates a new Tasks model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Tasks();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->task_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionHotel() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $location_id = $parents[0];
                header('Content-type: application/json');
                $out = Audits::getHotels($location_id);
                echo Json::encode([
                    'output' => $out,
                    'selected' => ''
                ]);
                return;
            }
        }
        echo Json::encode([
            'output' => '',
            'selected' => ''
        ]);
    }

    public function actionDepartment() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $hotel_id = $parents[0];
                header('Content-type: application/json');
                $deparments = Audits::getHotelDepartments($hotel_id);
                $resultArray = [];
                if (!empty($deparments)) {
                    foreach ($deparments as $department) {
                        $list = [];
                        if (isset($department['department'])) {
                            $list['id'] = $department['department_id'];
                            $list['name'] = $department['hotel']['hotel_name'] . '-' . $department['department']['department_name'];
                            $resultArray[] = $list;
                        }
                    }
                }
                echo Json::encode([
                    'output' => $resultArray,
                    'selected' => ''
                ]);
                return;
            }
        }
        echo Json::encode([
            'output' => '',
            'selected' => ''
        ]);
    }

    public function actionGetBackUpUsers() 
    {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $taskdoer_id = $parents[0];
                header('Content-type: application/json');
                $backUpUsers = User::getBackUpUsers($taskdoer_id);
                echo Json::encode([
                    'output' => $backUpUsers,
                    'selected' => ''
                ]);
                return;
            }
        }
        echo Json::encode([
            'output' => '',
            'selected' => ''
        ]);
    }

    /**
     * Updates an existing Tasks model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->task_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Tasks model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Tasks model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Tasks the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Tasks::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function actionSaveColumns() {
        $selectedColumns = isset($_POST['selected_columns']) ? $_POST['selected_columns'] : [];
        $grid_type = $_POST['grid_type'];
        if ($grid_type) {

            header('Content-type: application/json');
            $model=\app\models\GridColumns::find(['grid_type'=>$_POST['grid_type']])->one();
            if(!$model){
                $model = new \app\models\GridColumns(); 
            }
            $model->grid_type = $grid_type;
            $model->columns_data= json_encode($selectedColumns);
            if(!$model->save()){
                print_r($model->errors);
                exit;
            }
            echo Json::encode([
                'output' => true,
                'selected' => ''
            ]);
            return;
        }
        echo Json::encode([
            'output' => '',
            'selected' => ''
        ]);
    }   
}
