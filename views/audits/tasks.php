<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\bootstrap\ActiveForm;
use app\assets\AppAsset;
use yii\web\JqueryAsset;
use yii\web\View;
use yii\widgets\Pjax;
use app\models\AuditsSchedules;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\AuditsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tasks';
$this->params['breadcrumbs'][] = $this->title;

AppAsset::register($this);
View::registerJsFile(yii::$app->urlManager->createUrl('js/modelpopup.js'), ['depends' => JqueryAsset::className()]);
$this->registerJs('
$(".nav-bids").removeClass("active");
$("#tasks").addClass("active");
$(".dropdown-toggle").dropdown();
', \yii\web\View::POS_END);
?>
<div class="container-fluid">
    <h2>Manage Tasks</h2>
</div>
<!-- notification text -->
<div class="wa-notification wa-notification-alt">
    <span class="wa-iconBoxed">
        <span class="fa fa-file-text-o header-icon-fontcolor"></span>
    </span>
    <p id="description-text">
        Tasks for departments across offices can be scheduled and managed here.

    </p>
</div>
<!-- -------------------------Start Search here------------------------- -->
<?php echo $this->render('_searchTasks', ['model' => $searchModel]); ?>

<div class="row">
    <div class="col-lg-12 nopadding">
        <ul class="nav nav-tabs">
            <li  class="active">
                <a href="#" class="archivedaudits tabs" data-toggle="tab" name="tab" data-index="1"><i
                        class="fa fa-archive" aria-hidden="true" aria-expanded="false"></i>&nbsp;Completed Tasks</a>
            </li>
            <li>
                <a href="#" class="activeaudits tabs active" data-toggle="tab" name="tab" data-index="0"><i
                        class="fa fa-folder-open" aria-hidden="true" aria-expanded="true"></i>&nbsp;Scheduled
                    Tasks</a>
            </li>

            <?php
            if (Yii::$app->authManager->checkPermissionAccess('audits/create')) {
                ?>
                <li class="pull-right">
                    <a href="<?= yii::$app->urlManager->createUrl('audits/create-task'); ?>" class="btn btn-success"><i
                            class="fa fa-plus"></i>&nbsp;Schedule Task</a>
                </li>
            <?php } ?>
        </ul>
    </div>
</div>

<div class="row schedule_auditid" >
    <div class="tab-content">
        <div id="activeaudits" class="tab-pane fade in active">
            <div class="col-sm-12 nopadding">
                <div class="audits-index">
                    <div id="p1" data-pjax-container="" data-pjax-push-state="" data-pjax-timeout="1000"> 
                        <div id="w51" class="grid-view is-bs3 hide-resize">
                            <div class="rc-handle-container" style="width: 0px;"></div>
                            <div id="w5-container11" class="table-responsive kv-grid-container">
                                <table class="kv-grid-table table table-bordered table-striped kv-table-wrap"><thead>
                                        <tr>
                                            <th class="theadcolor" data-col-seq="0">Scheduled Task ID</th>
                                            <th class="theadcolor" data-col-seq="1">Location</th>
                                            <th class="theadcolor" data-col-seq="2">Office</th>
                                            <th class="theadcolor" data-col-seq="3">Floor</th>
                                            <th class="theadcolor" data-col-seq="4">CheckList</th>
                                            <th class="theadcolor" data-col-seq="5">Status</th>
                                            <th class="theadcolor" data-col-seq="6">Start Date</th>
                                            <th class="theadcolor" data-col-seq="7">End Date</th>
                                        </tr>

                                    </thead>
                                    <tbody>
                                        <tr data-key="12">
                                            <td data-col-seq="0">
                                                <a data-pjax="0" href="javascript:void(0)" title="View Scheduled Task">TSK009-2</a>
                                            </td>
                                            <td data-col-seq="1">Hyderabad</td>
                                            <td data-col-seq="2">JH1</td>
                                            <td data-col-seq="3">1st floor</td>
                                            <td data-col-seq="4">Daily FO Inspection Checklist</td>
                                            <td data-col-seq="5">Completed</td>
                                            <td data-col-seq="6">08-01-2020</td>
                                            <td data-col-seq="7">26-01-2020</td>
                                        </tr>
                                        <tr data-key="12">
                                            <td data-col-seq="0">
                                                <a data-pjax="0" href="javascript:void(0)" title="View Scheduled Task">TSK009-1</a>
                                            </td>
                                            <td data-col-seq="1">Hyderabad</td>
                                            <td data-col-seq="2">JH1</td>
                                            <td data-col-seq="3">1st floor</td>
                                            <td data-col-seq="4">Daily FO Inspection Checklist</td>
                                            <td data-col-seq="5">Completed</td>
                                            <td data-col-seq="6">08-01-2020</td>
                                            <td data-col-seq="7">25-01-2020</td>
                                        </tr>
                                        <tr data-key="12">
                                            <td data-col-seq="0">
                                                <a data-pjax="0" href="javascript:void(0)" title="View Scheduled Task">TSK008-2</a>
                                            </td>
                                            <td data-col-seq="1">Chennai</td>
                                            <td data-col-seq="2">Adyar</td>
                                            <td data-col-seq="3">1st floor</td>
                                            <td data-col-seq="4">Housekeeping Supervisor Checklist 1</td>
                                            <td data-col-seq="5">Completed</td>
                                            <td data-col-seq="6">08-01-2020</td>
                                            <td data-col-seq="7">26-01-2020</td>
                                        </tr>
                                        <tr data-key="12">
                                            <td data-col-seq="0">
                                                <a data-pjax="0" href="javascript:void(0)" title="View Scheduled Task">TSK008-3</a>
                                            </td>
                                            <td data-col-seq="1">Chennai</td>
                                            <td data-col-seq="2">Adyar</td>
                                            <td data-col-seq="3">1st floor</td>
                                            <td data-col-seq="4">Housekeeping Supervisor Checklist 1</td>
                                            <td data-col-seq="5">Completed</td>
                                            <td data-col-seq="6">08-01-2020</td>
                                            <td data-col-seq="7">26-01-2020</td>
                                        </tr>
                                        <tr data-key="12">
                                            <td data-col-seq="0">
                                                <a data-pjax="0"  href="javascript:void(0)" title="View Scheduled Task">TSK007-2</a>
                                            </td>
                                            <td data-col-seq="1">Chennai</td>
                                            <td data-col-seq="2">Adyar</td>
                                            <td data-col-seq="3">1st floor</td>
                                            <td data-col-seq="4">Security Officer Checklist</td>
                                            <td data-col-seq="5">Completed</td>
                                            <td data-col-seq="6">08-01-2020</td>
                                            <td data-col-seq="7">26-01-2020</td>
                                        </tr>
                                        <tr data-key="12">
                                            <td data-col-seq="0">
                                                <a data-pjax="0"  href="javascript:void(0)" title="View Scheduled Task">TSK007-1</a>
                                            </td>
                                            <td data-col-seq="1">Chennai</td>
                                            <td data-col-seq="2">Adyar</td>
                                            <td data-col-seq="3">1st floor</td>
                                            <td data-col-seq="4">Security Officer Checklist</td>
                                            <td data-col-seq="5">Completed</td>
                                            <td data-col-seq="6">08-01-2020</td>
                                            <td data-col-seq="7">26-01-2020</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>                        
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


<div class="row auditid" style="display:none">
    <div class="tab-content">
        <div id="activeaudits" class="tab-pane fade in active">
                <div class="col-sm-12 nopadding">
                    <div class="audits-index">
                        <div id="p1" data-pjax-container="" data-pjax-push-state="" data-pjax-timeout="1000"> 
                            <div id="w5" class="grid-view is-bs3 hide-resize">
                                <div class="rc-handle-container" style="width: 0px;">
                                </div>
                                <div id="w5-container" class="table-responsive kv-grid-container">
                                    <table class="kv-grid-table table table-bordered table-striped kv-table-wrap"><thead>
                                            <tr>
                                                <th class="theadcolor" data-col-seq="0">Scheduled Task ID</th>
                                                <th class="theadcolor" data-col-seq="1">Location</th>
                                                <th class="theadcolor" data-col-seq="2">Office</th>
                                                <th class="theadcolor" data-col-seq="3">Floor</th>
                                                <th class="theadcolor" data-col-seq="4">CheckList</th>
                                                <th class="theadcolor" data-col-seq="5">Status</th>
                                                <th class="theadcolor" data-col-seq="6">Start Date</th>
                                                <th class="theadcolor" data-col-seq="7">End Date</th>
                                                <th class="theadcolor">Actions</th>
                                            </tr>

                                        </thead>
                                        <tbody>
                                            <tr data-key="12">
                                                <td data-col-seq="0">
                                                    <a data-pjax="0" target="_blank" href="javascript:void(0)" title="View Scheduled Task">TSK001-2</a>
                                                </td>
                                                <td data-col-seq="1">Hyderabad</td>
                                                <td data-col-seq="2">JH1</td>
                                                <td data-col-seq="3">1st floor</td>
                                                <td data-col-seq="4">Housekeeping Supervisor Checklist</td>
                                                <td data-col-seq="5">Scheduled</td>
                                                <td data-col-seq="6">08-01-2020</td>
                                                <td data-col-seq="7">09-01-2020</td>
                                                <td><a href="javascript:void(0)" title="Edit"><i class="fa fa-edit"></i></a>&nbsp;
                                                    <a href="javascript:void(0)" title="Delete" class=""><i class="fa fa-trash" title="Delete"></i></a>
                                                </td>
                                            </tr>
                                            <tr data-key="12">
                                                <td data-col-seq="0">
                                                    <a data-pjax="0" target="_blank" href="javascript:void(0)" title="View Scheduled Task">TSK001-3</a>
                                                </td>
                                                <td data-col-seq="1">Hyderabad</td>
                                                <td data-col-seq="2">JH1</td>
                                                <td data-col-seq="3">1st floor</td>
                                                <td data-col-seq="4">Housekeeping Supervisor Checklist</td>
                                                <td data-col-seq="5">Scheduled</td>
                                                <td data-col-seq="6">08-01-2020</td>
                                                <td data-col-seq="7">09-01-2020</td>
                                                <td><a href="javascript:void(0)" title="Edit"><i class="fa fa-edit"></i></a>&nbsp;
                                                    <a href="javascript:void(0)" title="Delete" class=""><i class="fa fa-trash" title="Delete"></i></a>
                                                </td>
                                            </tr>
                                            <tr data-key="12">
                                                <td data-col-seq="0">
                                                    <a data-pjax="0" target="_blank" href="javascript:void(0)" title="View Scheduled Task">TSK001-4</a>
                                                </td>
                                                <td data-col-seq="1">Hyderabad</td>
                                                <td data-col-seq="2">JH2</td>
                                                <td data-col-seq="3">1st floor</td>
                                                <td data-col-seq="4">Housekeeping Supervisor Checklist</td>
                                                <td data-col-seq="5">Scheduled</td>
                                                <td data-col-seq="6">08-01-2020</td>
                                                <td data-col-seq="7">09-01-2020</td>
                                                <td><a href="javascript:void(0)" title="Edit"><i class="fa fa-edit"></i></a>&nbsp;
                                                    <a href="javascript:void(0)" title="Delete" class=""><i class="fa fa-trash" title="Delete"></i></a>
                                                </td>
                                            </tr>
                                            <tr data-key="12">
                                                <td data-col-seq="0">
                                                    <a data-pjax="0" target="_blank" href="javascript:void(0)" title="View Scheduled Task">TSK004-2</a>
                                                </td>
                                                <td data-col-seq="1">Hyderabad</td>
                                                <td data-col-seq="2">JH1</td>
                                                <td data-col-seq="3">1st floor</td>
                                                <td data-col-seq="4">Washroom Cleaning - Gents Checklist</td>
                                                <td data-col-seq="5">Scheduled</td>
                                                <td data-col-seq="6">08-01-2020</td>
                                                <td data-col-seq="7">09-01-2020</td>
                                                <td><a href="javascript:void(0)" title="Edit"><i class="fa fa-edit"></i></a>&nbsp;
                                                    <a href="javascript:void(0)" title="Delete" class=""><i class="fa fa-trash" title="Delete"></i></a>
                                                </td>
                                            </tr>
                                            <tr data-key="12">
                                                <td data-col-seq="0">
                                                    <a data-pjax="0" target="_blank" href="javascript:void(0)" title="View Scheduled Task">TSK004-3</a>
                                                </td>
                                                <td data-col-seq="1">Chennai</td>
                                                <td data-col-seq="2">JH1</td>
                                                <td data-col-seq="3">1st floor</td>
                                                <td data-col-seq="4">Washroom Cleaning - Gents Checklist</td>
                                                <td data-col-seq="5">Scheduled</td>
                                                <td data-col-seq="6">08-01-2020</td>
                                                <td data-col-seq="7">09-01-2020</td>
                                                <td><a href="javascript:void(0)" title="Edit"><i class="fa fa-edit"></i></a>&nbsp;
                                                    <a href="javascript:void(0)" title="Delete" class=""><i class="fa fa-trash" title="Delete"></i></a>
                                                </td>
                                            </tr>
                                            <tr data-key="12">
                                                <td data-col-seq="0">
                                                    <a data-pjax="0" target="_blank" href="javascript:void(0)" title="View Scheduled Task">TSK005-2</a>
                                                </td>
                                                <td data-col-seq="1">Chennai</td>
                                                <td data-col-seq="2">Adyar</td>
                                                <td data-col-seq="3">2nd floor</td>
                                                <td data-col-seq="4">Daily FO Inspection Checklist 1	</td>
                                                <td data-col-seq="5">Scheduled</td>
                                                <td data-col-seq="6">08-01-2020</td>
                                                <td data-col-seq="7">09-01-2020</td>
                                                 <td><a href="javascript:void(0)" title="Edit"><i class="fa fa-edit"></i></a>&nbsp;
                                                    <a href="javascript:void(0)" title="Delete" class=""><i class="fa fa-trash" title="Delete"></i></a>
                                                </td>
                                            </tr>
                                            <tr data-key="12">
                                                <td data-col-seq="0">
                                                    <a data-pjax="0" target="_blank" href="javascript:void(0)" title="View Scheduled Task">TSK005-1</a>
                                                </td>
                                                <td data-col-seq="1">Chennai</td>
                                                <td data-col-seq="2">Adyar</td>
                                                <td data-col-seq="3">2nd floor</td>
                                                <td data-col-seq="4">Daily FO Inspection Checklist 1	</td>
                                                <td data-col-seq="5">Scheduled</td>
                                                <td data-col-seq="6">08-01-2020</td>
                                                <td data-col-seq="7">09-01-2020</td>
                                                <td><a href="javascript:void(0)" title="Edit"><i class="fa fa-edit"></i></a>&nbsp;
                                                    <a href="javascript:void(0)" title="Delete" class=""><i class="fa fa-trash" title="Delete"></i></a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>                        
                        </div>
                    </div>
                </div>
        </div>
    </div>
</div>





<!-- ------------------------------------------Modele POPup start here-------------------------->
<div id="deleteAuditPopup" class="modal fade" role="dialog">
    <?php ActiveForm::begin(['id' => 'delete_role_form', 'action' => yii::$app->urlManager->createUrl('audits/delete')]) ?>
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" style="color: #fff!important; opacity: 1;"
                        aria-hidden="true">
                    �
                </button>
                <h4 class="modal-title">Confirmation</h4>
            </div>
            <div class="modal-body clearfix">
                <input type="hidden" name="deletable_audit_id" id="deletable_audit_id" value=""/>
                <div class="col-sm-12" style="margin-top: 20px;">
                    <label>
                        Are you sure you want to delete this audit along with child audits? You can't undo this action.
                    </label>
                </div>
            </div>
            <div class="modal-footer clearfix" style="border-top: none; margin-top: 5px;">
                <div class="col-sm-12">
                    <input class="btn btn-danger" type="submit" value="Delete">
                    <button type="button" class="btn btn-Clear" data-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end() ?>
</div>



<!----------------------Cancel Schedule audit Popup Start hare -->
<div id="deletepopup" class="modal fade" role="dialog">
    <?php ActiveForm::begin(['id' => 'cancel_auditschedule_form', 'action' => yii::$app->urlManager->createUrl('audits/cancel'), 'method' => 'post',]) ?>
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" style="color: #fff!important; opacity: 1;"
                        aria-hidden="true">
                    �
                </button>
                <h4 class="modal-title">Confirmation</h4>
            </div>
            <div class="modal-body clearfix">
                <input type="hidden" name="update_auditschedule_id" id="update_auditschedule_id_value" value=""/>
                <input type="hidden" name="fromIndex" value="1"/>
                <div class="col-sm-12" style="margin-top: 20px;">
                    <label>
                        Are you sure you want to cancel this Audit? You can't undo this action.
                    </label>
                </div>
            </div>
            <div class="modal-footer clearfix" style="border-top: none; margin-top: 5px;">
                <div class="col-sm-12">
                    <input class="btn btn-danger" type="submit" value="Submit">
                    <button type="button" class="btn btn-Clear" data-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end() ?>
</div>

<div id="deletepopupModal" class="modal fade" role="dialog">
    <?php ActiveForm::begin(['id' => 'delete_auditschedule_form', 'action' => yii::$app->urlManager->createUrl('audits/delete-audit'), 'method' => 'post',]) ?>
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" style="color: #fff!important; opacity: 1;"
                        aria-hidden="true">
                    �
                </button>
                <h4 class="modal-title">Confirmation</h4>
            </div>
            <div class="modal-body clearfix">
                <input type="hidden" name="update_auditschedule_id" id="delete_auditschedule_id_value" value=""/>
                <input type="hidden" name="fromIndex" value="1"/>
                <div class="col-sm-12" style="margin-top: 20px;">
                    <label>
                        Are you sure you want to delete this Audit? You can't undo this action.
                    </label>
                </div>
            </div>
            <div class="modal-footer clearfix" style="border-top: none; margin-top: 5px;">
                <div class="col-sm-12">
                    <input class="btn btn-danger" type="submit" value="Submit">
                    <button type="button" class="btn btn-Clear" data-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end() ?>
</div>