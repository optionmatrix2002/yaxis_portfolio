<?php

use kartik\select2\Select2;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\MaskedInput;
use app\models\HotelDepartmentSections;
use app\models\Cabins;

$action = "";
if ($cabinModel->isNewRecord) {
    $action = yii::$app->urlManager->createAbsoluteUrl('organisation/manage-cabin');
} else {
    $action = yii::$app->urlManager->createAbsoluteUrl([
        'organisation/manage-cabin',
        'cabin_id' => yii::$app->utils->encryptSetUp($cabinsModel->cabin_id, 'cabin')
    ]);
}
?>

<?php $form = ActiveForm::begin(['id' => 'cabin_form', 'action' => $action]); ?>
<div class="col-sm-12 text-center text-bold">
    <h3 class="text-success ">
        <i class="fa fa-home"></i> <?= $hotelDepartmentModel->department->department_name; ?>
    </h3>
    <small>(selected floor)</small>
</div>

<div class="col-sm-12">
    <?= Html::hiddenInput("encrypted_department_id", yii::$app->utils->encryptSetUp($hotelDepartmentModel->department_id, 'department')); ?>
    <?= Html::hiddenInput("encrypted_hotel_id", yii::$app->utils->encryptSetUp($hotelDepartmentModel->hotel_id, 'department')); ?>
    <?= Html::hiddenInput("add-cabin", yii::$app->urlManager->createAbsoluteUrl('organisation/create-new-cabin'), ['class' => 'add-cabin']); ?>
    <?= Html::hiddenInput("assign-cabin", yii::$app->urlManager->createAbsoluteUrl('organisation/assign-cabin'), ['class' => 'assign-cabin']); ?>

    <?php
    $existingCabins = Cabins::find()->where([
                'hotel_id' => $hotelDepartmentModel->hotel_id,
                'department_id' => $hotelDepartmentModel->department_id,
                'is_deleted' => 0
            ])
            ->asArray()
            ->all();


    $existingCabins = ArrayHelper::getColumn($existingCabins, 'cabin_id');
    $cabinList = ArrayHelper::map(Cabins::find()->where([
                                'not in',
                                'cabin_id',
                                $existingCabins
                            ])
                            ->andWhere([
                                'department_id' => $hotelDepartmentModel->department_id, 'is_deleted' => 0
                            ])
                            ->all(), 'cabin_id', 'cabin_name');

    $cabinsList = $cabinsList ? $cabinsList : [];
    ?>



    <div class="textField">
        <?= $form->field($cabinsModel, 'cabin_name')->textInput(['maxlength' => 50, 'class' => 'cabinName form-control charsSpecialChars'])->label('Workspace Name' . Html::tag('span', '*', ['class' => 'required'])); ?>
        <?= $form->field($cabinsModel, 'cabin_description')->textarea(['class' => 'form-control  cabinData cabinDescription', 'rows' => 3])->label('Description'); ?>
    </div>

</div>

<div class="textField col-sm-12"
     style=" margin-top: 20px;">
    <div class="text-center" style="margin-top: 3px;">
        <button id="add_new_cabin_save_btn"
                onclick="return saveNewCabinName();" type="button"
                disabled="disabled" class="btn btn-success">Save</button>
        <button id="add_new_cabin_cancel_btn"
                data-dismiss="modal" type="button"
                class="btn btn-default">Cancel</button>
    </div>
</div>


<?php ActiveForm::end() ?>


<script>

    changeAddCabinDiv = function () {
        $('.dropdownField').hide();
        $('.textField').show();
        $('button#save_multiplecabin_submit_btn').attr('disabled', 'disabled');
        $('button#add_new_cabin_submit_btn').hide();
        $('.savenewmultipledep').hide();
    }

    showDropDown = function () {
        $('.dropdownField').show();
        $('.savenewmultipledep').show();
        $('.textField').hide();
        $('button#save_multiplecabin_submit_btn').removeAttr('disabled');
        $('button#add_new_cabin_submit_btn').show();
    }

    saveNewCabinName = function ()
    {
        var deparmentName = $('.cabinName').val();
        var departmentTextArea = $('.cabinDescription').val();
        var actionUrl = $('.add-cabin').val();
        if (deparmentName) {
            $.ajax({
                url: actionUrl,
                data: $('form#cabin_form').serializeArray(),
                type: 'POST',
                success: function (result) {
                    result = JSON.parse(result);
                    if (result.success) {
                        var list = $("#hoteldepartmentcabin-cabin_id");
                        $.each(result.data, function (key, value) {
                            list.append(new Option(value, key));
                        });
                        $('form#cabin_form')[0].reset();
                    //    showDropDown();
                        toastr.success("cabin created successfully");
                         $("#popup_model").modal("hide");
                         var jsTreeInstance = $('#organisation_hierarchy').jstree(true);
    	                if (result.parent_node) {
    	                    jsTreeInstance.create_node(result.parent_node, result.node);
    	                    jsTreeInstance.open_node(result.parent_node);
    	                    jsTreeInstance.refresh();
    	                } else if (result.node) {
    	                    jsTreeInstance.rename_node(jsTreeInstance.get_node(result.node), result.node.text);
    	                }
                    } else if (result.error) {
                        var errors = result.error;
                        if (errors['cabin_name']) {
                            var deparmentNameField = $('.field-cabin-cabin_name');
                            deparmentNameField.addClass("has-error");
                            deparmentNameField.find('p').html(errors['cabin_name']);
                        }
                        if (errors['department_description']) {
                            var deparmentNameField = $('.field-cabin-cabin_remarks');
                            deparmentNameField.addClass("has-error");
                            deparmentNameField.find('p').html(errors['cabin_remarks']);
                        }
                    }

                },
                error: function () {
                },
            });
        }

    }

    $('.cabinName').on('change keyup', function () {
        if ($('.cabinName').val()) {
            $('button#add_new_cabin_save_btn').removeAttr('disabled');
        }
    });

    $('.cabinId').on('change', function () {
        $('button#save_multiplecabin_submit_btn').attr('disabled', 'disabled');
        if ($(this).val()) {
            $('button#save_multiplecabin_submit_btn').removeAttr('disabled');
        }
    });
    $('button#save_multiplecabin_submit_btn').on('click', function () {
        var actionUrl = $('.assign-cabin').val()
        if ($('.cabinId').val()) {
            $.ajax({
                url: actionUrl,
                data: $('form#cabin_form').serializeArray(),
                type: 'POST',
                success: function (data) {
                    response = JSON.parse(data);
                    if (response.success) {
                        toastr.success(response.success);
                        $("#popup_model").modal("hide");
                        var jsTreeInstance = $('#organisation_hierarchy').jstree(true);
                        if (response.parent_node) {
                            jsTreeInstance.create_node(response.parent_node, response.node);
                            jsTreeInstance.open_node(response.parent_node);
                            jsTreeInstance.refresh();
                        } else if (response.node) {
                            jsTreeInstance.rename_node(jsTreeInstance.get_node(response.node), response.node.text);
                        }
                    } else if (response.error) {
                        toastr.error(response.error);
                    }
                },
                error: function () {
                },
            });
        }
    });

</script>
