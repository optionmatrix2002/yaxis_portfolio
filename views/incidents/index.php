<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\assets\AppAsset;
use yii\bootstrap\ActiveForm;
use yii\web\JqueryAsset;
use yii\web\View;
use yii\widgets\Pjax;
use app\models\Tickets;
use app\models\TicketProcessCritical;
use app\models\GridColumns;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\TicketsSearch */

$this->title = 'Incidents';
$this->params['breadcrumbs'][] = $this->title;

AppAsset::register($this);

View::registerJs("save_url = '". yii::$app->urlManager->createUrl('tickets/save-columns')."';",View::POS_HEAD);
View::registerCssFile(yii::$app->urlManager->createUrl('css/bootstrap-multiselect.css'));
View::registerJsFile(yii::$app->urlManager->createUrl('js/modelpopup.js'), ['depends' => JqueryAsset::className()]);
View::registerJsFile(yii::$app->urlManager->createUrl('js/bootstrap-multiselect.js'), [
    'depends' => JqueryAsset::className()
]);
$this->registerJs('
$(".nav-bids").removeClass("active");
$("#incidents").addClass("active");
$(".dropdown-toggle").dropdown();
var selectedVals=[];
$("#example-getting-started option:selected").map(function(a, item){selectedVals.push(item.value);});
console.log(selectedVals);
$("#example-getting-started").multiselect({
    includeSelectAllOption: true,
    onChange: function(option, checked, select) {
        var selectedVal = option.val();
        console.log(option.val(), checked, select);
        if(!checked){
            $(".tab-content").find("."+selectedVal).addClass("hidden");
            for( var i = 0; i < selectedVals.length; i++){ 
                if ( selectedVals[i] === option.val()) {
                    selectedVals.splice(i, 1); 
                }
             }
        }else{
            selectedVals.push(option.val());
            $(".tab-content").find("."+selectedVal).removeClass("hidden");
        }
    },
    onSelectAll: function() {
        selectedVals=[];
        selectedVals=["c1","c2","c3","c4","c5","c6","c7","c8","c9","c10","c11","c12","c13","c14"];
        $(".tab-content").find(".tbl-td").removeClass("hidden");
    },
    onDeselectAll: function() {
        selectedVals=[];
        $(".tab-content").find(".tbl-td").addClass("hidden");
    }
});

$(".multiselect-native-select .btn-group").click(function(){
 $(this).toggleClass("open");
});

$("#submitGridSelectionBtn").click(function(){
    $.post({
            url: save_url,
            data: {selected_columns:selectedVals,grid_type:$(this).data("type")},
            success: function(data) {
                console.log(data);
                response = JSON.parse(data);
                if(response.output){
                    toastr.success("Columns saved successfully");
                    location.reload();
                }
                
            }
        });   

});
', \yii\web\View::POS_END);
?>
<style>
.columnsFilter{
    margin: 20px 0px;
}
</style>
<div class="container-fluid">
    <h2>Incidents</h2>
</div>

<!-- notification text -->
<div class="wa-notification wa-notification-alt">
    <span class="wa-iconBoxed">
        <span class="fa fa-file-text-o header-icon-fontcolor"></span>
    </span>
    <p id="description-text">
        All incident activites including creation, assignments and tracking can be managed here.

    </p>
</div>

<?php echo $this->render('_search', ['model' => $searchModel]); ?>
<?php
$buttons = '';

if (Yii::$app->authManager->checkPermissionAccess('tickets/update')) {
    $buttons .= '{update}';
}

if (Yii::$app->authManager->checkPermissionAccess('tickets/update')) {
    $buttons .= '&nbsp;&nbsp;{cancel}';
}

$audits = \yii\helpers\ArrayHelper::map(\app\models\AuditsSchedules::find()->where(['status' => 3])->all(), 'audit_schedule_id', 'audit_schedule_name');
$audits[-1] = 'Dynamic Ticket';

$subSectionList = \yii\helpers\ArrayHelper::map(\app\models\SubSections::getList(), 'sub_section_id', 'ss_subsection_name');

$gridColumnsInfo = [
    [
        'attribute' => 'ticket_name',
        'header' => 'Incident ID',
        'visible'=>(!$columnsArr['c1']) ? false :true
    ],
    [
        'attribute' => 'audit_id',
        'header' => 'Audit',
        'value' => function ($model) use ($audits) {
            return (isset($audits[$model->audit_schedule_id])) ? $audits[$model->audit_schedule_id] : 'Dynamic Ticket';
        },
        'visible'=>(!$columnsArr['c2']) ? false :true
    ],
    [
        'attribute' => 'hotel_id',
        'header' => 'Office',
        'value' => function ($model) {
            return ($model->hotel_id) ? $model->hotel->hotel_name : '--';
        },
        'visible'=>(!$columnsArr['c3']) ? false :true
    ],
    [
        'attribute' => 'department_id',
        'header' => 'Floor',
        'value' => function ($model) {
            return ($model->department_id) ? $model->department->department_name : '--';
        },
        'visible'=>(!$columnsArr['c4']) ? false :true

    ],
    [
        'attribute' => 'cabin_id',
        'header' => 'Cabin',
        'value' => function ($model) {
            return $model->getTicketCabinData();
        },
        'format' => 'raw',
        'visible'=>(!$columnsArr['c5']) ? false :true
    ],
    [
        'attribute' => 'subject',
        'header' => 'Subject',
        'value' => function ($model) {
            return strip_tags($model->subject);
        },
        'visible'=>(!$columnsArr['c6']) ? false :true

    ],
    [
        'attribute' => 'assigned_id',
        'header' => 'Assigned To',
        'value' => function ($model) {
            return ucfirst($model->assignedUser->first_name) . ' ' . ucfirst($model->assignedUser->last_name);
        },
        'visible'=>(!$columnsArr['c7']) ? false :true

    ],
    [
        'attribute' => 'created_at',
        'header' => 'Created On',
        'value' => function ($model) {
            $timestamp = strtotime($model->created_at);
            return Yii::$app->formatter->asDate($timestamp, 'php:d-m-Y');
        },
        'visible'=>(!$columnsArr['c8']) ? false :true

    ],
    [
        'attribute' => 'due_date',
        'header' => 'Due Date',
        'value' => function ($model) {
            $timestamp = strtotime($model->due_date);
            return Yii::$app->formatter->asDate($timestamp, 'php:d-m-Y');
        },
        'visible'=>(!$columnsArr['c9']) ? false :true

    ],
    [
        'attribute' => 'overDueTicket',
        'header' => 'Overdue',
        'value' => function ($model) {
            $timestamp = strtotime($model->due_date);
            if (date('Y-m-d') > date('Y-m-d', $timestamp)) {
                return 'Yes';
            }

            return 'No';
        },
        'visible'=>(!$columnsArr['c10']) ? false :true

    ],
    [
        'attribute' => 'chronic',
        'header' => 'Chronic',
        'value' => function ($model) {
            switch ($model->chronicity) {
                case 0:
                    $chronicity = 'No';
                    break;
                case 1:
                    $chronicity = 'Yes';
                    break;
            }
            return $chronicity;
        },
        'visible'=>(!$columnsArr['c11']) ? false :true

    ],
    [
        'attribute' => 'priority_type_id',
        'header' => 'Priority',
        'value' => function ($model) {
            return $model->priorityType->priority_name;
        },
        'visible'=>(!$columnsArr['c12']) ? false :true

    ],
    [
        'attribute' => 'process_critical',
        'header' => 'Process Critical (Audit)',
        'value' => function ($model) {
        $data=Tickets::getAnswers($model->answer_id);
        
        switch ($data['question']['process_critical']) {
            case 0:
                $process = 'No';
                break;
            case 1:
                $process = 'Yes';
                break;
        }
        unset($data);
        return $process;
        },
        'visible'=>(!$columnsArr['c13']) ? false :true

        ],
        
        [
            'attribute' => 'process_critical_dynamic',
            'header' => 'Process Critical (Dynamic)',
            'value' => function ($model) {
            $process='-';
            if($model->audit_schedule_id==null){
                switch ($model->process_critical_dynamic) {
                    case 0:
                        $process = 'No';
                        break;
                    case 1:
                        $process = 'Yes';
                        break;
                }
            }
            
            return $process;
            },
            'visible'=>(!$columnsArr['c13']) ? false :true

            ],
            
    [
        'attribute' => 'status',
        'header' => 'Status',
        'value' => function ($model) {
            return \app\models\Tickets::$statusList[$model->status];
        },        'visible'=>(!$columnsArr['c14']) ? false :true

    ],
];

$archivedTickets = [
    [
        'attribute' => 'ticket_name',
        'header' => 'Incident ID',
        'visible'=>(!$columnsArr['c1']) ? false :true
    ],
    [
        'attribute' => 'audit_id',
        'header' => 'Audit',
        'value' => function ($model) use ($audits) {
            return (isset($audits[$model->audit_schedule_id])) ? $audits[$model->audit_schedule_id] : 'Dynamic Ticket';
        },
        'visible'=>(!$columnsArr['c2']) ? false :true
    ],
    [
        'attribute' => 'hotel_id',
        'header' => 'Office',
        'value' => function ($model) {
            return ($model->hotel_id) ? $model->hotel->hotel_name : '--';
        },
        'visible'=>(!$columnsArr['c3']) ? false :true
    ],
    [
        'attribute' => 'department_id',
        'header' => 'Floor',
        'value' => function ($model) {
            return ($model->department_id) ? $model->department->department_name : '--';
        },
        'visible'=>(!$columnsArr['c4']) ? false :true

    ],
    [
        'attribute' => 'cabin_id',
        'header' => 'Cabin',
        'value' => function ($model) {
            return $model->getTicketCabinData();
        },
        'format' => 'raw',
        'visible'=>(!$columnsArr['c5']) ? false :true
    ],
    [
        'attribute' => 'subject',
        'header' => 'Subject',
        'value' => function ($model) {
            return strip_tags($model->subject);
        },
        'visible'=>(!$columnsArr['c6']) ? false :true

    ],
    [
        'attribute' => 'assigned_id',
        'header' => 'Assigned To',
        'value' => function ($model) {
            return ucfirst($model->assignedUser->first_name) . ' ' . ucfirst($model->assignedUser->last_name);
        },
        'visible'=>(!$columnsArr['c7']) ? false :true

    ],
    [
        'attribute' => 'created_at',
        'header' => 'Created On',
        'value' => function ($model) {
            $timestamp = strtotime($model->created_at);
            return Yii::$app->formatter->asDate($timestamp, 'php:d-m-Y');
        },
        'visible'=>(!$columnsArr['c8']) ? false :true

    ],
    [
        'attribute' => 'due_date',
        'header' => 'Due Date',
        'value' => function ($model) {
            $timestamp = strtotime($model->due_date);
            return Yii::$app->formatter->asDate($timestamp, 'php:d-m-Y');
        },
        'visible'=>(!$columnsArr['c9']) ? false :true

    ],
    [
        'attribute' => 'overDueTicket',
        'header' => 'Overdue',
        'value' => function ($model) {
            $timestamp = strtotime($model->due_date);
            if (date('Y-m-d') > date('Y-m-d', $timestamp)) {
                return 'Yes';
            }

            return 'No';
        },
        'visible'=>(!$columnsArr['c10']) ? false :true

    ],
    [
        'attribute' => 'chronic',
        'header' => 'Chronic',
        'value' => function ($model) {
            switch ($model->chronicity) {
                case 0:
                    $chronicity = 'No';
                    break;
                case 1:
                    $chronicity = 'Yes';
                    break;
            }
            return $chronicity;
        },
        'visible'=>(!$columnsArr['c11']) ? false :true

    ],
    [
        'attribute' => 'priority_type_id',
        'header' => 'Priority',
        'value' => function ($model) {
            return $model->priorityType->priority_name;
        },
        'visible'=>(!$columnsArr['c12']) ? false :true

    ],
    [
        'attribute' => 'process_critical',
        'header' => 'Process Critical (Audit)',
        'value' => function ($model) {
        $data=Tickets::getAnswers($model->answer_id);
        
        switch ($data['question']['process_critical']) {
            case 0:
                $process = 'No';
                break;
            case 1:
                $process = 'Yes';
                break;
        }
        unset($data);
        return $process;
        },
        'visible'=>(!$columnsArr['c13']) ? false :true

        ],
        
        [
            'attribute' => 'process_critical_dynamic',
            'header' => 'Process Critical (Dynamic)',
            'value' => function ($model) {
            $process='-';
            if($model->audit_schedule_id==null){
                switch ($model->process_critical_dynamic) {
                    case 0:
                        $process = 'No';
                        break;
                    case 1:
                        $process = 'Yes';
                        break;
                }
            }
            
            return $process;
            },
            'visible'=>(!$columnsArr['c13']) ? false :true

            ],
            
    [
        'attribute' => 'status',
        'header' => 'Status',
        'value' => function ($model) {
            return \app\models\Tickets::$statusList[$model->status];
        },        'visible'=>(!$columnsArr['c14']) ? false :true

    ],
];
?>
<table class="columnsFilter">
<tr>
<td> <select id="example-getting-started" multiple="multiple">
                    <?php
                        foreach($tableColumnsArr as $index=>$column){
                            ?>
                            <option value="<?=$index?>" <?=$columnsArr[$index] ? 'selected' : ''?>><?=$column?></option>
                            <?php
                        }
                    ?>
                </select>
                </td>
<td><button class="btn btn-success" id="submitGridSelectionBtn" data-type="incidents" style="margin-left: 15px;">Save</button></td>
</tr>
</table>
<div class="row">
    <div class="col-lg-12 nopadding">
        <ul class="nav nav-tabs">
            <li class="active">
                <a href="#" class="activetickets tabs active" data-toggle="tab" name="tab" data-index="0"><i
                            class="fa fa-folder-open" aria-hidden="true" aria-expanded="true"></i>&nbsp;Active
                    Incidents</a>
            </li>
            <li>
                <a href="#" class="archivedtickets tabs" data-toggle="tab" name="tab" data-index="1"><i
                            class="fa fa-archive" aria-hidden="true" aria-expanded="false"></i>&nbsp;Archived
                    Incidents</a>
            </li>
            <?php if (Yii::$app->authManager->checkPermissionAccess('incidents/create')) { ?>
                <li class="pull-right">

                    <a href="<?= yii::$app->urlManager->createUrl('incidents/create'); ?>" class="btn btn-success"><i
                                class="fa fa-plus"></i>&nbsp;Create Incident</a>
                </li>
            <?php } ?>
            <li class="pull-right">
                <span class="pull-right">
                <?php echo \kartik\export\ExportMenu::widget([
                    'asDropdown' => false,
                    'fontAwesome' => true,
                    'options' => ['id' => 'archive-tickets-download'],
                    'enableFormatter' => false,
                    'exportConfig' => [
                        \kartik\export\ExportMenu::FORMAT_HTML => false,
                        \kartik\export\ExportMenu::FORMAT_TEXT => false,
                        \kartik\export\ExportMenu::FORMAT_PDF => false,
                        \kartik\export\ExportMenu::FORMAT_EXCEL => [
                            'label' => 'Archived Incidents',
                            'icon' => '',
                            'iconOptions' => ['class' => 'fa fa-download icon-white'],
                            'linkOptions' => ['style' => 'color:white !important'],
                            'options' => ['style' => 'margin-right:5px', 'class' => 'download-tickets pull-right btn-success', 'title' => Yii::t('app', 'Archived Tickets Download')],
                            //'alertMsg' => Yii::t('app', 'The CSV export file will be generated for download.'),
                            //'mime' => 'application/csv',
                            //'extension' => 'csv',
                            //'writer' => 'CSV'
                        ],
                        \kartik\export\ExportMenu::FORMAT_EXCEL_X => false,
                        \kartik\export\ExportMenu::FORMAT_CSV => false/*[
                            'label' => 'Archived Tickets',
                            'icon' => '',
                            'iconOptions' => ['class' => 'fa fa-download icon-white'],
                            'linkOptions' => ['style' => 'color:white !important'],
                            'options' => ['style' => 'margin-right:5px', 'class' => 'download-tickets pull-right btn-success', 'title' => Yii::t('app', 'Archived Tickets Download')],
                            'alertMsg' => Yii::t('app', 'The CSV export file will be generated for download.'),
                            'mime' => 'application/csv',
                            'extension' => 'csv',
                            'writer' => 'CSV'
                        ]*/,
                    ],
                    'filename' => 'Archived Incidents',
                    'target' => '_self',
                    'showConfirmAlert' => false,
                    'dataProvider' => $dataArchivedProvider,
                    'columns' => $archivedTickets]) ?>
                </span>
            </li>
            <li class="pull-right">
                <span class="pull-right">
                <?php echo \kartik\export\ExportMenu::widget([
                    'asDropdown' => false,
                    'fontAwesome' => true,
                    'options' => ['id' => 'active-tickets-download'],
                    'enableFormatter' => false,
                    'exportConfig' => [
                        \kartik\export\ExportMenu::FORMAT_HTML => false,
                        \kartik\export\ExportMenu::FORMAT_TEXT => false,
                        \kartik\export\ExportMenu::FORMAT_PDF => false,
                        \kartik\export\ExportMenu::FORMAT_EXCEL => [
                            'label' => 'Active Incidents',
                            'icon' => '',
                            'iconOptions' => ['class' => 'fa fa-download icon-white'],
                            'linkOptions' => ['style' => 'color:white !important'],
                            'options' => ['style' => 'margin-right:5px', 'class' => 'download-tickets pull-right btn-success', 'title' => Yii::t('app', 'Active Tickets Download')],
                          //  'alertMsg' => Yii::t('app', 'The CSV export file will be generated for download.'),
                           // 'mime' => 'application/csv',
                           // 'extension' => 'csv',
                           // 'writer' => 'CSV'
                        ],
                        \kartik\export\ExportMenu::FORMAT_EXCEL_X => false,
                        \kartik\export\ExportMenu::FORMAT_CSV => false/*[
                            'label' => 'Active Tickets',
                            'icon' => '',
                            'iconOptions' => ['class' => 'fa fa-download icon-white'],
                            'linkOptions' => ['style' => 'color:white !important'],
                            'options' => ['style' => 'margin-right:5px', 'class' => 'download-tickets pull-right btn-success', 'title' => Yii::t('app', 'Active Tickets Download')],
                            'alertMsg' => Yii::t('app', 'The CSV export file will be generated for download.'),
                            'mime' => 'application/csv',
                            'extension' => 'csv',
                            'writer' => 'CSV'
                        ]*/,
                    ],
                    'filename' => 'Active Incidents',
                    'target' => '_self',
                    'showConfirmAlert' => false,
                    'dataProvider' => $dataProvider,
                    'columns' => $gridColumnsInfo]) ?>
                </span>
            </li>
        </ul>
    </div>
</div>
<div class="row ticketid">
    <div class="tab-content">
        <div id="activeaudits" class="tab-pane fade in active">
            <div class="col-sm-12 nopadding">
                <div class="tickets-index">
                    <?php Pjax::begin(); ?>
                    <?=
                    GridView::widget([
                        'dataProvider' => $dataProvider,
                        'layout' => '{items}{pager}',
                        'emptyText' => 'No Active Incidents found. Please Check \'Archived Tickets\' for closed incidents.',
                        //'filterModel' => $searchModel,
                        'columns' => [
                            [
                                'attribute' => 'name',
                                'header' => 'Incident ID',
                                'value' => function ($model) {
                                    return '<a href=' . yii::$app->urlManager->createUrl('tickets/reports?id=' . Yii::$app->utils->encryptData($model->ticket_id)) . ' title="View" target="_blank">' . $model->ticket_name . '</a>';

                                },
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'theadcolor'],
                                'contentOptions' => ['class' => (!$columnsArr['c1']) ? 'hidden c1 tbl-td' : 'c1 tbl-td'],
                                'headerOptions' => ['class' => (!$columnsArr['c1']) ? 'hidden theadcolor c1 tbl-td' : 'theadcolor c1 tbl-td']
                            ],
                            [
                                'attribute' => 'audit_id',
                                'header' => 'Audit',
                                'value' => function ($model) use ($audits) {
                                    return (isset($audits[$model->audit_schedule_id])) ? $audits[$model->audit_schedule_id] : 'Dynamic Ticket';
                                },
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'theadcolor'],
                                'contentOptions' => ['class' => (!$columnsArr['c2']) ? 'hidden c2 tbl-td' : 'c2 tbl-td'],
                                'headerOptions' => ['class' => (!$columnsArr['c2']) ? 'hidden theadcolor c2 tbl-td' : 'theadcolor c2 tbl-td']
                            ],
                            [
                                'attribute' => 'hotel_id',
                                'header' => 'Office',
                                'value' => function ($model) {
                                    return ($model->hotel_id) ? $model->hotel->hotel_name : '--';
                                },
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'theadcolor'],
                                'contentOptions' => ['class' => (!$columnsArr['c3']) ? 'hidden c3 tbl-td' : 'c3 tbl-td'],
                                'headerOptions' => ['class' => (!$columnsArr['c3']) ? 'hidden theadcolor c3 tbl-td' : 'theadcolor c3 tbl-td']
                            ],
                            [
                                'attribute' => 'department_id',
                                'header' => 'Floor',
                                'value' => function ($model) {
                                    return ($model->department_id) ? $model->department->department_name : '--';
                                },
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'theadcolor'],
                                'contentOptions' => ['class' => (!$columnsArr['c4']) ? 'hidden c4 tbl-td' : 'c4 tbl-td'],
                                'headerOptions' => ['class' => (!$columnsArr['c4']) ? 'hidden theadcolor c4 tbl-td' : 'theadcolor c4 tbl-td']
                            ],
                            [
                                'attribute' => 'cabin',
                                'header' => 'Cabin',
                                'value' => function ($model) {
                                    return ($model->cabin_id) ? $model->cabins->cabin_id : '--';
                                },
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'theadcolor'],
                                'contentOptions' => ['class' => (!$columnsArr['c5']) ? 'hidden c5 tbl-td' : 'c5 tbl-td'],
                                'headerOptions' => ['class' => (!$columnsArr['c5']) ? 'hidden theadcolor c5 tbl-td' : 'theadcolor c5 tbl-td']
                            ],
                            [
                                'attribute' => 'subject',
                                'header' => 'Subject',
                                'value' => function ($model) {
                                    if (strlen($model->subject) > 25) {
                                        return '<span title="' . $model->subject . '"> ' . substr($model->subject, 0, 25) . '...</span>';
                                    }
                                    return '<span title="' . $model->subject . '"> ' . $model->subject . '</span>';

                                },
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'theadcolor'],
                                'contentOptions' => ['class' => (!$columnsArr['c6']) ? 'hidden c6 tbl-td' : 'c6 tbl-td'],
                                'headerOptions' => ['class' => (!$columnsArr['c6']) ? 'hidden theadcolor c6 tbl-td' : 'theadcolor c6 tbl-td']
                            ],
                            /* [
                                 'attribute' => 'section_id',
                                 'header' => 'Section',
                                 'value' => function ($model) {
                                     return $model->section->s_section_name;
                                 },
                                 'format' => 'raw',
                                 'headerOptions' => ['class' => 'theadcolor']
                             ],*/

                            [
                                'attribute' => 'assigned_id',
                                'header' => 'Assigned To',
                                'value' => function ($model) {
                                    return ucfirst($model->assignedUser->first_name) . ' ' . ucfirst($model->assignedUser->last_name);
                                },
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'theadcolor'],
                                'contentOptions' => ['class' => (!$columnsArr['c7']) ? 'hidden c7 tbl-td' : 'c7 tbl-td'],
                                'headerOptions' => ['class' => (!$columnsArr['c7']) ? 'hidden theadcolor c7 tbl-td' : 'theadcolor c7 tbl-td']
                            ],
                            [
                                'attribute' => 'created_at',
                                'header' => 'Created On',
                                'value' => function ($model) {
                                    $timestamp = strtotime($model->created_at);
                                    return Yii::$app->formatter->asDate($timestamp, 'php:d-m-Y');
                                },
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'theadcolor'],
                                'contentOptions' => ['class' => (!$columnsArr['c8']) ? 'hidden c8 tbl-td' : 'c8 tbl-td'],
                                'headerOptions' => ['class' => (!$columnsArr['c8']) ? 'hidden theadcolor c8 tbl-td' : 'theadcolor c8 tbl-td']
                            ],
                            [
                                'attribute' => 'due_date',
                                'header' => 'Due Date',
                                'value' => function ($model) {
                                    $timestamp = strtotime($model->due_date);
                                    $priority_id = $model->priority_type_id;

                                    if (date('Y-m-d') > date('Y-m-d', $timestamp)) {
                                        $color = "color:red;";
                                        return '<span style=' . $color . '>' . Yii::$app->formatter->asDate($timestamp, 'php:d-m-Y') . '</span>';
                                    }

                                    return Yii::$app->formatter->asDate($timestamp, 'php:d-m-Y');
                                },
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'theadcolor'],
                                'contentOptions' => ['class' => (!$columnsArr['c9']) ? 'hidden c9 tbl-td' : 'c9 tbl-td'],
                                'headerOptions' => ['class' => (!$columnsArr['c9']) ? 'hidden theadcolor c9 tbl-td' : 'theadcolor c9 tbl-td']
                            ],
                            [
                                'attribute' => 'overDueTicket',
                                'header' => 'Over due',
                                'value' => function ($model) {
                                    $timestamp = strtotime($model->due_date);
                                    if (date('Y-m-d') > date('Y-m-d', $timestamp)) {
                                        return 'Yes';
                                    }

                                    return 'No';
                                },
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'theadcolor'],
                                'contentOptions' => ['class' => (!$columnsArr['c10']) ? 'hidden c10 tbl-td' : 'c10 tbl-td'],
                                'headerOptions' => ['class' => (!$columnsArr['c10']) ? 'hidden theadcolor c10 tbl-td' : 'theadcolor c10 tbl-td']
                            ],
                            [
                                'attribute' => 'chronic',
                                'header' => 'Chronic',
                                'value' => function ($model) {
                                    switch ($model->chronicity) {
                                        case 0:
                                            $chronicity = 'No';
                                            break;
                                        case 1:
                                            $chronicity = 'Yes';
                                            break;
                                    }
                                    return $chronicity;
                                },
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'theadcolor'],
                                'contentOptions' => ['class' => (!$columnsArr['c11']) ? 'hidden c11 tbl-td' : 'c11 tbl-td'],
                                'headerOptions' => ['class' => (!$columnsArr['c11']) ? 'hidden theadcolor c11 tbl-td' : 'theadcolor c11 tbl-td']
                            ],
                            [
                                'attribute' => 'priority_type_id',
                                'header' => 'Priority',
                                'value' => function ($model) {

                                    $priority_id = $model->priority_type_id;
                                    if ($priority_id == 1) {
                                        $color = "color:red;";
                                        return '<span style=' . $color . '>' . $model->priorityType->priority_name . '</span>';
                                    } else {
                                        return $model->priorityType->priority_name;
                                    }
                                },
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'theadcolor'],
                                'contentOptions' => ['class' => (!$columnsArr['c12']) ? 'hidden c12 tbl-td' : 'c12 tbl-td'],
                                'headerOptions' => ['class' => (!$columnsArr['c12']) ? 'hidden theadcolor c12 tbl-td' : 'theadcolor c12 tbl-td']
                            ],
                            [
                                'attribute' => 'process_critical',
                                'header' => 'Process Critical (Audit)',
                                'headerOptions' => ['class' => 'theadcolor'],
                                'value' => function ($model) {
                                $data=Tickets::getAnswers($model->answer_id);
                               
                                switch ($data['question']['process_critical']) {
                                    case 0:
                                        $process = 'No';
                                        break;
                                    case 1:
                                        $process = 'Yes';
                                        break;
                                }
                                return $process;
                                },
                                'contentOptions' => ['class' => (!$columnsArr['c13']) ? 'hidden c13 tbl-td' : 'c13 tbl-td'],
                                'headerOptions' => ['class' => (!$columnsArr['c13']) ? 'hidden theadcolor c13 tbl-td' : 'theadcolor c13 tbl-td']
                                
                                ],
                                
                               /* [
                                    'attribute' => 'process_critical_dynamic',
                                    'header' => 'Process Critical (Dynamic)',
                                    'headerOptions' => ['class' => 'theadcolor'],
                                    'value' => function ($model) {
                                    $process='-';
                                    if($model->audit_schedule_id){
                                        switch ($model->process_critical_dynamic) {
                                            case 0:
                                                $process = 'No';
                                                break;
                                            case 1:
                                                $process = 'Yes';
                                                break;
                                        }
                                    }
                                    
                                    return $process;
                                    },
                                    ],*/
                            [
                                'attribute' => 'status',
                                'header' => 'Status',
                                'value' => function ($model) {
                                    return \app\models\Tickets::$statusList[$model->status];
                                },
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'theadcolor']
                                ,
                                'contentOptions' => ['class' => (!$columnsArr['c14']) ? 'hidden c14 tbl-td' : 'c14 tbl-td'],
                                'headerOptions' => ['class' => (!$columnsArr['c14']) ? 'hidden theadcolor c14 tbl-td' : 'theadcolor c14 tbl-td']
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'header' => 'Actions',
                                'headerOptions' => ['class' => 'theadcolor'],
                                'template' => $buttons,
                                'buttons' => [
                                    'update' => function ($url, $model) {
                                        return Html::a('<i class="fa fa-edit"></i>', ['incidents/update', 'id' => Yii::$app->utils->encryptData($model->ticket_id)], [
                                            'title' => Yii::t('yii', 'Edit'),
                                        ]);
                                    },
                                    /*  'delete' => function ($url, $model) {
                                         return '<a href="javascript:void(0)" title="Delete" class="delete_ticket_btn" data-token =' . yii::$app->utils->encryptData($model->ticket_id) . '><i class="fa fa-trash-o" title="Delete"></i></a>';
                                     }, */

                                    'cancel' => function ($url, $model) {
                                        return (in_array($model->status, [3, 4])) ? '-' : '<a href="javascript:void(0)" title="Edit" class="cancel_ticekts_btn" data-token =' . yii::$app->utils->encryptData($model->ticket_id) . '><i class="fa fa-close" title="Cancel"></i></a>';
                                    },

                                ]
                            ],
                        ],
                    ]);
                    ?>
                    <?php Pjax::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Archived tickets -->

