<?php
use yii\helpers\ArrayHelper;
use kartik\depdrop\DepDrop;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Tasks */
/* @var $form yii\widgets\ActiveForm */
$this->registerJs('
 $("document").ready(function()
 {
 
  $(\'.datetimepicker\').datetimepicker({
   format: \'DD-MM-YYYY\',
  });

 });
');
?>
<div class="container-fluid">
    <h2><?= $this->title; ?> </h2>
</div>
<div class="wa-notification wa-notification-alt">
    <span class="wa-iconBoxed">
        <span class="fa fa-file-text-o header-icon-fontcolor"></span>
    </span>
    <p id="description-text">
       Task data can be created here
    </p>
</div>
<div class="tasks-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="col-sm-12 col-lg-12 col-md-12 margintop10">
            <div class="col-sm-3 col-lg-3 col-md-3">
                <label class="required-label">Location :</label>
            </div>
            <div class="col-sm-9 col-lg-9 col-md-9">
                <div class="input-group col-sm-6">
                    <?= $form->field($model, 'location_id')->dropDownList(ArrayHelper::map(\app\models\Locations::find()->where(['is_deleted' => 0])->all(), 'location_id', 'locationCity.name'), ['prompt' => 'Select Location', 'disabled' => !$model->isNewRecord])->label(false); ?>
                </div>
            </div>
        </div>


 <div class="col-sm-12 col-lg-12 col-md-12 margintop10">
            <div class="col-sm-3 col-lg-3 col-md-3">
                <label class="required-label">Office :</label>
            </div>
            <div class="col-sm-9 col-lg-9 col-md-9">
                <div class="input-group col-sm-6">
                <?php
                    $hotels = \app\models\Tasks::getHotels($model->location_id);
                    $hotels = ArrayHelper::map($hotels, 'id', 'name');
                    $hotels = $hotels ? $hotels : [];

                    if ($model->isNewRecord) {
                        echo $form->field($model, 'hotel_id')
                                ->widget(DepDrop::classname(), [
                                    'options' => [
                                        'id' => 'hotel_id'
                                    ],
                                    'data' => $hotels,
                                    'pluginOptions' => [
                                        'depends' => [
                                            'tasks-location_id'
                                        ],
                                        'placeholder' => 'Select Office',
                                        'url' => Url::to([
                                            'tasks/hotel'
                                        ])
                                    ]
                                ])
                                ->label(false);
                    } else {
                        echo $form->field($model, 'hotel_id')
                                ->dropDownList($hotels, [
                                    'disabled' => !$model->isNewRecord
                                ])
                                ->label(false);
                    }
                    ?>                </div>
            </div>
        </div>
        
    <div class="col-sm-12 col-lg-12 col-md-12 margintop10">
            <div class="col-sm-3 col-lg-3 col-md-3">
                <label class="required-label">Floor :</label>
            </div>
            <div class="col-sm-9 col-lg-9 col-md-9">
                <div class="input-group col-sm-6">
                <?php
                    $departments = \app\models\Audits::getHotelDepartments($model->hotel_id);
                    $departments = ArrayHelper::map($departments, 'department_id', function ($element) {
                                return $element['hotel']['hotel_name'] . '-' . $element['department']['department_name'];
                            });

                    $departments = $departments ? $departments : [];

                    if ($model->isNewRecord) {
                        echo $form->field($model, 'department_id')
                                ->widget(DepDrop::classname(), [
                                    'options' => [
                                        'id' => 'department_id'
                                    ],
                                    'data' => $departments,
                                    'pluginOptions' => [
                                        'depends' => [
                                            'hotel_id'
                                        ],
                                        'placeholder' => 'Select Floor',
                                        'url' => Url::to([
                                            'audits/department'
                                        ])
                                    ]
                                ])
                                ->label(false);
                    } else {

                        echo $form->field($model, 'department_id')
                                ->dropDownList($departments, [
                                    'disabled' => 'disabled'
                                ])
                                ->label(false);
                    }
                    ?>
                </div>
            </div>
        </div>

    <div class="col-sm-12 col-lg-12 col-md-12 margintop10">
            <div class="col-sm-3 col-lg-3 col-md-3">
                <label class="required-label">Checklist :</label>
            </div>
            <div class="col-sm-9 col-lg-9 col-md-9">
                <div class="input-group col-sm-6">
                    <?= $form->field($model, 'checklist_id')->dropDownList(ArrayHelper::map(\app\models\Checklists::find()->where(['is_deleted' => 0])->all(), 'checklist_id', 'cl_name'), ['prompt' => 'Select Checklist', 'disabled' => !$model->isNewRecord])->label(false); ?>
                </div>
            </div>
        </div>

   <div class="col-sm-12 col-lg-12 col-md-12">
            <div class="col-sm-3 col-lg-3 col-md-3">
                <label  class = "required-label">Frequency :</label>
            </div>
            <div class="col-sm-9 col-lg-9 col-md-9">
                <div class="input-group col-sm-6">
                    <div class="col-sm-12  radio-button-padding"  id="auditspan">
                        <?= $form->field($model, 'frequency')->dropDownList(ArrayHelper::map(\app\models\Interval::find()->asArray()->all(), 'interval_id', 'interval_name'), ['prompt' => 'Select Frequency'], ['class' => 'form-control'])->label(false); ?>
                    </div>                       
                </div>
            </div>
        </div>
   
   <div class="col-sm-12 col-lg-12 col-md-12 margintop10">
            <div class="col-sm-3 col-lg-3 col-md-3">
                <label class="required-label">Taskdoer :</label>
            </div>
            <div class="col-sm-9 col-lg-9 col-md-9">
                <div class="input-group col-sm-6">
                    <?= $form->field($model, 'taskdoer_id')->dropDownList(ArrayHelper::map(\app\models\User::find()->where(['user_type'=>4,'is_deleted' => 0])->all(), 'user_id', 'taskdoer_username'), ['prompt' => 'Select taskdoer', 'disabled' => !$model->isNewRecord])->label(false); ?>
                </div>
            </div>
        </div>
  
   <div class="col-sm-12 margintop10">
                <div class="col-sm-3 col-lg-3 col-md-3">
                    <label class="required-label">Start Date :</label>
                </div>
                <div class="col-sm-9 col-lg-9 col-md-9">
                    <div class="input-group col-sm-6">
                        <?= $form->field($model, 'start_date')->textInput(['value' => $model->start_date ? Yii::$app->formatter->asDate($model->start_date, "php:d-m-Y") : '', 'class' => 'datetimepicker form-control', 'id' => 'dateStart', 'disabled' => !$model->isNewRecord])->label(false); ?>
                    </div>
                </div>
            </div>

  <div class="col-sm-12 margintop10">
                <div class="col-sm-3 col-lg-3 col-md-3">
                    <label class="required-label">End Date :</label>
                </div>
                <div class="col-sm-9 col-lg-9 col-md-9">
                    <div class="input-group col-sm-6">
                        <?= $form->field($model, 'end_date')->textInput(['value' => Yii::$app->formatter->asDate($model->end_date, 'php:d-m-Y'), 'class' => 'datetimepicker form-control', 'id' => 'dateEnd',])->label(false); ?>
                    </div>
                </div>
            </div>

  <div class="col-sm-12 margintop10">
                <div class="col-sm-3 col-lg-3 col-md-3">
                    <label class="required-label">Back up user</label>
                </div>
                <div class="col-sm-9 col-lg-9 col-md-9">
                    <div class="input-group col-sm-6">
                    <?php
                    $backupuser = \app\models\User::getBackUpUsers($model->taskdoer_id);
                    $backupuser = ArrayHelper::map($backupuser, 'back_up_user', function ($element) {
                                return $element['taskdoer']['taskdoer_username'] . '-' . $element['backupuser']['back_up_user'];
                            });

                    $backupuser = $backupuser ? $backupuser : [];
                    if ($model->isNewRecord) {
                        echo $form->field($model, 'back_up_user')
                                ->widget(DepDrop::classname(), [
                                    'options' => [
                                        'id' => 'tasks-back_up_user'
                                    ],
                                    'data' => $backupuser,
                                    'pluginOptions' => [
                                        'depends' => [
                                            'tasks-taskdoer_id'
                                        ],
                                        'placeholder' => 'Select Back-up',
                                        'url' => Url::to([
                                            'tasks/get-back-up-users'
                                        ])
                                    ]
                                ])
                                ->label(false);
                    } else {
                        echo $form->field($model, 'back_up_user')
                                ->dropDownList($departments, [
                                    'disabled' => 'disabled'
                                ])
                                ->label(false);
                    }
                    ?>
                    </div>
                </div>
            </div>

    
            <div class="col-lg-12 col-md-12 col-sm-12 margintop10">
            <div class="col-lg-3 col-md-3 col-sm-3">
                <label></label>
            </div>
            <div class="col-lg-9 col-md-9 col-sm-9">
                <div class="col-sm-6 input-group">
                    <?= Html::submitButton($model->isNewRecord ? 'Save' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-success']) ?>
                    <?= Html::a('Cancel', ['/user'], ['class' => 'btn btn-default mg-left-10']); ?>
                </div>
            </div>
        </div>

    <?php ActiveForm::end(); ?>

</div>
