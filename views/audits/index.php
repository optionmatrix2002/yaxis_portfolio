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

$this->title = 'Audits';
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
$("#MenuAudits").addClass("active");
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
<?php
$gridColumnsInfo = [
    [
        'attribute' => 'ticket_name',
        'header' => 'Scheduled Audit ID',
        'visible'=>(!$columnsArr['c1']) ? false :true
    ],
    [
        'attribute' => 'Auditor',
        'header' => 'Auditor',
        'visible'=>(!$columnsArr['c10']) ? false :true
    ],
    [
        'attribute' => 'location_id',
        'header' => 'Location',
        'value' => function ($model) {
            return $model->location->locationCity->name;
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
        'header' => 'CheckList',
        'value' => function ($model) {
            return $model->getTicketCabinData();
        },
        'format' => 'raw',
        'visible'=>(!$columnsArr['c5']) ? false :true
    ],
    [
        'attribute' => 'subject',
        'header' => 'Status',
        'value' => function ($model) {
            return strip_tags($model->subject);
        },
        'visible'=>(!$columnsArr['c6']) ? false :true

    ],
    [
        'attribute' => 'assigned_id',
        'header' => 'Start Date',
        'value' => function ($model) {
            return ucfirst($model->assignedUser->first_name) . ' ' . ucfirst($model->assignedUser->last_name);
        },
        'visible'=>(!$columnsArr['c7']) ? false :true

    ],
    [
        'attribute' => 'created_at',
        'header' => 'End Date',
        'value' => function ($model) {
            $timestamp = strtotime($model->created_at);
            return Yii::$app->formatter->asDate($timestamp, 'php:d-m-Y');
        },
        'visible'=>(!$columnsArr['c8']) ? false :true

    ],
    [
        'attribute' => 'due_date',
        'header' => 'Submission Date',
        'value' => function ($model) {
            $timestamp = strtotime($model->due_date);
            return Yii::$app->formatter->asDate($timestamp, 'php:d-m-Y');
        },
        'visible'=>(!$columnsArr['c9']) ? false :true

    ]

];
?>
<div class="container-fluid">
    <h2>Manage Audits</h2>
</div>
<!-- notification text -->
<div class="wa-notification wa-notification-alt">
    <span class="wa-iconBoxed">
        <span class="fa fa-file-text-o header-icon-fontcolor"></span>
    </span>
    <p id="description-text">
        Audits for departments across offices can be scheduled and managed here.

    </p>
</div>
<!-- -------------------------Start Search here------------------------- -->
<?php echo $this->render('_search', ['model' => $searchModel]); ?>

<div class="row">
    <div class="col-lg-12 nopadding">
        <ul class="nav nav-tabs">
            <li  class="active">
                <a href="#" class="archivedaudits tabs" data-toggle="tab" name="tab" data-index="1"><i
                        class="fa fa-archive" aria-hidden="true" aria-expanded="false"></i>&nbsp;Completed Audits</a>
            </li>
            <li>
                <a href="#" class="activeaudits tabs active" data-toggle="tab" name="tab" data-index="0"><i
                        class="fa fa-folder-open" aria-hidden="true" aria-expanded="true"></i>&nbsp;Scheduled
                    Audits</a>
            </li>

            <?php
            if (Yii::$app->authManager->checkPermissionAccess('audits/create')) {
                ?>
                <li class="pull-right">
                    <a href="<?= yii::$app->urlManager->createUrl('audits/create'); ?>" class="btn btn-success"><i
                            class="fa fa-plus"></i>&nbsp;Schedule Audit</a>
                </li>
            <?php } ?>
            <li class="pull-right">
            <a href="javascript:void(0)" class="btn btn-success"><i
                            class="fa fa-download icon-white"></i>&nbsp;Completed Audits</a>
            </li>
            <li class="pull-right">
            <a href="javascript:void(0)" class="btn btn-success"><i
                            class="fa fa-download icon-white"></i>&nbsp;Scheduled Audits</a>
            </li>
        </ul>
    </div>
</div>
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
<td><button class="btn btn-success" id="submitGridSelectionBtn" data-type="audits" style="margin-left: 15px;">Save</button></td>
</tr>
</table>
<div class="row schedule_auditid" >
    <div class="tab-content">
        <div id="activeaudits" class="tab-pane fade in active">
            <div class="col-sm-12 nopadding">
                <div class="audits-index">
                    <?php Pjax::begin(); ?>
                    <?=
                    GridView::widget([
                        'dataProvider' => $dataProviderAuditsSchedules,
                        'layout' => '{items}{pager}',
                        'emptyText' => 'No Completed Audits found. Please Check Scheduled Audits.',
                        //'filterModel' => $searchModel,
                        'columns' => [
                            [
                                'attribute' => 'audit_schedule_name',
                                'format' => 'raw',
                                'header' => 'Scheduled Audit ID',
                                'headerOptions' => ['class' => 'theadcolor'],
                                'contentOptions' => ['class' => (!$columnsArr['c1']) ? 'hidden c1 tbl-td' : 'c1 tbl-td'],
                                'headerOptions' => ['class' => (!$columnsArr['c1']) ? 'hidden theadcolor c1 tbl-td' : 'theadcolor c1 tbl-td'],
                                'value' => function ($model) {
                                    return '<a  data-pjax="0" target="_blank" href="' . yii::$app->urlManager->createUrl('audits/reports?id=' . Yii::$app->utils->encryptData($model->audit_schedule_id)) . '" title="View Scheduled Audit">' . $model->audit_schedule_name . '</a>';
                                }
                            ],
                            [
                                'attribute' => 'auditor',
                                'format' => 'raw',
                                'header' => 'Auditor',
                                'headerOptions' => ['class' => 'theadcolor'],
                                'contentOptions' => ['class' => (!$columnsArr['c10']) ? 'hidden c10 tbl-td' : 'c10 tbl-td'],
                                'headerOptions' => ['class' => (!$columnsArr['c10']) ? 'hidden theadcolor c10 tbl-td' : 'theadcolor c10 tbl-td'],
                                'value' => function ($model) {
                                    return $model->auditor->first_name . " " . $model->auditor->last_name;
                                }
                            ],
                                [
                                    'attribute' => 'audit.location_id',
                                    'format' => 'raw',
                                    'header' => 'Location',
                                    'headerOptions' => ['class' => 'theadcolor'],
                                    'contentOptions' => ['class' => (!$columnsArr['c2']) ? 'hidden c2 tbl-td' : 'c2 tbl-td'],
                                    'headerOptions' => ['class' => (!$columnsArr['c2']) ? 'hidden theadcolor c2 tbl-td' : 'theadcolor c2 tbl-td'],
                                    'value' => function ($model) {
                                        return $model->audit->location->locationCity->name;
                                    }
                                ],
                            [
                                'attribute' => 'audit.hotel_id',
                                'format' => 'raw',
                                'header' => 'Office',
                                'headerOptions' => ['class' => 'theadcolor'],
                                'contentOptions' => ['class' => (!$columnsArr['c3']) ? 'hidden c3 tbl-td' : 'c3 tbl-td'],
                                'headerOptions' => ['class' => (!$columnsArr['c3']) ? 'hidden theadcolor c3 tbl-td' : 'theadcolor c3 tbl-td'],
                                'value' => function ($model) {
                                    return $model->audit->hotel->hotel_name;
                                }
                            ],
                            [
                                'attribute' => 'audit.department_id',
                                'format' => 'raw',
                                'header' => 'Floor',
                                'headerOptions' => ['class' => 'theadcolor'],
                                'contentOptions' => ['class' => (!$columnsArr['c4']) ? 'hidden c4 tbl-td' : 'c4 tbl-td'],
                                'headerOptions' => ['class' => (!$columnsArr['c4']) ? 'hidden theadcolor c4 tbl-td' : 'theadcolor c4 tbl-td'],
                                'value' => function ($model) {
                                    return $model->audit->department->department_name;
                                }
                            ],
                            [
                                'attribute' => 'audit.checklist_id',
                                'format' => 'raw',
                                'header' => 'CheckList',
                                'headerOptions' => ['class' => 'theadcolor'],
                                'contentOptions' => ['class' => (!$columnsArr['c5']) ? 'hidden c1 tbl-td' : 'c5 tbl-td'],
                                'headerOptions' => ['class' => (!$columnsArr['c5']) ? 'hidden theadcolor c5 tbl-td' : 'theadcolor c5 tbl-td'],
                                'value' => function ($model) {
                                    return $model->audit->checklist->cl_name;
                                }
                            ],
                            [
                                'attribute' => 'status',
                                'format' => 'raw',
                                'header' => 'Status',
                                'headerOptions' => ['class' => 'theadcolor'],
                                'contentOptions' => ['class' => (!$columnsArr['c6']) ? 'hidden c6 tbl-td' : 'c6 tbl-td'],
                                'headerOptions' => ['class' => (!$columnsArr['c6']) ? 'hidden theadcolor c6 tbl-td' : 'theadcolor c6 tbl-td'],
                                'value' => function ($model) {
                                    $value = "";
                                    switch (intval($model->status)) {
                                        case 0:$value = 'Scheduled';
                                            break;
                                        case 1:$value = 'In Progress';
                                            break;
                                        case 2:$value = 'Draft';
                                            break;
                                        case 3:$value = 'Completed';
                                            break;
                                        case 4:$value = 'Cancelled';
                                            break;
                                    }
                                    return $value;
                                }
                            ],
                            [
                                'attribute' => 'start_date',
                                'format' => 'raw',
                                'header' => 'Start Date',
                                'headerOptions' => ['class' => 'theadcolor'],
                                'contentOptions' => ['class' => (!$columnsArr['c7']) ? 'hidden c7 tbl-td' : 'c7 tbl-td'],
                                'headerOptions' => ['class' => (!$columnsArr['c7']) ? 'hidden theadcolor c7 tbl-td' : 'theadcolor c7 tbl-td'],
                                'value' => function ($model) {
                                    $timestamp = strtotime($model->start_date);
                                    return Yii::$app->formatter->asDate($timestamp, 'php:d-m-Y');
                                }
                            ],
                            [
                                'attribute' => 'end_date',
                                'format' => 'raw',
                                'header' => 'End Date',
                                'headerOptions' => ['class' => 'theadcolor'],
                                'contentOptions' => ['class' => (!$columnsArr['c8']) ? 'hidden c8 tbl-td' : 'c8 tbl-td'],
                                'headerOptions' => ['class' => (!$columnsArr['c8']) ? 'hidden theadcolor c8 tbl-td' : 'theadcolor c8 tbl-td'],
                                'value' => function ($model) {
                                    $timestamp = strtotime($model->end_date);
                                    return Yii::$app->formatter->asDate($timestamp, 'php:d-m-Y');
                                }
                            ],
                            [
                                'attribute' => 'updated_at',
                                'format' => 'raw',
                                'header' => 'Submission&nbsp;Date',
                                'headerOptions' => ['class' => 'theadcolor'],
                                'contentOptions' => ['class' => (!$columnsArr['c9']) ? 'hidden c9 tbl-td' : 'c9 tbl-td'],
                                'headerOptions' => ['class' => (!$columnsArr['c9']) ? 'hidden theadcolor c9 tbl-td' : 'theadcolor c9 tbl-td'],
                                'value' => function ($model) {
                                    if ($model->status == 3) {
                                        $timestamp = strtotime($model->updated_at);
                                        return Yii::$app->formatter->asDate($timestamp, 'php:d-m-Y H:i:s');
                                    }
                                    return '---';
                                }
                            ],
                        /* [
                          'attribute' => 'audit_name',
                          'format' => 'raw',
                          'header' => 'Audit ID',
                          'headerOptions' => ['class' => 'theadcolor'],
                          'value' => function ($model) {
                          return '<a href=' . yii::$app->urlManager->createUrl('audits/view-audit?id=' . Yii::$app->utils->encryptData($model->audit_id)) . ' title="View" target="_blank">' . $model->audit_name . '</a>';
                          }
                          ],
                          [
                          'attribute' => 'location_id',
                          'format' => 'raw',
                          'header' => 'Location',
                          'headerOptions' => ['class' => 'theadcolor'],
                          'value' => function ($model) {
                          return $model->location->locationCity->name;
                          }
                          ],


                          [
                          'attribute' => 'start_date',
                          'format' => 'raw',
                          'header' => 'Start Date',
                          'headerOptions' => ['class' => 'theadcolor'],
                          'value' => function ($model) {
                          $timestamp = strtotime($model->start_date);
                          return Yii::$app->formatter->asDate($timestamp, 'php:d-m-Y');
                          }
                          ],
                          [
                          'attribute' => 'end_date',
                          'format' => 'raw',
                          'header' => 'End Date',
                          'headerOptions' => ['class' => 'theadcolor'],
                          'value' => function ($model) {
                          $timestamp = strtotime($model->end_date);
                          return Yii::$app->formatter->asDate($timestamp, 'php:d-m-Y');
                          }
                          ], */
                        /*  [
                          'class' => 'yii\grid\ActionColumn',
                          'header' => 'Actions',

                          'headerOptions' => ['class' => 'theadcolor'],
                          'template' => '{delete}',
                          'buttons' => [

                          'delete' => function ($url, $model)
                          {
                          return '<a href="javascript:void(0)" title="Delete" class="delete_audit_btn" data-token ='.yii::$app->utils->encryptData($model->audit_id).'><i class="fa fa-trash-o" title="Delete"></i></a>';

                          },


                          ]
                          ], */
                        ],
                    ]);
                    ?>
                    <?php Pjax::end(); ?>
                </div>

            </div>
        </div>
    </div>
</div>


<div class="row auditid" style="display:none">
    <div class="tab-content">
        <div id="activeaudits" class="tab-pane fade in active">
            <?php if (intval($searchModel->show_child) == 0) { ?>
                <div class="col-sm-12 nopadding">
                    <div class="audits-index">
                        <?php Pjax::begin(); ?>
                        <?php
                        $buttons = '';
                        if (Yii::$app->authManager->checkPermissionAccess('audits/update')) {
                            $buttons .= '{update}';
                        }
                        if (Yii::$app->authManager->checkPermissionAccess('audits/delete')) {
                            $buttons .= '&nbsp;{delete}';
                        }
                        ?>
                        <?=
                        GridView::widget([
                            'dataProvider' => $dataProviderAudits,
                            'layout' => '{items}{pager}',
                            'emptyText' => 'No Scheduled audits found. Please Check Completed Audits for past audits.',
                            //'filterModel' => $searchModel,
                            'columns' => [
                                [
                                    'attribute' => 'audit_schedule_name',
                                    'format' => 'raw',
                                    'header' => 'Scheduled Audit ID',
                                    'headerOptions' => ['class' => 'theadcolor'],
                                    'contentOptions' => ['class' => (!$columnsArr['c1']) ? 'hidden c1 tbl-td' : 'c1 tbl-td'],
                                    'headerOptions' => ['class' => (!$columnsArr['c1']) ? 'hidden theadcolor c1 tbl-td' : 'theadcolor c1 tbl-td'],
                                    'value' => function ($model) {
                                        return '<a  data-pjax="0" target="_blank" href="' . yii::$app->urlManager->createUrl('audits/reports?id=' . Yii::$app->utils->encryptData($model->audit_schedule_id)) . '" title="View Scheduled Audit">' . $model->audit_schedule_name . '</a>';
                                    }
                                ],
                                [
                                    'attribute' => 'auditor',
                                    'format' => 'raw',
                                    'header' => 'Auditor',
                                    'headerOptions' => ['class' => 'theadcolor'],
                                    'contentOptions' => ['class' => (!$columnsArr['c10']) ? 'hidden c10 tbl-td' : 'c10 tbl-td'],
                                    'headerOptions' => ['class' => (!$columnsArr['c10']) ? 'hidden theadcolor c10 tbl-td' : 'theadcolor c10 tbl-td'],
                                    'value' => function ($model) {
                                        return $model->auditor->first_name . " " . $model->auditor->last_name;
                                    }
                                ],
                                    [
                                        'attribute' => 'audit.location_id',
                                        'format' => 'raw',
                                        'header' => 'Location',
                                        'headerOptions' => ['class' => 'theadcolor'],
                                        'contentOptions' => ['class' => (!$columnsArr['c2']) ? 'hidden c2 tbl-td' : 'c2 tbl-td'],
                                        'headerOptions' => ['class' => (!$columnsArr['c2']) ? 'hidden theadcolor c2 tbl-td' : 'theadcolor c2 tbl-td'],
                                        'value' => function ($model) {
                                            return $model->audit->location->locationCity->name;
                                        }
                                    ],
                                [
                                    'attribute' => 'audit.hotel_id',
                                    'format' => 'raw',
                                    'header' => 'Office',
                                    'headerOptions' => ['class' => 'theadcolor'],
                                    'contentOptions' => ['class' => (!$columnsArr['c3']) ? 'hidden c3 tbl-td' : 'c3 tbl-td'],
                                    'headerOptions' => ['class' => (!$columnsArr['c3']) ? 'hidden theadcolor c3 tbl-td' : 'theadcolor c3 tbl-td'],
                                    'value' => function ($model) {
                                        return $model->audit->hotel->hotel_name;
                                    }
                                ],
                                [
                                    'attribute' => 'audit.department_id',
                                    'format' => 'raw',
                                    'header' => 'Floor',
                                    'headerOptions' => ['class' => 'theadcolor'],
                                    'contentOptions' => ['class' => (!$columnsArr['c4']) ? 'hidden c4 tbl-td' : 'c4 tbl-td'],
                                    'headerOptions' => ['class' => (!$columnsArr['c4']) ? 'hidden theadcolor c4 tbl-td' : 'theadcolor c4 tbl-td'],
                                    'value' => function ($model) {
                                        return $model->audit->department->department_name;
                                    }
                                ],
                                [
                                    'attribute' => 'audit.checklist_id',
                                    'format' => 'raw',
                                    'header' => 'CheckList',
                                    'headerOptions' => ['class' => 'theadcolor'],
                                    'contentOptions' => ['class' => (!$columnsArr['c5']) ? 'hidden c1 tbl-td' : 'c5 tbl-td'],
                                    'headerOptions' => ['class' => (!$columnsArr['c5']) ? 'hidden theadcolor c5 tbl-td' : 'theadcolor c5 tbl-td'],
                                    'value' => function ($model) {
                                        return $model->audit->checklist->cl_name;
                                    }
                                ],
                                [
                                    'attribute' => 'status',
                                    'format' => 'raw',
                                    'header' => 'Status',
                                    'headerOptions' => ['class' => 'theadcolor'],
                                    'contentOptions' => ['class' => (!$columnsArr['c6']) ? 'hidden c6 tbl-td' : 'c6 tbl-td'],
                                    'headerOptions' => ['class' => (!$columnsArr['c6']) ? 'hidden theadcolor c6 tbl-td' : 'theadcolor c6 tbl-td'],
                                    'value' => function ($model) {
                                        return $model->checklist->cl_name;
                                    }
                                ],
                                [
                                    'attribute' => 'start_date',
                                    'format' => 'raw',
                                    'header' => 'Start Date',
                                    'headerOptions' => ['class' => 'theadcolor'],
                                    'contentOptions' => ['class' => (!$columnsArr['c7']) ? 'hidden c7 tbl-td' : 'c7 tbl-td'],
                                    'headerOptions' => ['class' => (!$columnsArr['c7']) ? 'hidden theadcolor c7 tbl-td' : 'theadcolor c7 tbl-td'],
                                    'value' => function ($model) {
                                        $timestamp = strtotime($model->start_date);
                                        return Yii::$app->formatter->asDate($timestamp, 'php:d-m-Y');
                                    }
                                ],
                                [
                                    'attribute' => 'end_date',
                                    'format' => 'raw',
                                    'header' => 'End Date',
                                    'headerOptions' => ['class' => 'theadcolor'],
                                    'contentOptions' => ['class' => (!$columnsArr['c8']) ? 'hidden c8 tbl-td' : 'c8 tbl-td'],
                                    'headerOptions' => ['class' => (!$columnsArr['c8']) ? 'hidden theadcolor c8 tbl-td' : 'theadcolor c8 tbl-td'],
                                    'value' => function ($model) {
                                        $timestamp = strtotime($model->end_date);
                                        return Yii::$app->formatter->asDate($timestamp, 'php:d-m-Y');
                                    }
                                ],
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'header' => 'Actions',
                                    'contentOptions' => ['class' => (!$columnsArr['c9']) ? 'hidden c9 tbl-td' : 'c9 tbl-td'],
                                    'headerOptions' => ['class' => (!$columnsArr['c9']) ? 'hidden theadcolor c9 tbl-td' : 'theadcolor c9 tbl-td'],
                                    'headerOptions' => ['class' => 'theadcolor'],
                                    'template' => $buttons,
                                    'buttons' => [
                                        'update' => function ($url, $model) {
                                            return Html::a('<i class="fa fa-edit"></i>', ['audits/update', 'id' => Yii::$app->utils->encryptData($model->audit_id)], [
                                                        'title' => Yii::t('yii', 'Edit'),
                                            ]);
                                        },
                                        'delete' => function ($url, $model) {
                                            return '<a href="javascript:void(0)" title="Delete" class="delete_audit_info_btn" data-token =' . yii::$app->utils->encryptData($model->audit_id) . '><i class="fa fa-trash" title="Delete"></i></a>';
                                        },
                                    ]
                                ],
                            ],
                        ]);
                        ?>
                        <?php Pjax::end(); ?>
                    </div>
                </div>
            <?php } else if (intval($searchModel->show_child) == 1) { ?>

                <div class="col-sm-12 nopadding">
                    <div class="audits-index">
                        <?php Pjax::begin(); ?>
                        <?php
                        $buttons = '';
                        if (Yii::$app->authManager->checkPermissionAccess('audits/update')) {
                            $buttons .= '{update}';
                        }
                        if (Yii::$app->authManager->checkPermissionAccess('audits/delete')) {
                            $buttons .= '&nbsp;{delete}';
                        }
                        ?>
                        <?=
                        GridView::widget([
                            'dataProvider' => $dataProviderAuditsSchedulesChilds,
                            'layout' => '{items}{pager}',
                            'emptyText' => 'No Scheduled audits found. Please Check Completed Audits for past audits.',
                            //'filterModel' => $searchModel,
                            'columns' => [
                                [
                                    'attribute' => 'audit_schedule_name',
                                    'format' => 'raw',
                                    'header' => 'Scheduled Audit ID',
                                    'headerOptions' => ['class' => 'theadcolor'],
                                    'value' => function ($model) {
                                        return '<a  data-pjax="0" target="_blank" href="' . yii::$app->urlManager->createUrl('audits/reports?id=' . Yii::$app->utils->encryptData($model->audit_schedule_id)) . '" title="View Scheduled Audit">' . $model->audit_schedule_name . '</a>';
                                    }
                                ],
                                [
                                    'attribute' => 'auditer',
                                    'format' => 'raw',
                                    'header' => 'Auditor',
                                    'headerOptions' => ['class' => 'theadcolor'],
                                    'value' => function ($model) {
                                        return $model->auditor->first_name . " " . $model->auditor->last_name;
                                    }
                                ],
                                [
                                    'attribute' => 'location_id',
                                    'format' => 'raw',
                                    'header' => 'Location',
                                    'headerOptions' => ['class' => 'theadcolor'],
                                    'value' => function ($model) {
                                        return $model->audit->location->locationCity->name;
                                    }
                                ],
                                [
                                    'attribute' => 'hotel_id',
                                    'format' => 'raw',
                                    'header' => 'Office',
                                    'headerOptions' => ['class' => 'theadcolor'],
                                    'value' => function ($model) {
                                        return $model->audit->hotel->hotel_name;
                                    }
                                ],
                                [
                                    'attribute' => 'department_id',
                                    'format' => 'raw',
                                    'header' => 'Floor',
                                    'headerOptions' => ['class' => 'theadcolor'],
                                    'value' => function ($model) {
                                        return $model->audit->department->department_name;
                                    }
                                ],
                                [
                                    'attribute' => 'checklist_id',
                                    'format' => 'raw',
                                    'header' => 'CheckList',
                                    'headerOptions' => ['class' => 'theadcolor'],
                                    'value' => function ($model) {
                                        return $model->audit->checklist->cl_name;
                                    }
                                ],
                                [
                                    'attribute' => 'status',
                                    'format' => 'raw',
                                    'header' => 'Status',
                                    'headerOptions' => ['class' => 'theadcolor'],
                                    'value' => function ($model) {
                                        $value = "";
                                        switch (intval($model->status)) {
                                            case 0:$value = 'Scheduled';
                                                break;
                                            case 1:$value = 'In Progress';
                                                break;
                                            case 2:$value = 'Draft';
                                                break;
                                            case 3:$value = 'Completed';
                                                break;
                                            case 4:$value = 'Cancelled';
                                                break;
                                        }
                                        return $value;
                                    }
                                ],
                                [
                                    'attribute' => 'start_date',
                                    'format' => 'raw',
                                    'header' => 'Start Date',
                                    'headerOptions' => ['class' => 'theadcolor'],
                                    'value' => function ($model) {
                                        $timestamp = strtotime($model->start_date);
                                        return Yii::$app->formatter->asDate($timestamp, 'php:d-m-Y');
                                    }
                                ],
                                [
                                    'attribute' => 'end_date',
                                    'format' => 'raw',
                                    'header' => 'End Date',
                                    'headerOptions' => ['class' => 'theadcolor'],
                                    'value' => function ($model) {
                                        $timestamp = strtotime($model->end_date);
                                        return Yii::$app->formatter->asDate($timestamp, 'php:d-m-Y');
                                    }
                                ],
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'header' => 'Actions',
                                    'headerOptions' => ['class' => 'theadcolor'],
                                    'template' => $buttons,
                                    'buttons' => [
                                        'update' => function ($url, $model) {
                                            return Html::a('<i class="fa fa-edit"></i>', ['audits/update', 'id' => Yii::$app->utils->encryptData($model->audit_id)], [
                                                        'title' => Yii::t('yii', 'Edit'),
                                            ]);
                                        },
                                        'delete' => function ($url, $model) {
                                            return '<a href="javascript:void(0)" title="Delete" class="delete_audit_info_btn" data-token =' . yii::$app->utils->encryptData($model->audit_id) . '><i class="fa fa-trash" title="Delete"></i></a>';
                                        },
                                    ]
                                ],
                            ],
                        ]);
                        ?>
                        <?php Pjax::end(); ?>
                    </div>
                </div>
            <?php } ?>
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