<?php ?>
<div class="col-sm-12 bg-white" id="footer">
    <p style="margin-top: 10px; margin-left: 0px; text-align: center; color: #FFF;">&#169; <?php echo date('Y'); ?> -
        Option Matrix InfoTech Pvt Ltd &#174; All Rights Reserved</p>
</div>
<div id="helpModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content" style="height:253px">
            <div class="modal-header" style="margin: 0; line-height: 1.42857143; padding: 10px;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title help-modal-title" style="font-weight: bolder; margin-left: 0px !important;">
                    Help </h4>
            </div>
            <div class="modal-body" style="margin: 0; line-height: 1.42857143; padding: 10px;">
                <div class="col-xs-12 nopadding" id="helptextBody">
                    Click on Module Help for a guided tour of the application. Upload the application guidelines
                    document here which can be viewed from the Corporate Audit mobile application.
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12" style="margin-bottom: 13px;">
                    <button class="btn btn-primary" id="mhelp" style="float:right;">Module Help</button>
                </div>
                <?php $form = \yii\widgets\ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'id' => 'uploadForm'], 'action' => yii::$app->urlManager->createUrl('site/helpdocupload')]) ?>
                <table>
                    <tr>
                        <td style="font-weight: 600;
                            padding-right: 20px;
                            padding-left: 11px;">Guidelines Document</td>
                        <td style="padding-right: 10px;"><div class="upload-btn-wrapper" style="margin-top: 5px;">
                                <button class="btn upload-button">Upload a file</button>
                                <input type="file" id="file_upload_help" name="file_upload_help" aria-invalid="false"/>
                            </div></td>
                        <td style="padding-right: 10px;"><div >
                                <?= \yii\helpers\Html::submitButton('Save', ['class' => 'btn btn-success', 'id' => 'help_upload_submit']) ?>
                            </div></td>
                        <td style="padding-right: 10px;"><a class="btn btn-success"
                                                            title="Download <?= Yii::getAlias('@web') . Yii::$app->params['guideLinesPath'] ?>"
                                                            href="<?= Yii::getAlias('@web') . Yii::$app->params['guideLinesPath'] ?>"
                                                            download>
                                <i class="fa fa-arrow-circle-o-down" title="Download"> </i>
                                Download


                            </a></td>
                    </tr>
                </table>
                <div class="status-row" style="margin-left: 13px;">&nbsp;</div>
                <span id="result"></span>
                <?php \yii\widgets\ActiveForm::end(); ?>

            </div>

        </div>
        <!-- Modal -->
        <div class="modal fade" id="myModal-change-pswd1" tabindex="-1" role="dialog" aria-labelledby="myModallabel"
             aria-hidden="true">
            <div class="modal-dialog pswd-pop">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"
                                style="color: #fff!important; opacity: 1;" aria-hidden="true">
                            &times;
                        </button>
                        <h4 class="modal-title" id="myModallabel">Change Password</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row nopadding txtboxpadding">
                            <div class="col-sm-4">
                                <label class="add-member-label lblfnt">Email:</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="form-control add-member-input" value="admin@aramark.com"
                                       readonly="readonly"/>

                            </div>
                        </div>
                        <div class="row nopadding txtboxpadding">
                            <div class="col-sm-4">
                                <label class="add-member-label lblfnt">New Password:</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="password" class="form-control add-member-input" placeholder="New Password"
                                       id="txtNewPassword" name="txtNewPassword"/>
                                <span id="lblNewPassword" class="required hide">Required</span>
                            </div>
                        </div>
                        <div class="row nopadding txtboxpadding">
                            <div class="col-sm-4">
                                <label class="add-member-label lblfnt">Confirm Password:</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="password" class="form-control add-member-input"
                                       placeholder="Confirm Password" id="txtConfirmPassword" name="txtCnfmPassword"/>
                                <span id="lblConfirmPassword" class="required hide">Required</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0">
                        <div class="col-sm-6 nopadding">
                            <button type="button" class="btn btn-success" id="btnChangePassword">Update</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal"
                                    onclick="clearChangePasswordFields();">Close
                            </button>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php
        $sub_url = yii::$app->urlManager->createUrl('site/helpdocupload');
        $this->registerJs("

var sub_url = '$sub_url';
$(document).ready(function (e){
$('#helpModal').on('hide.bs.modal', function (e) {
  $('.status-row').html('');
})
    $('#uploadForm').on('submit',(function(e){
        e.preventDefault();
        var form = new FormData(this);
        console.log(form.action);
        $.ajax({
            url: sub_url,
            type: 'POST',
            data:  new FormData(this),
            contentType: false,
            cache: false,
            processData:false,
        success: function(data){
            if(data.code == 500){
                $('.status-row').html('<span class=\"text-danger\">'+data.content+'</span>');
            } else{
                $('.status-row').html('<span class=\"text-success\">'+data.content+'</span>');
            }
        },
        error: function(){} 	        
        });
    }));
});
");
        ?>
