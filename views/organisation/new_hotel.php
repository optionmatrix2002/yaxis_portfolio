<?php
?>
<style>
    .closebutton{
        font-size:23px;
        float:right;
    }
    </style>
<div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="closebutton" data-dismiss="modal">&times;</button>
            <h3 class="modal-title organisation_setup_title"><i class="fa fa-building-o"></i> <?= $hotelModel->isNewRecord ? 'Add' :'Edit'; ?> Office</h3>
        </div>
        <div class="modal-body">
            <?=$this->render("hotel_form",['hotelModel'=>$hotelModel,"locationsModel"=>$locationsModel]); ?>
        </div>
    </div>
</div>
