<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\ActiveForm;
use app\assets\AppAsset;
use yii\web\JqueryAsset;
use yii\web\View;
use yii\widgets\Pjax;
use app\models\AuditsSchedules;
/* @var $this yii\web\View */
/* @var $searchModel app\models\search\TasksSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tasks';
$this->params['breadcrumbs'][] = $this->title;
AppAsset::register($this);
View::registerJs("save_url = '". yii::$app->urlManager->createUrl('tasks/save-columns')."';",View::POS_HEAD);
View::registerCssFile(yii::$app->urlManager->createUrl('css/bootstrap-multiselect.css'));
View::registerJsFile(yii::$app->urlManager->createUrl('js/modelpopup.js'), ['depends' => JqueryAsset::className()]);
View::registerJsFile(yii::$app->urlManager->createUrl('js/bootstrap-multiselect.js'), [
    'depends' => JqueryAsset::className()
]);
$this->registerJs('
$(".nav-bids").removeClass("active");
$("#tasks").addClass("active");
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
$this->registerJs('
$(".nav-bids").removeClass("active");
$("#MenuTasks").addClass("active");
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
<?php echo $this->render('_search', ['model' => $searchModel]); ?>

<style>
.columnsFilter{
    margin: 20px 0px;
}
</style>
<?php
$gridColumnsInfo = [
    [
        'attribute' => 'task_id',
        'header' => 'Scheduled Task ID	',
        'visible'=>(!$columnsArr['c1']) ? false :true
    ],
    [
        'attribute' => 'location_id',
        'header' => 'location',
        'value' => function ($model){
            return $model->getUserLocationsData();
        },
        'visible'=>(!$columnsArr['c14']) ? false :true
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
        'attribute' => 'checklist_id',
        'header' => 'Checklist',
        'value' => function ($model) {
            return ($model->checklist_id) ? $model->checklist->checklist_id : '--';
        },
        'format' => 'raw',
        'visible'=>(!$columnsArr['c5']) ? false :true
    ],

    
    [
        'attribute' => 'frequency',
        'header' => 'frequency',
        'value' => function ($model) {
            return ($model->frequency);
        },
        'visible'=>(!$columnsArr['c6']) ? false :true

    ],
  
    [
        'attribute' => 'start_date',
        'header' => 'Start Date',
        'value' => function ($model) {
            $timestamp = strtotime($model->start_date);
            return Yii::$app->formatter->asDate($timestamp, 'php:d-m-Y');
        },
        'visible'=>(!$columnsArr['c8']) ? false :true

    ],
    [
        'attribute' => 'end_date',
        'header' => 'End Date',
        'value' => function ($model) {
            $timestamp = strtotime($model->end_date);
            return Yii::$app->formatter->asDate($timestamp, 'php:d-m-Y');
        },
        'visible'=>(!$columnsArr['c9']) ? false :true

    ],

    [
        'attribute' => 'back_up_user',
        'header' => 'Backup User',
        'value' => function ($model) {
            return ($model->back_up_user);
        },
        'visible'=>(!$columnsArr['c11']) ? false :true

    ],
    [
        'attribute' => 'taskdoer_id',
        'header' => 'TaskDoer ID',
        'value' => function ($model) {
            return ($model->taskdoer_id);
        },
        'visible'=>(!$columnsArr['c10']) ? false :true

    ],
   
];

$archivedTickets =

    [[
        'attribute' => 'task_id',
        'header' => 'Scheduled Task ID	',
        'visible'=>(!$columnsArr['c1']) ? false :true
    ],
    [
        'attribute' => 'location_id',
        'header' => 'location',
        'value' => function ($model) {
            return $model->getUserLocationsData();
        },
        'visible'=>(!$columnsArr['c14']) ? false :true
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
        'attribute' => 'checklist_id',
        'header' => 'Checklist',
        'value' => function ($model) {
            return ($model->checklist_id) ? $model->checklist->checklist_id : '--';
        },
        'format' => 'raw',
        'visible'=>(!$columnsArr['c5']) ? false :true
    ],

    
    [
        'attribute' => 'frequency',
        'header' => 'frequency',
        'value' => function ($model) {
            return ($model->frequency);
        },
        'visible'=>(!$columnsArr['c6']) ? false :true

    ],

    [
        'attribute' => 'start_date',
        'header' => 'Start Date',
        'value' => function ($model) {
            $timestamp = strtotime($model->start_date);
            return Yii::$app->formatter->asDate($timestamp, 'php:d-m-Y');
        },
        'visible'=>(!$columnsArr['c8']) ? false :true

    ],
    [
        'attribute' => 'end_date',
        'header' => 'End Date',
        'value' => function ($model) {
            $timestamp = strtotime($model->end_date);
            return Yii::$app->formatter->asDate($timestamp, 'php:d-m-Y');
        },
        'visible'=>(!$columnsArr['c9']) ? false :true

    ],

    [
        'attribute' => 'back_up_user',
        'header' => 'Backup User',
        'value' => function ($model) {
            return ($model->back_up_user);
        },
        'visible'=>(!$columnsArr['c11']) ? false :true

    ],
    [
        'attribute' => 'taskdoer_id',
        'header' => 'TaskDoer ID',
        'value' => function ($model) {
            return ($model->taskdoer_id);
        },
        'visible'=>(!$columnsArr['c10']) ? false :true

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
<td><button class="btn btn-success" id="submitGridSelectionBtn" data-type="tasks" style="margin-left: 15px;">Save</button></td>
</tr>
</table>
<div class="row">
    <div class="col-lg-12 nopadding">
        <ul class="nav nav-tabs">
            <li class="active">
                <a href="#" class="activetickets tabs active" data-toggle="tab" name="tab" data-index="0"><i
                            class="fa fa-folder-open" aria-hidden="true" aria-expanded="true"></i>&nbsp;Active
                    Tasks</a>
            </li>
            <li>
                <a href="#" class="archivedtickets tabs" data-toggle="tab" name="tab" data-index="1"><i
                            class="fa fa-archive" aria-hidden="true" aria-expanded="false"></i>&nbsp;Archived
                    Tasks</a>
            </li>
            <?php if (Yii::$app->authManager->checkPermissionAccess('tasks/create')) { ?>
                <li class="pull-right">

                    <a href="<?= yii::$app->urlManager->createUrl('tasks/create'); ?>" class="btn btn-success"><i
                                class="fa fa-plus"></i>&nbsp;Create Tasks</a>
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
                            'label' => 'Active Task',
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
                    'dataProvider' => $dataScheduledProvider,
                    'columns' => $gridColumnsInfo]) ?>
                </span>
            </li>
        </ul>
    </div>
    <div class="row ticketid">
    <div class="tab-content">
        <div id="activeaudits" class="tab-pane fade in active">
            <div class="col-sm-12 nopadding">
                <div class="tickets-index">
                    <?php Pjax::begin(); ?>
                    <?=
                    GridView::widget([
                        'dataProvider' => $dataScheduledProvider,
                        'layout' => '{items}{pager}',
                        'emptyText' => 'No Active Task found. Please Check \'Archived Tickets\' for closed tasks.',
                        //'filterModel' => $searchModel,
                        'columns' => [
                            
                            [
                                'attribute' => 'task_id',
                                'header' => 'Scheduled Task ID	',
                                'value' => function ($model) {
                                   return '<a href=' . yii::$app->urlManager->createUrl('task/reports?id=' . Yii::$app->utils->encryptData($model->task_id)) . ' title="View" target="_blank">' . $model->task_id . '</a>';
                                },
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'theadcolor'],
                                'contentOptions' => ['class' => (!$columnsArr['c1']) ? 'hidden c1 tbl-td' : 'c1 tbl-td'],
                                'headerOptions' => ['class' => (!$columnsArr['c1']) ? 'hidden theadcolor c1 tbl-td' : 'theadcolor c1 tbl-td']
                            ],
                            [
                                'attribute' => 'location_id',
                                'header' => ' Location ',
                                'value' => function ($model) {
                                    return $model->getUserLocationsData();

                                },
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'theadcolor'],
                                'contentOptions' => ['class' => (!$columnsArr['c14']) ? 'hidden c14 tbl-td' : 'c14 tbl-td'],
                                'headerOptions' => ['class' => (!$columnsArr['c14']) ? 'hidden theadcolor c14 tbl-td' : 'theadcolor c14 tbl-td']
                            ],
                            [
                                'attribute' => 'hotel_id',
                                'header' => ' Office ',
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
                                'header' => ' Floor ',
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
                                'header' => ' Cabin ',
                                'value' => function ($model) {
                                    return ($model->checklist_id) ? $model->checklist->checklist_id : '--';
                                },
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'theadcolor'],
                                'contentOptions' => ['class' => (!$columnsArr['c5']) ? 'hidden c5 tbl-td' : 'c5 tbl-td'],
                                'headerOptions' => ['class' => (!$columnsArr['c5']) ? 'hidden theadcolor c5 tbl-td' : 'theadcolor c5 tbl-td']
                            ],
                            [
                                'attribute' => 'frequency',
                                'header' => ' Frequency ',
                                'value' => function ($model) {
                            
                                    return ($model->frequency);
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
                                'attribute' => 'start_date',
                                'header' => ' Start Date ',
                                'value' => function ($model) {
                                    $timestamp = strtotime($model->start_date);
                                    return Yii::$app->formatter->asDate($timestamp, 'php:d-m-Y');
                                },
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'theadcolor'],
                                'contentOptions' => ['class' => (!$columnsArr['c8']) ? 'hidden c8 tbl-td' : 'c8 tbl-td'],
                                'headerOptions' => ['class' => (!$columnsArr['c8']) ? 'hidden theadcolor c8 tbl-td' : 'theadcolor c8 tbl-td']
                            ],
                            [
                                'attribute' => 'end_date',
                                'header' => ' End Date ',
                                'value' => function ($model) {
                                    $timestamp = strtotime($model->end_date);
                                    return Yii::$app->formatter->asDate($timestamp, 'php:d-m-Y');
                                },
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'theadcolor'],
                                'contentOptions' => ['class' => (!$columnsArr['c9']) ? 'hidden c9 tbl-td' : 'c9 tbl-td'],
                                'headerOptions' => ['class' => (!$columnsArr['c9']) ? 'hidden theadcolor c9 tbl-td' : 'theadcolor c9 tbl-td']
                            ],
                            [
                                'attribute' => 'taskdoer_id',
                                'header' => 'TaskDoer ID',
                                'value' => function ($model) {
                                    return ($model->taskdoer_id);

                                    },

                                'format' => 'raw',
                                'headerOptions' => ['class' => 'theadcolor'],
                                'contentOptions' => ['class' => (!$columnsArr['c10']) ? 'hidden c10 tbl-td' : 'c10 tbl-td'],
                                'headerOptions' => ['class' => (!$columnsArr['c10']) ? 'hidden theadcolor c10 tbl-td' : 'theadcolor c10 tbl-td']
                            ],
                            [
                                'attribute' => 'back_up_user',
                                'header' => ' Back_up_user ',
                                'value' => function ($model) {
                                    return ($model->back_up_user);
                                },
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'theadcolor'],
                                'contentOptions' => ['class' => (!$columnsArr['c11']) ? 'hidden c11 tbl-td' : 'c11 tbl-td'],
                                'headerOptions' => ['class' => (!$columnsArr['c11']) ? 'hidden theadcolor c11 tbl-td' : 'theadcolor c11 tbl-td']
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'header' => ' Actions ',
                                'headerOptions' => ['class' => 'theadcolor'],
                                'template' => $buttons,
                                'buttons' => [
                                    'update' => function ($url, $model) {
                                        return $model->status == 1 ? '--' : Html::a('<i class="fa fa-edit"></i>', ['tasks/update', 'id' => Yii::$app->utils->encryptData($model->user_id)], [
                                            'title' => Yii::t('yii', 'Edit'),
                                        ]);
                                    },
        
                                    'delete' => function ($url, $model) {
                                        return $model->status == 1 ? '--' : '<a href="javascript:void(0)" title="Delete" class="delete_user_btn" data-token =' . yii::$app->utils->encryptData($model->user_id) . '><i class="fa fa-trash-o" title="Delete"></i></a>';
                                    },
                                ]
        
                            ],
                          
                            ]
                        ]
                    );
                    ?>
                    <?php Pjax::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$buttons = '';


if (Yii::$app->authManager->checkPermissionAccess('tasks/delete')) {
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
                        //'filterModel' => $searchModel,'dataProvider' => $dataProvider,
                        'emptyText' => 'No Active Task found. Please Check \'Archived Tickets\' for closed tasks.',
                        //'filterModel' => $searchModel,
                        'columns' => [
                            [
                                'attribute' => 'task_id',
                                'header' => 'Scheduled Task ID	',
                                'value' => function ($model) {
                                    return '<a href=' . yii::$app->urlManager->createUrl('task/reports?id=' . Yii::$app->utils->encryptData($model->task_id)) . ' title="View" target="_blank">' . $model->task_id . '</a>';

                                },
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'theadcolor'],
                                'contentOptions' => ['class' => (!$columnsArr['c1']) ? 'hidden c1 tbl-td' : 'c1 tbl-td'],
                                'headerOptions' => ['class' => (!$columnsArr['c1']) ? 'hidden theadcolor c1 tbl-td' : 'theadcolor c1 tbl-td']
                            ],
                            [
                                'attribute' => 'location_id',
                                'header' => 'location',
                                'value' => function ($model){
                                    return $model->getUserLocationsData();
                                },
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'theadcolor'],
                                'contentOptions' => ['class' => (!$columnsArr['c14']) ? 'hidden c14 tbl-td' : 'c14 tbl-td'],
                                'headerOptions' => ['class' => (!$columnsArr['c14']) ? 'hidden theadcolor c14 tbl-td' : 'theadcolor c14 tbl-td']
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
                                    return ($model->checklist_id) ? $model->checklist->checklist_id : '--';
                                },
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'theadcolor'],
                                'contentOptions' => ['class' => (!$columnsArr['c5']) ? 'hidden c5 tbl-td' : 'c5 tbl-td'],
                                'headerOptions' => ['class' => (!$columnsArr['c5']) ? 'hidden theadcolor c5 tbl-td' : 'theadcolor c5 tbl-td']
                            ],
                            [
                                'attribute' => 'frequency',
                                'header' => 'Frequency',
                                'value' => function ($model) {
                                    return ($model->frequency);
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
                                'attribute' => 'start_date',
                                'header' => 'Start Date',
                                'value' => function ($model) {
                                    $timestamp = strtotime($model->start_date);
                                    return Yii::$app->formatter->asDate($timestamp, 'php:d-m-Y');
                                },
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'theadcolor'],
                                'contentOptions' => ['class' => (!$columnsArr['c8']) ? 'hidden c8 tbl-td' : 'c8 tbl-td'],
                                'headerOptions' => ['class' => (!$columnsArr['c8']) ? 'hidden theadcolor c8 tbl-td' : 'theadcolor c8 tbl-td']
                            ],
                            [
                                'attribute' => 'end_date',
                                'header' => 'End Date',
                                'value' => function ($model) {
                                    $timestamp = strtotime($model->end_date);
                                    return Yii::$app->formatter->asDate($timestamp, 'php:d-m-Y');
                                },
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'theadcolor'],
                                'contentOptions' => ['class' => (!$columnsArr['c9']) ? 'hidden c9 tbl-td' : 'c9 tbl-td'],
                                'headerOptions' => ['class' => (!$columnsArr['c9']) ? 'hidden theadcolor c9 tbl-td' : 'theadcolor c9 tbl-td']
                            ],
                            [
                                'attribute' => 'taskdoer_id',
                                'header' => 'TaskDoer ID',
                                'value' => function ($model) {
                                    return ($model->taskdoer_id);

                                    },

                                'format' => 'raw',
                                'headerOptions' => ['class' => 'theadcolor'],
                                'contentOptions' => ['class' => (!$columnsArr['c10']) ? 'hidden c10 tbl-td' : 'c10 tbl-td'],
                                'headerOptions' => ['class' => (!$columnsArr['c10']) ? 'hidden theadcolor c10 tbl-td' : 'theadcolor c10 tbl-td']
                            ],
                            [
                                'attribute' => 'back_up_user',
                                'header' => 'back_up_user',
                                'value' => function ($model) {
                                    return ($model->back_up_user);
                                },
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'theadcolor'],
                                'contentOptions' => ['class' => (!$columnsArr['c11']) ? 'hidden c11 tbl-td' : 'c11 tbl-td'],
                                'headerOptions' => ['class' => (!$columnsArr['c11']) ? 'hidden theadcolor c11 tbl-td' : 'theadcolor c11 tbl-td']
                            ],
                              [
                        'class' => 'yii\grid\ActionColumn',
                        'header' => 'Actions',
                        'headerOptions' => ['class' => 'theadcolor'],
                        'template' => $buttons,
                        'buttons' => [
                            'update' => function ($url, $model) {
                                return $model->status == 1 ? '--' : Html::a('<i class="fa fa-edit"></i>', ['user/update', 'id' => Yii::$app->utils->encryptData($model->user_id)], [
                                    'title' => Yii::t('yii', 'Edit'),
                                ]);
                            },

                            'delete' => function ($url, $model) {
                                return $model->status == 1 ? '--' : '<a href="javascript:void(0)" title="Delete" class="delete_user_btn" data-token =' . yii::$app->utils->encryptData($model->user_id) . '><i class="fa fa-trash-o" title="Delete"></i></a>';
                            },
                        ]

                    ]
                          
                            ]
                        ]
                    );
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
    <?php ActiveForm::begin(['id' => 'delete_tickets_form', 'action' => yii::$app->urlManager->createUrl('tasks/delete'), 'method' => 'post',]) ?>
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
    <?php ActiveForm::begin(['id' => 'cancel_auditschedule_form', 'action' => yii::$app->urlManager->createUrl('tasks/cancel'), 'method' => 'post',]) ?>
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
