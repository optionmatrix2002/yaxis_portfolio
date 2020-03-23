<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\search\AuditsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<?php
$this->registerJs('
 $("document").ready(function()
 {
 
  $(\'.datetimepicker\').datetimepicker({
   format: \'DD-MM-YYYY\',
  });

$(\'#auditssearch-hotel_id\').on("change",function(){
$.post( "' . Yii::$app->urlManager->createUrl('site/departments?quickView=1&id=') . '"+$(this).val(), function( data ) {
                  $( "select#auditssearch-department_id" ).html( data );
                });
});

function getFloors(){
var officeId = $(\'#auditssearch-hotel_id\').val();
 $.post( "' . Yii::$app->urlManager->createUrl('site/departments?quickView=1&id=') . '"+officeId, function( data ) {
                  $( "select#auditssearch-department_id" ).html(data);
                    getAudits();
                });
}
$(\'#auditssearch-department_id\').on("change",function(){

                $.post( "' . Yii::$app->urlManager->createUrl('site/audits?id=' . '"+$(this).val()+"' . '&hotel_id=') . '"+$("#auditssearch-hotel_id").val(), function( data ) {
                  $( "select#auditssearch-audit_id" ).html( data );
                });
 });
 
function getAudits(){
var officeId = $(\'#auditssearch-hotel_id\').val();
var depId = $(\'#auditssearch-department_id\').val();
                $.post( "' . Yii::$app->urlManager->createUrl('site/audits?id=' . '"+depId+"' . '&hotel_id=') . '"+officeId, function( data ) {
                  $( "select#auditssearch-audit_id" ).html( data );
                    submitQuickViewForm();
                });
}
getFloors();

 });
');


$this->registerJs('
 $(".clear-all-btn").click(function()
 {
    
      window.location.href="dashboard#quickview";
 });
');
?>
<div class="col-md-12 showfilter">
    <div class="audits-search">

        <?php $form = ActiveForm::begin(['id' => 'form-fetch-audits', 'fieldConfig' => [
            'template' => "{input}",
            'options' => [
                'tag' => 'false'
            ]
        ], 'enableClientValidation' => true, 'enableAjaxValidation' => false,
            'action' => ['/site/compareaudits'],
            'options' => ['enctype' => 'multipart/form-data']]); ?>
              <div class="col-lg-3 col-md-6 col-sm-12">
        <?= $form->field($model, 'location_id')
                        ->widget(Select2::classname(), ['data' => ArrayHelper::map(\app\models\Locations::find()
                            ->where(['is_deleted' => 0])->all(), 'location_id', 'locationCity.name'), 'showToggleAll' => false, 'language' => 'en', 'options' => ['multiple' => false, 'placeholder' => 'Select Location'], 'pluginOptions' => ['showToggleAll' => false, 'allowClear' => true]])
                        ->label(false); ?>

        
            </div>
        <div class="col-lg-2 col-md-6 col-sm-12">
                <?php
                $hotels = [];
                $query = \app\models\Hotels::find()->where(['hotel_status' => 1, 'is_deleted' => 0]);
                if (Yii::$app->user && Yii::$app->user->identity->user_type != 1) {
                    $return = \app\models\User::getUserAssingemnts();
                    $userHotels = $return['userHotels'];
                    $query->andWhere(['hotel_id' => $userHotels]);

                }
                $hotels = $query->all();
                $model->hotel_id= isset($hotels[0]['hotel_id']) ? $hotels[0]['hotel_id'] : 0;
                echo $form->field($model, 'hotel_id')
                    ->dropDownList(ArrayHelper::map($hotels, 'hotel_id', 'hotel_name'), [
                        'prompt' => 'Office',
                    ], [
                        'class',
                        'form-control'
                    ])
                    ->label(false);
                ?>

        </div>

        <div class="col-lg-2 col-md-6 col-sm-12">

                <?php
                /*echo $form->field($model, 'department_id')
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
                    ->label(false);*/
                ?>

                <?php
                echo $form->field($model, 'department_id')
                    ->dropDownList([], [
                        'onchange' => '
                
            '], [
                        'class',
                        'form-control'
                    ])
                    ->label(false);
                ?>

        </div>
        <div class="col-lg-2 col-md-6 col-sm-12">

                <?php
                echo $form->field($model, 'audit_id')
                    ->dropDownList([], [
                        'prompt' => 'Audit',
                        'class',
                        'form-control'
                    ])
                    ->label(false);
                ?>

        </div>


        <div class=" pull-right text-right">
            <?= Html::submitButton('Go', ['class' => 'btn btn-success']) ?>
            <?= Html::a('Clear', Yii::$app->urlManager->createUrl(['/site/dashboard#quickview']), ['class' => 'btn btn-default clear-all-btn']) ?>

            <?php
            echo Html::a('<i class="fa glyphicon glyphicon-download "></i> Download Report', ['/site/pdf-download', 'id' => 0], [
                'class' => 'btn-success download-report display-hide',
                'target' => '_blank',
                'disable' => 'disable',
                'data-toggle' => 'tooltip',
                'title' => 'Will open the generated PDF file in a new window'
            ]);
            ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

<?php
$this->registerJs("

function submitQuickViewForm(){
 var form = $('#form-fetch-audits');
         var auditId = $('#auditssearch-audit_id').val();
        
        var formData = form.serialize();
        $.ajax({
            url: form.attr('action'),
            type: form.attr('method'),
            data: formData,
            success: function (data) {
                if(data.count > 0){
                    $('.download-report').addClass('btn');
                    $('.quickview-content').html(data.content);
                     $('.default-text-h4').text('');
                }else{
                    $('.download-report').removeClass('btn');
                    $('.quickview-content').html('');
                    $('.default-text-h4').html(data.content);
                }
                $('.download-report').attr('href', $('.download-report').attr('href').replace(/((\?|&)id\=)[0-9]*/, '$1' + auditId));
              
            },
            error: function () {
                $('.download-report').removeClass('btn');
                $('.compare-header').html('There are no audits scheduled to display');
            }
        });
        return false;
}

    $('#form-fetch-audits').on('beforeSubmit', function(e) {
        var form = $(this);
         var auditId = $('#auditssearch-audit_id').val();
        
        var formData = form.serialize();
        $.ajax({
            url: form.attr('action'),
            type: form.attr('method'),
            data: formData,
            success: function (data) {
                if(data.count > 0){
                    $('.download-report').addClass('btn');
                    $('.quickview-content').html(data.content);
                     $('.default-text-h4').text('');
                }else{
                    $('.download-report').removeClass('btn');
                    $('.quickview-content').html('');
                    $('.default-text-h4').html(data.content);
                }
                $('.download-report').attr('href', $('.download-report').attr('href').replace(/((\?|&)id\=)[0-9]*/, '$1' + auditId));
              
            },
            error: function () {
                $('.download-report').removeClass('btn');
                $('.compare-header').html('There are no audits scheduled to display');
            }
        });
        return false;
    }).on('submit', function(e){
        e.preventDefault();
    });");

?>

<?php
$this->registerJs("   
    $('.clear-all-btn').click(function() {
    location.reload();
});");

?>
