<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\widgets\MaskedInput;
use app\models\HotelDepartments;

$action = "";
$action = yii::$app->urlManager->createAbsoluteUrl([
    'organisation/manage-cabin',
    'cabin_id' => yii::$app->utils->encryptSetUp($cabinsModel->cabin_id, 'cabin')
]);

?>

<?php $form = ActiveForm::begin(['id' => 'cabin_form', 'action' => $action]); ?>
<div class="col-sm-12 text-center text-bold">
	<h3 class="text-success ">
		<i class="fa fa-home"></i> <?= $hotelDepartmentModel->department->department_name; ?>
    </h3>
	<small>(selected department)</small>
</div>
<div class="col-sm-12">
    <?= Html::hiddenInput("encrypted_department_id", yii::$app->utils->encryptSetUp($hotelDepartmentModel->department_id,'department')); ?>
    <?= $form->field($cabinsModel, 'cabin_name')->textInput(['maxlength' => 50,'class'=>'form-control alphanumeric'])->label('Cabin Name'. Html::tag('span', '*',['class'=>'required'])); ?>
</div>
<div class="col-sm-12">
    <?= $form->field($cabinsModel, 'cabin_description')->textarea(['rows' => 3])->label('Description'); ?>
</div>
<div class='col-sm-12' style="margin-top: 20px;">
	<div class="col-sm-12 text-center">
		<button id="save_cabin_submit_btn" type="submit"
			class="btn btn-success"><?=$cabinsModel->isNewRecord ? 'Save':'Update'; ?> cabin</button>
		<button class="btn btn-default" data-dismiss="modal">Close</button>
	</div>
</div>
<?php ActiveForm::end() ?>