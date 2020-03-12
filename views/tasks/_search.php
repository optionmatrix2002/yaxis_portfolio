<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use Yii;

/* @var $this yii\web\View */
/* @var $model app\models\search\TasksSearch */
/* @var $form yii\widgets\ActiveForm */
?>


<?php
$this->registerJs('
 $("document").ready(function()
 {
 
  $(\'.datetimepicker\').datetimepicker({
   format: \'DD-MM-YYYY\',
  });

 });
');

?>
<div class="audits-search">
    <div class="col-lg-12 col-md-12 col-sm-12 showfilter">

        <?php $form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
            'fieldConfig' => [
                'template' => "{input}",
                'options' => [
                    'tag' => 'false'
                ]
            ],
        ]); ?>
        <div class="col-lg-3 col-md-6 col-sm-12">
                <?= $form->field($model, 'task_id')->textInput(['class' => 'form-control', 'placeholder' => 'Task ID'])->label(false); ?>
        </div>


        <!--


   <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group nopadding">
            <?php
        echo $form->field($model, 'hotel_id')
            ->dropDownList(ArrayHelper::map(\app\models\Hotels::find()->where(['is_deleted' => 0])->all(), 'hotel_id', 'hotel_name'), [
                'prompt' => 'Office'
            ], [
                'class',
                'form-control'
            ])
            ->label(false);
        ?>
        </div>

    </div>

    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <?php
        echo $form->field($model, 'department_id')
            ->dropDownList(ArrayHelper::map(\app\models\Departments::find()->where(['is_deleted' => 0])->all(), 'department_id', 'department_name'), [
                'prompt' => 'Floor',
                'onchange' => '
                $.post( "' . Yii::$app->urlManager->createUrl('site/audits?id=') . '"+$(this).val(), function( data ) {
                  $( "select#auditssearch-audit_id" ).html( data );
                });
                    
            '
            ], [
                'class',
                'form-control'
            ])
            ->label(false);
        ?>
        </div>
   </div>

     -->
     <div class="col-lg-3 col-md-3 col-sm-3">
        <?= $form->field($model, 'location_id')
                        ->widget(Select2::classname(), ['data' => ArrayHelper::map(\app\models\Locations::find()
                            ->where(['is_deleted' => 0])->all(), 'location_id', 'locationCity.name'), 'showToggleAll' => false, 'language' => 'en', 'options' => ['multiple' => false, 'placeholder' => 'Select Location'], 'pluginOptions' => ['showToggleAll' => false, 'allowClear' => true]])
                        ->label(false); ?>

        
            </div>        
  
        <div class="col-lg-3 col-md-6 col-sm-12">

                <?php
                echo $form->field($model, 'department_id')
                    ->dropDownList([], [
                        'prompt' => 'Floor',
                        'onchange' => '
                $.post( "' . Yii::$app->urlManager->createUrl('site/audits?id=') . '"+$(this).val(), function( data ) {
                  $( "select#auditssearch-audit_id" ).html( data );
                });
                    
            '], [
                        'class',
                        'form-control'
                    ])
                    ->label(false);
                ?>
        </div>


        <div class="col-lg-3 col-md-6 col-sm-12">

                <?= $form->field($model, 'taskdoer_id')->widget(Select2::classname(), ['data' => ArrayHelper::map(\app\models\User::find()->where(['is_deleted' => 0, 'user_type' => 2])->all(), 'user_id', function ($element) {
                    return $element['first_name'] . ' ' . $element['last_name'];
                }), 'showToggleAll' => false, 'language' => 'en', 'options' => ['placeholder' => 'Select Auditor'], 'pluginOptions' => ['allowClear' => true]])->label(false); ?>

        </div>

        
        <div class="col-lg-3 margin-top-5 col-md-6 col-sm-12">
                <?= $form->field($model, 'start_date')->textInput(['class' => 'datetimepicker form-control', 'placeholder' => 'Start Date'])->label(false); ?>
        </div>

        <div class="col-lg-3 margin-top-5 col-md-6 col-sm-12">
                <?= $form->field($model, 'end_date')->textInput(['class' => 'datetimepicker form-control', 'placeholder' => 'End Date'])->label(false); ?>
        </div>
       

        <div class="col-lg-3 col-md-3 col-sm-3 pull-right text-right margin-top-5">
            <?= Html::a('Go', Yii::$app->urlManager->createUrl(['/tasks']), ['class' => 'btn btn-success']) ?>
            <?= Html::a('Clear', Yii::$app->urlManager->createUrl(['/tasks']), ['class' => 'btn btn-default']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