<?php
$buttons = '';


if (Yii::$app->authManager->checkPermissionAccess('tickets/delete')) {
    $buttons .= '&nbsp;&nbsp;{delete}';
}

?>

<div class="row archiveTicketsData" style="display:none">
    <div class="tab-content">
        <div id="activeaudits" class="tab-pane fade in active">
            <div class="col-sm-12 nopadding">
                <div class="tickets-index">
                    <?php Pjax::begin(); ?>
                    <?=
                    GridView::widget([
                        'dataProvider' => $dataArchivedProvider,
                        'layout' => '{items}{pager}',
                        //'filterModel' => $searchModel,
                        'columns' => [
                            [
                                'attribute' => 'name',
                                'header' => 'Incident ID',
                                'value' => function ($model) {
                                    return '<a href=' . yii::$app->urlManager->createUrl('tickets/reports?id=' . Yii::$app->utils->encryptData($model->ticket_id)) . ' title="View" target="_blank">' . $model->ticket_name . '</a>';

                                },
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'theadcolor'],
                                'contentOptions' => ['class' => (!$columnsArr['c1']) ? 'hidden c1 tbl-td' : 'c1 tbl-td'],
                                'headerOptions' => ['class' => (!$columnsArr['c1']) ? 'hidden theadcolor c1 tbl-td' : 'theadcolor c1 tbl-td']
                            ],
                            [
                                'attribute' => 'audit_id',
                                'header' => 'Audit',
                                'value' => function ($model) use ($audits) {
                                    return (isset($audits[$model->audit_schedule_id])) ? $audits[$model->audit_schedule_id] : 'Dynamic Ticket';
                                },
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'theadcolor'],
                                'contentOptions' => ['class' => (!$columnsArr['c2']) ? 'hidden c2 tbl-td' : 'c2 tbl-td'],
                                'headerOptions' => ['class' => (!$columnsArr['c2']) ? 'hidden theadcolor c2 tbl-td' : 'theadcolor c2 tbl-td']
                            ],
                            [
                                'attribute' => 'hotel_id',
                                'header' => 'Office',
                                'value' => function ($model) {
                                    return ($model->hotel_id) ? $model->hotel->hotel_name : '--';
                                },
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'theadcolor'],
                                'contentOptions' => ['class' => (!$columnsArr['c3']) ? 'hidden c3 tbl-td' : 'c3 tbl-td'],
                                'headerOptions' => ['class' => (!$columnsArr['c3']) ? 'hidden theadcolor c3 tbl-td' : 'theadcolor c3 tbl-td']
                            ],
                            [
                                'attribute' => 'department_id',
                                'header' => 'Floor',
                                'value' => function ($model) {
                                    return ($model->department_id) ? $model->department->department_name : '--';
                                },
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'theadcolor'],
                                'contentOptions' => ['class' => (!$columnsArr['c4']) ? 'hidden c4 tbl-td' : 'c4 tbl-td'],
                                'headerOptions' => ['class' => (!$columnsArr['c4']) ? 'hidden theadcolor c4 tbl-td' : 'theadcolor c4 tbl-td']
                            ],
                            [
                                'attribute' => 'cabin',
                                'header' => 'Cabin',
                                'value' => function ($model) {
                                    return ($model->cabin_id) ? $model->cabins->cabin_id : '--';
                                },
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'theadcolor'],
                                'contentOptions' => ['class' => (!$columnsArr['c5']) ? 'hidden c5 tbl-td' : 'c5 tbl-td'],
                                'headerOptions' => ['class' => (!$columnsArr['c5']) ? 'hidden theadcolor c5 tbl-td' : 'theadcolor c5 tbl-td']
                            ],
                            [
                                'attribute' => 'subject',
                                'header' => 'Subject',
                                'value' => function ($model) {
                                    if (strlen($model->subject) > 25) {
                                        return '<span title="' . $model->subject . '"> ' . substr($model->subject, 0, 25) . '...</span>';
                                    }
                                    return '<span title="' . $model->subject . '"> ' . $model->subject . '</span>';

                                },
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'theadcolor'],
                                'contentOptions' => ['class' => (!$columnsArr['c6']) ? 'hidden c6 tbl-td' : 'c6 tbl-td'],
                                'headerOptions' => ['class' => (!$columnsArr['c6']) ? 'hidden theadcolor c6 tbl-td' : 'theadcolor c6 tbl-td']
                            ],
                            /* [
                                 'attribute' => 'section_id',
                                 'header' => 'Section',
                                 'value' => function ($model) {
                                     return $model->section->s_section_name;
                                 },
                                 'format' => 'raw',
                                 'headerOptions' => ['class' => 'theadcolor']
                             ],*/

                            [
                                'attribute' => 'assigned_id',
                                'header' => 'Assigned To',
                                'value' => function ($model) {
                                    return ucfirst($model->assignedUser->first_name) . ' ' . ucfirst($model->assignedUser->last_name);
                                },
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'theadcolor'],
                                'contentOptions' => ['class' => (!$columnsArr['c7']) ? 'hidden c7 tbl-td' : 'c7 tbl-td'],
                                'headerOptions' => ['class' => (!$columnsArr['c7']) ? 'hidden theadcolor c7 tbl-td' : 'theadcolor c7 tbl-td']
                            ],
                            [
                                'attribute' => 'created_at',
                                'header' => 'Created On',
                                'value' => function ($model) {
                                    $timestamp = strtotime($model->created_at);
                                    return Yii::$app->formatter->asDate($timestamp, 'php:d-m-Y');
                                },
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'theadcolor'],
                                'contentOptions' => ['class' => (!$columnsArr['c8']) ? 'hidden c8 tbl-td' : 'c8 tbl-td'],
                                'headerOptions' => ['class' => (!$columnsArr['c8']) ? 'hidden theadcolor c8 tbl-td' : 'theadcolor c8 tbl-td']
                            ],
                            [
                                'attribute' => 'due_date',
                                'header' => 'Due Date',
                                'value' => function ($model) {
                                    $timestamp = strtotime($model->due_date);
                                    $priority_id = $model->priority_type_id;

                                    if (date('Y-m-d') > date('Y-m-d', $timestamp)) {
                                        $color = "color:red;";
                                        return '<span style=' . $color . '>' . Yii::$app->formatter->asDate($timestamp, 'php:d-m-Y') . '</span>';
                                    }

                                    return Yii::$app->formatter->asDate($timestamp, 'php:d-m-Y');
                                },
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'theadcolor'],
                                'contentOptions' => ['class' => (!$columnsArr['c9']) ? 'hidden c9 tbl-td' : 'c9 tbl-td'],
                                'headerOptions' => ['class' => (!$columnsArr['c9']) ? 'hidden theadcolor c9 tbl-td' : 'theadcolor c9 tbl-td']
                            ],
                            [
                                'attribute' => 'overDueTicket',
                                'header' => 'Over due',
                                'value' => function ($model) {
                                    $timestamp = strtotime($model->due_date);
                                    if (date('Y-m-d') > date('Y-m-d', $timestamp)) {
                                        return 'Yes';
                                    }

                                    return 'No';
                                },
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'theadcolor'],
                                'contentOptions' => ['class' => (!$columnsArr['c10']) ? 'hidden c10 tbl-td' : 'c10 tbl-td'],
                                'headerOptions' => ['class' => (!$columnsArr['c10']) ? 'hidden theadcolor c10 tbl-td' : 'theadcolor c10 tbl-td']
                            ],
                            [
                                'attribute' => 'chronic',
                                'header' => 'Chronic',
                                'value' => function ($model) {
                                    switch ($model->chronicity) {
                                        case 0:
                                            $chronicity = 'No';
                                            break;
                                        case 1:
                                            $chronicity = 'Yes';
                                            break;
                                    }
                                    return $chronicity;
                                },
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'theadcolor'],
                                'contentOptions' => ['class' => (!$columnsArr['c11']) ? 'hidden c11 tbl-td' : 'c11 tbl-td'],
                                'headerOptions' => ['class' => (!$columnsArr['c11']) ? 'hidden theadcolor c11 tbl-td' : 'theadcolor c11 tbl-td']
                            ],
                            [
                                'attribute' => 'priority_type_id',
                                'header' => 'Priority',
                                'value' => function ($model) {

                                    $priority_id = $model->priority_type_id;
                                    if ($priority_id == 1) {
                                        $color = "color:red;";
                                        return '<span style=' . $color . '>' . $model->priorityType->priority_name . '</span>';
                                    } else {
                                        return $model->priorityType->priority_name;
                                    }
                                },
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'theadcolor'],
                                'contentOptions' => ['class' => (!$columnsArr['c12']) ? 'hidden c12 tbl-td' : 'c12 tbl-td'],
                                'headerOptions' => ['class' => (!$columnsArr['c12']) ? 'hidden theadcolor c12 tbl-td' : 'theadcolor c12 tbl-td']
                            ],
                            [
                                'attribute' => 'process_critical',
                                'header' => 'Process Critical (Audit)',
                                'headerOptions' => ['class' => 'theadcolor'],
                                'value' => function ($model) {
                                $data=Tickets::getAnswers($model->answer_id);
                               
                                switch ($data['question']['process_critical']) {
                                    case 0:
                                        $process = 'No';
                                        break;
                                    case 1:
                                        $process = 'Yes';
                                        break;
                                }
                                return $process;
                                },
                                'contentOptions' => ['class' => (!$columnsArr['c13']) ? 'hidden c13 tbl-td' : 'c13 tbl-td'],
                                'headerOptions' => ['class' => (!$columnsArr['c13']) ? 'hidden theadcolor c13 tbl-td' : 'theadcolor c13 tbl-td']
                                
                                ],
                                
                               /* [
                                    'attribute' => 'process_critical_dynamic',
                                    'header' => 'Process Critical (Dynamic)',
                                    'headerOptions' => ['class' => 'theadcolor'],
                                    'value' => function ($model) {
                                    $process='-';
                                    if($model->audit_schedule_id){
                                        switch ($model->process_critical_dynamic) {
                                            case 0:
                                                $process = 'No';
                                                break;
                                            case 1:
                                                $process = 'Yes';
                                                break;
                                        }
                                    }
                                    
                                    return $process;
                                    },
                                    ],*/
                            [
                                'attribute' => 'status',
                                'header' => 'Status',
                                'value' => function ($model) {
                                    return \app\models\Tickets::$statusList[$model->status];
                                },
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'theadcolor']
                                ,
                                'contentOptions' => ['class' => (!$columnsArr['c14']) ? 'hidden c14 tbl-td' : 'c14 tbl-td'],
                                'headerOptions' => ['class' => (!$columnsArr['c14']) ? 'hidden theadcolor c14 tbl-td' : 'theadcolor c14 tbl-td']
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'header' => 'Actions',
                                'headerOptions' => ['class' => 'theadcolor'],
                                'template' => $buttons,
                                'buttons' => [
                                    'update' => function ($url, $model) {
                                        return Html::a('<i class="fa fa-edit"></i>', ['incidents/update', 'id' => Yii::$app->utils->encryptData($model->ticket_id)], [
                                            'title' => Yii::t('yii', 'Edit'),
                                        ]);
                                    },
                                    /*  'delete' => function ($url, $model) {
                                         return '<a href="javascript:void(0)" title="Delete" class="delete_ticket_btn" data-token =' . yii::$app->utils->encryptData($model->ticket_id) . '><i class="fa fa-trash-o" title="Delete"></i></a>';
                                     }, */

                                    'cancel' => function ($url, $model) {
                                        return (in_array($model->status, [3, 4])) ? '-' : '<a href="javascript:void(0)" title="Edit" class="cancel_ticekts_btn" data-token =' . yii::$app->utils->encryptData($model->ticket_id) . '><i class="fa fa-close" title="Cancel"></i></a>';
                                    },

                                ]
                            ],
                        ],
                    ]);
                    ?>
                    <?php Pjax::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Archived Tickets-->


