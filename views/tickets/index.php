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

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\TicketsSearch */

$this->title = 'Tickets';
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
$("#tickets").addClass("active");
$(".dropdown-toggle").dropdown();
var selectedVals=[];
$("#example-getting-started option:selected").map(function(a, item){selectedVals.push(item.value);});
console.log(selectedVals);
$("#example-getting-started").multiselect({
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
        console.log(selectedVals);
    }
});

$(".multiselect-native-select .btn-group").click(function(){
 $(this).toggleClass("open");
});

$("#submitGridSelectionBtn").click(function(){
    console.log("dd");
    $.post({
            url: save_url,
            data: {selected_columns:selectedVals,grid_type:$(this).data("type")},
            success: function(data) {
                console.log(data);
                response = JSON.parse(data);
                if(response.output){
                    toastr.success("Columns saved successfully");
                }
                
            }
        });   

});
', \yii\web\View::POS_END);
?>
<div class="container-fluid">
    <h2>Tickets</h2>
</div>

<!-- notification text -->
<div class="wa-notification wa-notification-alt">
    <span class="wa-iconBoxed">
        <span class="fa fa-file-text-o header-icon-fontcolor"></span>
    </span>
    <p id="description-text">
        All ticketing activites including creation, assignments and tracking can be managed here.

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
        'header' => 'Ticket ID',
    ],
    [
        'attribute' => 'audit_id',
        'header' => 'Audit',
        'value' => function ($model) use ($audits) {
            return (isset($audits[$model->audit_schedule_id])) ? $audits[$model->audit_schedule_id] : 'Dynamic Ticket';
        },
    ],
    [
        'attribute' => 'hotel_id',
        'header' => 'Office',
        'value' => function ($model) {
            return ($model->hotel_id) ? $model->hotel->hotel_name : '--';
        },

    ],
    [
        'attribute' => 'department_id',
        'header' => 'Floor',
        'value' => function ($model) {
            return ($model->department_id) ? $model->department->department_name : '--';
        },
    ],
    [
        'attribute' => 'section_id',
        'header' => 'Section',
        'value' => function ($model) {
            $str = $model->section->s_section_name;
            $str = html_entity_decode($str, ENT_QUOTES | ENT_XML1, 'UTF-8');
            $str = htmlspecialchars_decode($str);
            $str = html_entity_decode($str);
            $str = strip_tags($str);
            return $str;
            //return $model->section->s_section_name;
        },
    ],
    [
        'attribute' => 'sub_section_id',
        'header' => 'Subsection',
        'value' => function ($model) use ($subSectionList) {
            return isset($subSectionList[$model->sub_section_id]) ? $subSectionList[$model->sub_section_id] : $model->sub_section_id;
        },
    ],
    [
        'attribute' => 'subject',
        'header' => 'Subject',
        'value' => function ($model) {
            return strip_tags($model->subject);
        },
    ],
    [
        'attribute' => 'description',
        'header' => 'Observation',
        'value' => function ($model) {
            return strip_tags($model->description);
        },
    ],
    [
        'attribute' => 'assigned_id',
        'header' => 'Assigned To',
        'value' => function ($model) {
            return ucfirst($model->assignedUser->first_name) . ' ' . ucfirst($model->assignedUser->last_name);
        },

    ],
    [
        'attribute' => 'created_at',
        'header' => 'Created On',
        'value' => function ($model) {
            $timestamp = strtotime($model->created_at);
            return Yii::$app->formatter->asDate($timestamp, 'php:d-m-Y');
        },
    ],
    [
        'attribute' => 'due_date',
        'header' => 'Due Date',
        'value' => function ($model) {
            $timestamp = strtotime($model->due_date);
            return Yii::$app->formatter->asDate($timestamp, 'php:d-m-Y');
        },
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

    ],
    [
        'attribute' => 'priority_type_id',
        'header' => 'Priority',
        'value' => function ($model) {
            return $model->priorityType->priority_name;
        },
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
            ],
        
        [
            'attribute' => 'prob_module_id',
            'header' => 'Problem Classification',
            'value' => function ($model) {
            $process_data=TicketProcessCritical::findOne(['ticket_id'=>$model->ticket_id]);
            
            $val = empty($process_data)?'-':$process_data->probModule->module_option;
            
            unset($process_data);
            
            return $val;
            },
            ],
            
        [
            'attribute' => 'root_cause',
            'header' => 'Root Cause',
            'value' => function ($model) {
            $process_data=TicketProcessCritical::findOne(['ticket_id'=>$model->ticket_id]);
            
            $val = empty($process_data)?'-':$process_data->root_cause;
            
            unset($process_data);
            
            return $val;
            },
            ],
            
            [
                'attribute' => 'improvement_plan',
                'header' => 'Improvement Plan for Zero Deviation',
                'value' => function ($model) {
                $process_data=TicketProcessCritical::findOne(['ticket_id'=>$model->ticket_id]);
                
                $val = empty($process_data)?'-':$process_data->improvement_plan;
                
                unset($process_data);
                
                return $val;
                },
                ],
                
            [
                'attribute' => 'improve_plan_module_id',
                'header' => 'Improvement Plan Classification',
                'value' => function ($model) {
                $process_data=TicketProcessCritical::findOne(['ticket_id'=>$model->ticket_id]);
                
                $val = empty($process_data)?'-':$process_data->improvePlanModule->module_option;
                
                unset($process_data);
                
                return $val;
                },
                ],
            
    [
        'attribute' => 'status',
        'header' => 'Status',
        'value' => function ($model) {
            return \app\models\Tickets::$statusList[$model->status];
        },
    ],
];

