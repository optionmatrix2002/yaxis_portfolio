<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Departments */
/* @var $form yii\widgets\ActiveForm */

$this->registerJs('
$(".nav-bids").removeClass("active");
$("#MenuMasterData").addClass("active");
$("#settings-department").addClass("active");
', \yii\web\View::POS_END);

$actionType = Yii::$app->controller->action->id;
?>
<?php $item = Yii::$app->controller->action->id;?>
<div class="container-fluid">
	<h2><?=$this->title; ?></h2>
</div>
<div class="wa-notification wa-notification-alt">
	<span class="wa-iconBoxed"> <span class="fa fa-file-text-o header-icon-fontcolor"></span>
	</span>
	<?php if($actionType=="create")
	{?>
    <p id="description-text">Master Data of Departments can be create from here.</p>
	<?php } else if($actionType=="update") {?>
	<p id="description-text">Master Data of Departments can be update from here.</p>
	<?php } else { ?>
	<p id="description-text">Master Data of Departments can be managed from here.</p>
	<?php } ?>
		
</div>
<div class="col-md-12">
	<a href="<?= yii::$app->urlManager->createUrl('departments'); ?>"
		class="btn btn-default pull-right"><i class="fa fa-arrow-left"></i>&nbsp;Back</a>
</div>

<div class="row" style="margin-top: 10px;">
	<div class="departments-form">
 <?php $form = ActiveForm::begin(); ?>
          <div class="col-lg-12 col-md-12 col-sm-12 margintop10">
			<div class="col-lg-3 col-sm-3 col-md-3">
				<label>Floor:<span class="span-star">*</span></label>
			</div>
			<div class="col-lg-9 col-sm-9 col-md-9">
				<div class="input-group col-sm-6">
                    
                    <?= $form->field($model, 'department_name')->textInput(['maxlength' => true,'class'=>'form-control charsSpecialChars'])->label(false) ?>
                </div>
			</div>
		</div>
		<div class="col-lg-12 col-md-12 col-sm-12 margintop10">
			<div class="col-lg-3 col-md-3 col-sm-3">
				<label>Description :<span class="span-star">*</span></label>
			</div>
			<div class="col-lg-9 col-md-9 col-sm-9">
				<div class="input-group col-sm-6">
                        <?= $form->field($model, 'department_description')->textarea(['rows' => 6])->label(false) ?>
                    </div>
			</div>
		</div>
		
           <?php
        /*
         * ?> <div class="col-sm-12 margintop10">
         * <div class="col-sm-2">
         * <label>Status :<span class="span-star">*</span></label>
         * </div>
         * <div class="col-sm-10">
         * <div class="input-group col-sm-6">
         * <?= $form->field($model, 'is_active')
         * ->radioList(
         * ['1' => 'Active', '0' => 'Inactive'],
         * [
         * 'item' => function($index, $label, $name, $checked, $value) {
         * $checked = ($checked) ? 'checked' : '';
         * $return = '<div class="col-sm-6 radio-button-padding" id="auditspan">';
         * $return .= '<label class="ExternalAudit">';
         * $return .= '<input type="radio" name="' . $name . '" value="' . $value . '" ' . $checked . ' tabindex="3">';
         * $return .= '<i></i>';
         * $return .= '&nbsp;&nbsp;&nbsp;<span>' . ucwords($label) . '</span>';
         * $return .= '</label>';
         * $return .= '</div>';
         * return $return;
         * }
         * ]
         * )
         * ->label(false);
         * ?>
         * </div>
         * </div>
         * </div> <?php
         */
        ?>
           <div class="col-lg-12 col-md-12 col-sm-12 margintop10">
			<div class="col-lg-3 col-md-3 col-sm-3">
				<label></label>
			</div>
			<div class="col-lg-9 col-md-9 col-sm-9">
				<div class="col-sm-6 input-group">                
                        <?= Html::submitButton($model->isNewRecord ? 'Save' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-success']) ?>
                        <?= Html::a( 'Cancel',['/departments'],['class'=>'btn btn-default']); ?>
                    </div>
			</div>
		</div>
  <?php ActiveForm::end(); ?>
</div>
</div>