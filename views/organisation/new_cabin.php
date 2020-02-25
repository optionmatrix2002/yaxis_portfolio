<?php
?>
<div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h3 class="modal-title organisation_setup_title"><i class="fa fa-users"></i> <?=$cabinsModel->isNewRecord ? 'Add':'Edit'; ?> Cabin</h3>
            <h5 class="text-muted organisation_setup_title">
                 <?= $hotelDepartmentModel->hotel->location->locationCity->name ?>  <i class="fa fa-angle-double-right text-primary" aria-hidden="true"></i>  <?= $hotelDepartmentModel->hotel->hotel_name?>  <i class="fa fa-angle-double-right text-primary" aria-hidden="true"></i>  
            </h5>
        </div>
        <div class="modal-body">
            <?php if($cabinsModel->isNewRecord){ ?>           
            <?=$this->render("cabin_form",['cabinsModel'=>$cabinsModel,"hotelDepartmentModel"=>$hotelDepartmentModel]); ?>
            <?php 
        
        } else{ ?>
            <?=$this->render("cabin_edit",['cabinsModel'=>$cabinsModel,"hotelDepartmentModel"=>$hotelDepartmentModel]); ?>
        <?php } ?>
        </div>
    </div>
</div>