$archivedTickets = [
    [
        'attribute' => 'ticket_name',
        'header' => 'Ticket ID',
    ],
    [
        'attribute' => 'audit_id',
        'header' => 'Audit',
        'value' => function ($model) use ($audits) {
            return (isset($audits[$model->audit_schedule_id])) ? $audits[$model->audit_schedule_id] : 'Dynamic Ticket';
        },
    ],
    [
        'attribute' => 'hotel_id',
        'header' => 'Office',
        'value' => function ($model) {
            return ($model->hotel_id) ? $model->hotel->hotel_name : '--';
        },
    ],
    [
        'attribute' => 'department_id',
        'header' => 'Floor',
        'value' => function ($model) {
            return ($model->department_id) ? $model->department->department_name : '--';
        },
    ],
    [
        'attribute' => 'section_id',
        'header' => 'Section',
        'value' => function ($model) {
            $str = $model->section->s_section_name;
            $str = html_entity_decode($str, ENT_QUOTES | ENT_XML1, 'UTF-8');
            $str = htmlspecialchars_decode($str);
            $str = html_entity_decode($str);
            $str = strip_tags($str);
            return $str;
            return $model->section->s_section_name;
        },
    ],
    [
        'attribute' => 'sub_section_id',
        'header' => 'Section',
        'value' => function ($model) use ($subSectionList) {
            return isset($subSectionList[$model->sub_section_id]) ? $subSectionList[$model->sub_section_id] : $model->sub_section_id;
        },
    ],
    [
        'attribute' => 'subject',
        'header' => 'Subject',
        'value' => function ($model) {
            return strip_tags($model->subject);
        },
    ],
    [
        'attribute' => 'description',
        'header' => 'Observation',
        'value' => function ($model) {
            return strip_tags($model->description);
        },
    ],
    [
        'attribute' => 'assigned_id',
        'header' => 'Assigned To',
        'value' => function ($model) {
            return ucfirst($model->assignedUser->first_name) . ' ' . ucfirst($model->assignedUser->last_name);
        },
    ],
    [
        'attribute' => 'created_at',
        'header' => 'Created On',
        'value' => function ($model) {
            $timestamp = strtotime($model->created_at);
            return Yii::$app->formatter->asDate($timestamp, 'php:d-m-Y');
        },
    ],
    [
        'attribute' => 'due_date',
        'header' => 'Due Date',
        'value' => function ($model) {
            $timestamp = strtotime($model->due_date);
            return Yii::$app->formatter->asDate($timestamp, 'php:d-m-Y');
        },
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
    ],
    [
        'attribute' => 'priority_type_id',
        'header' => 'Priority',
        'value' => function ($model) {
            return $model->priorityType->priority_name;
        },
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
            ],
        
        [
            'attribute' => 'prob_module_id',
            'header' => 'Problem Classification',
            'value' => function ($model) {
            $process_data=TicketProcessCritical::findOne(['ticket_id'=>$model->ticket_id]);
            
            $val = empty($process_data)?'-':$process_data->probModule->module_option;
            
            unset($process_data);
            
            return $val;
            },
            ],
            
            [
                'attribute' => 'root_cause',
                'header' => 'Root Cause',
                'value' => function ($model) {
                $process_data=TicketProcessCritical::findOne(['ticket_id'=>$model->ticket_id]);
                
                $val = empty($process_data)?'-':$process_data->root_cause;
                
                unset($process_data);
                
                return $val;
                },
                ],
                
                [
                    'attribute' => 'improvement_plan',
                    'header' => 'Improvement Plan for Zero Deviation',
                    'value' => function ($model) {
                    $process_data=TicketProcessCritical::findOne(['ticket_id'=>$model->ticket_id]);
                    
                    $val = empty($process_data)?'-':$process_data->improvement_plan;
                    unset($process_data);
                    
                    return $val;
                    },
                    ],
                    
                    [
                        'attribute' => 'improve_plan_module_id',
                        'header' => 'Improvement Plan Classification',
                        'value' => function ($model) {
                        $process_data=TicketProcessCritical::findOne(['ticket_id'=>$model->ticket_id]);
                        
                        $val = empty($process_data)?'-':$process_data->improvePlanModule->module_option;
                        
                        unset($process_data);
                        
                        return $val;
                        },
                        ],
                        
    [
        'attribute' => 'status',
        'header' => 'Status',
        'value' => function ($model) {
            return \app\models\Tickets::$statusList[$model->status];
        },
    ],
];
?>
<div class="row">
<div class="col-md-12 nopadding">
            <div class="col-md-3">
                <select id="example-getting-started" multiple="multiple">
                    <?php
                        foreach($tableColumnsArr as $index=>$column){
                            ?>
                            <option value="<?=$index?>" <?=$columnsArr[$index] ? 'selected' : ''?>><?=$column?></option>
                            <?php
                        }
                    ?>
                </select>
            </div>
            <div class="col-md-3">
                <button class="btn btn-success" id="submitGridSelectionBtn" data-type="tickets">Save</button>
            </div>
        </div>
    <div class="col-lg-12 nopadding">
        <ul class="nav nav-tabs">
            <li class="active">
                <a href="#" class="activetickets tabs active" data-toggle="tab" name="tab" data-index="0"><i
                            class="fa fa-folder-open" aria-hidden="true" aria-expanded="true"></i>&nbsp;Active
                    Tickets</a>
            </li>
            <li>
                <a href="#" class="archivedtickets tabs" data-toggle="tab" name="tab" data-index="1"><i
                            class="fa fa-archive" aria-hidden="true" aria-expanded="false"></i>&nbsp;Archived
                    Tickets</a>
            </li>
            <?php if (Yii::$app->authManager->checkPermissionAccess('tickets/create')) { ?>
                <li class="pull-right">

                    <a href="<?= yii::$app->urlManager->createUrl('tickets/create'); ?>" class="btn btn-success"><i
                                class="fa fa-plus"></i>&nbsp;Create Ticket</a>
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
                            'label' => 'Archived Tickets',
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
                    'filename' => 'Archived Tickets',
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
                            'label' => 'Active Tickets',
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
                    'filename' => 'Active Tickets',
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
                        'emptyText' => 'No Active Tickets found. Please Check \'Archived Tickets\' for closed tickets.',
                        //'filterModel' => $searchModel,
                        'columns' => [
                            [
                                'attribute' => 'name',
                                'header' => 'Ticket ID',
                                'value' => function ($model) {
                                    return '<a href=' . yii::$app->urlManager->createUrl('tickets/reports?id=' . Yii::$app->utils->encryptData($model->ticket_id)) . ' title="View" target="_blank">' . $model->ticket_name . '</a>';

                                },
                                'format' => 'raw',
                                'contentOptions' => ['class' => (!$columnsArr['c1']) ? 'hidden c1' : 'c1'],
                                'headerOptions' => ['class' => (!$columnsArr['c1']) ? 'hidden theadcolor c1' : 'theadcolor c1']
                            ],
                            [
                                'attribute' => 'audit_id',
                                'header' => 'Audit',
                                'value' => function ($model) use ($audits) {
                                    return (isset($audits[$model->audit_schedule_id])) ? $audits[$model->audit_schedule_id] : 'Dynamic Ticket';
                                },
                                'format' => 'raw',
                                'contentOptions' => ['class' => (!$columnsArr['c2']) ? 'hidden c2' : 'c2'],
                                'headerOptions' => ['class' => (!$columnsArr['c2']) ? 'hidden theadcolor c2' : 'theadcolor c2']
                            ],
                            [
                                'attribute' => 'hotel_id',
                                'header' => 'Office',
                                'value' => function ($model) {
                                    return ($model->hotel_id) ? $model->hotel->hotel_name : '--';
                                },
                                'format' => 'raw',
                                'contentOptions' => ['class' => (!$columnsArr['c3']) ? 'hidden c3' : 'c3'],
                                'headerOptions' => ['class' => (!$columnsArr['c3']) ? 'hidden theadcolor c3' : 'theadcolor c3']
                            ],
                            [
                                'attribute' => 'department_id',
                                'header' => 'Floor',
                                'value' => function ($model) {
                                    return ($model->department_id) ? $model->department->department_name : '--';
                                },
                                'format' => 'raw',
                                'contentOptions' => ['class' => (!$columnsArr['c4']) ? 'hidden c4' : 'c4'],
                                'headerOptions' => ['class' => (!$columnsArr['c4']) ? 'hidden theadcolor c4' : 'theadcolor c4']
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
                                'contentOptions' => ['class' => (!$columnsArr['c5']) ? 'hidden c5' : 'c5'],
                                'headerOptions' => ['class' => (!$columnsArr['c5']) ? 'hidden theadcolor c5' : 'theadcolor c5']
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
                                'contentOptions' => ['class' => (!$columnsArr['c6']) ? 'hidden c6' : 'c6'],
                                'headerOptions' => ['class' => (!$columnsArr['c6']) ? 'hidden theadcolor c6' : 'theadcolor c6']
                            ],
                            [
                                'attribute' => 'created_at',
                                'header' => 'Created On',
                                'value' => function ($model) {
                                    $timestamp = strtotime($model->created_at);
                                    return Yii::$app->formatter->asDate($timestamp, 'php:d-m-Y');
                                },
                                'format' => 'raw',
                                'contentOptions' => ['class' => (!$columnsArr['c7']) ? 'hidden c7' : 'c7'],
                                'headerOptions' => ['class' => (!$columnsArr['c7']) ? 'hidden theadcolor c7' : 'theadcolor c7']
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
                                'contentOptions' => ['class' => (!$columnsArr['c8']) ? 'hidden c8' : 'c8'],
                                'headerOptions' => ['class' => (!$columnsArr['c8']) ? 'hidden theadcolor c8' : 'theadcolor c8']
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
                                'contentOptions' => ['class' => (!$columnsArr['c9']) ? 'hidden c9' : 'c9'],
                                'headerOptions' => ['class' => (!$columnsArr['c9']) ? 'hidden theadcolor c9' : 'theadcolor c9']
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
                                'contentOptions' => ['class' => (!$columnsArr['c10']) ? 'hidden c10' : 'c10'],
                                'headerOptions' => ['class' => (!$columnsArr['c10']) ? 'hidden theadcolor c10' : 'theadcolor c10']
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
                                'contentOptions' => ['class' => (!$columnsArr['c11']) ? 'hidden c11' : 'c11'],
                                'headerOptions' => ['class' => (!$columnsArr['c11']) ? 'hidden theadcolor c11' : 'theadcolor c11']
                            ],
                            [
                                'attribute' => 'process_critical',
                                'header' => 'Process Critical (Audit)',
                                'contentOptions' => ['class' => (!$columnsArr['c12']) ? 'hidden c12' : 'c12'],
                                'headerOptions' => ['class' => (!$columnsArr['c12']) ? 'hidden theadcolor c12' : 'theadcolor c12'],
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
                                'contentOptions' => ['class' => (!$columnsArr['c13']) ? 'hidden c13' : 'c13'],
                                'headerOptions' => ['class' => (!$columnsArr['c13']) ? 'hidden theadcolor c13' : 'theadcolor c13']
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'header' => 'Actions',
                                'headerOptions' => ['class' => 'theadcolor'],
                                'template' => $buttons,
                                'buttons' => [
                                    'update' => function ($url, $model) {
                                        return Html::a('<i class="fa fa-edit"></i>', ['tickets/update', 'id' => Yii::$app->utils->encryptData($model->ticket_id)], [
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
                                'header' => 'Ticket ID',
                                'value' => function ($model) {
                                    return '<a href=' . yii::$app->urlManager->createUrl('tickets/reports?id=' . Yii::$app->utils->encryptData($model->ticket_id)) . ' title="View" target="_blank">' . $model->ticket_name . '</a>';

                                },
                                'format' => 'raw',
                                'contentOptions' => ['class' => (!$columnsArr['c1']) ? 'hidden c1' : 'c1'],
                                'headerOptions' => ['class' => (!$columnsArr['c1']) ? 'hidden theadcolor c1' : 'theadcolor c1']
                            ],
                            [
                                'attribute' => 'audit_id',
                                'header' => 'Audit',
                                'value' => function ($model) use ($audits) {
                                    return (isset($audits[$model->audit_schedule_id])) ? $audits[$model->audit_schedule_id] : 'Dynamic Ticket';
                                },
                                'format' => 'raw',
                                'contentOptions' => ['class' => (!$columnsArr['c2']) ? 'hidden c2' : 'c2'],
                                'headerOptions' => ['class' => (!$columnsArr['c2']) ? 'hidden theadcolor c2' : 'theadcolor c2']
                            ],
                            [
                                'attribute' => 'hotel_id',
                                'header' => 'Office',
                                'value' => function ($model) {
                                    return ($model->hotel_id) ? $model->hotel->hotel_name : '--';
                                },
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'theadcolor'],
                                'contentOptions' => ['class' => (!$columnsArr['c3']) ? 'hidden c3' : 'c3'],
                                'headerOptions' => ['class' => (!$columnsArr['c3']) ? 'hidden theadcolor c3' : 'theadcolor c3']
                            ],
                            [
                                'attribute' => 'department_id',
                                'header' => 'Floor',
                                'value' => function ($model) {
                                    return ($model->department_id) ? $model->department->department_name : '--';
                                },
                                'format' => 'raw',
                                'contentOptions' => ['class' => (!$columnsArr['c4']) ? 'hidden c4' : 'c4'],
                                'headerOptions' => ['class' => (!$columnsArr['c4']) ? 'hidden theadcolor c4' : 'theadcolor c4']
                            ],
                            
                            [
                                'attribute' => 'section_id',
                                'header' => 'Section',
                                'value' => function ($model) {
                                    $str = $model->section->s_section_name;
                                    /*if (strlen($str) > 8) {
                                        return '<span title="' . $str . '"> ' . substr($str, 0, 8) . '...</span>';
                                    }*/
                                    return $str;
                                },
                                'format' => 'raw',
                                'contentOptions' => ['class' => (!$columnsArr['c5']) ? 'hidden c5' : 'c5'],
                                'headerOptions' => ['class' => (!$columnsArr['c5']) ? 'hidden theadcolor c5' : 'theadcolor c5']
                            ],

                            [
                                'attribute' => 'assigned_id',
                                'header' => 'Assigned To',
                                'value' => function ($model) {
                                    return ucfirst($model->assignedUser->first_name) . ' ' . ucfirst($model->assignedUser->last_name);
                                },
                                'format' => 'raw',
                                'contentOptions' => ['class' => (!$columnsArr['c6']) ? 'hidden c6' : 'c6'],
                                'headerOptions' => ['class' => (!$columnsArr['c6']) ? 'hidden theadcolor c6' : 'theadcolor c6']
                            ],
                            [
                                'attribute' => 'created_at',
                                'header' => 'Created On',
                                'value' => function ($model) {
                                    $timestamp = strtotime($model->created_at);
                                    return Yii::$app->formatter->asDate($timestamp, 'php:d-m-Y');
                                },
                                'format' => 'raw',
                                'contentOptions' => ['class' => (!$columnsArr['c7']) ? 'hidden c7' : 'c7'],
                                'headerOptions' => ['class' => (!$columnsArr['c7']) ? 'hidden theadcolor c7' : 'theadcolor c7']
                            ],
                            [
                                'attribute' => 'due_date',
                                'header' => 'Due Date',
                                'value' => function ($model) {
                                    $timestamp = strtotime($model->due_date);
                                    return Yii::$app->formatter->asDate($timestamp, 'php:d-m-Y');
                                },
                                'format' => 'raw',
                                'contentOptions' => ['class' => (!$columnsArr['c8']) ? 'hidden c8' : 'c8'],
                                'headerOptions' => ['class' => (!$columnsArr['c8']) ? 'hidden theadcolor c8' : 'theadcolor c8']
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
                                'contentOptions' => ['class' => (!$columnsArr['c9']) ? 'hidden c9' : 'c9'],
                                'headerOptions' => ['class' => (!$columnsArr['c9']) ? 'hidden theadcolor c9' : 'theadcolor c9']
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
                                'contentOptions' => ['class' => (!$columnsArr['c10']) ? 'hidden c10' : 'c10'],
                                'headerOptions' => ['class' => (!$columnsArr['c10']) ? 'hidden theadcolor c10' : 'theadcolor c10']
                            ],
                            [
                                'attribute' => 'priority_type_id',
                                'header' => 'Priority',
                                'value' => function ($model) {
                                    return $model->priorityType->priority_name;
                                },
                                'format' => 'raw',
                                'contentOptions' => ['class' => (!$columnsArr['c11']) ? 'hidden c11' : 'c11'],
                                'headerOptions' => ['class' => (!$columnsArr['c11']) ? 'hidden theadcolor c11' : 'theadcolor c11']
                            ],
                            [
                                'attribute' => 'process_critical',
                                'header' => 'Process Critical (Audit)',
                                'contentOptions' => ['class' => (!$columnsArr['c12']) ? 'hidden c12' : 'c12'],
                                'headerOptions' => ['class' => (!$columnsArr['c12']) ? 'hidden theadcolor c12' : 'theadcolor c12'],
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
                                'contentOptions' => ['class' => (!$columnsArr['c13']) ? 'hidden c13' : 'c13'],
                                'headerOptions' => ['class' => (!$columnsArr['c13']) ? 'hidden theadcolor c13' : 'theadcolor c13']
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'header' => 'Actions',
                                'headerOptions' => ['class' => 'theadcolor'],
                                'template' => $buttons,
                                'buttons' => [
                                    'delete' => function ($url, $model) {
                                        return !(in_array($model->status, [5])) ? '-' : '<a href="javascript:void(0)" title="Delete" class="delete_ticket_btn" data-token =' . yii::$app->utils->encryptData($model->ticket_id) . '><i class="fa fa-trash-o" title="Delete"></i></a>';
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
    <?php ActiveForm::begin(['id' => 'delete_tickets_form', 'action' => yii::$app->urlManager->createUrl('tickets/delete'), 'method' => 'post',]) ?>
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
                        Are you sure you want to delete this ticket? You can't undo this action.
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
    <?php ActiveForm::begin(['id' => 'cancel_auditschedule_form', 'action' => yii::$app->urlManager->createUrl('tickets/cancel'), 'method' => 'post',]) ?>
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
                        Are you sure you want to cancel this ticket? You can't undo this action.
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
