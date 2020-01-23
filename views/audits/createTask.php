<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Audits */

$this->title = 'Create Tasks';
$this->params['breadcrumbs'][] = ['label' => 'Tasks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="audits-create">
 <?= $this->render('_formTask', [
     'model' => $model,
     'auditLocationsModel' => $auditLocationsModel,
     'auditsSchedulesModel' => $auditsSchedulesModel,
     
    ]) ?>

</div>
