<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Tickets */

$this->title = 'Create Incident';
$this->params['breadcrumbs'][] = ['label' => 'Incidents', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tickets-create">

    <?= $this->render('_form', [
        'model' => $model,
        'modelTicketAttachment'=>$modelTicketAttachment,
    ]) ?>

</div>