<!----------------------Delete Popup Start hare -->
<div id="deletepopup" class="modal fade" role="dialog">
    <?php ActiveForm::begin(['id' => 'delete_tickets_form', 'action' => yii::$app->urlManager->createUrl('incidents/delete'), 'method' => 'post',]) ?>
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" style="color: #fff!important; opacity: 1;"
                        aria-hidden="true">
                    x
                </button>
                <h4 class="modal-title">Confirmation</h4>
            </div>
            <div class="modal-body clearfix">
                <input type="hidden" name="deletable_ticket_id" id="deletable_ticket_id" value=""/>
                <div class="col-sm-12" style="margin-top: 20px;">
                    <label>
                        Are you sure you want to delete this incident? You can't undo this action.
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

<!----------------------Cancel Tickets Popup Start hare -->
<div id="cancelpopup" class="modal fade" role="dialog">
    <?php ActiveForm::begin(['id' => 'cancel_auditschedule_form', 'action' => yii::$app->urlManager->createUrl('incidents/cancel'), 'method' => 'post',]) ?>
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
                <input type="hidden" name="cancel_ticekts_id" id="cancel_ticekts_id" value=""/>
                <div class="col-sm-12" style="margin-top: 20px;">
                    <label>
                        Are you sure you want to cancel this incident? You can't undo this action.
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
