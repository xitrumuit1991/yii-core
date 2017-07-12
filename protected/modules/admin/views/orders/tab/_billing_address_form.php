<?php
/**
 * Created by PhpStorm.
 * User: Phuong Ho
 * Date: 10/28/14
 * Time: 9:30 AM
 */
?>

<div class="title-1 clearfix">
    <h1>BILLING ADDRESS</h1>
</div>

<div class="form-group form-group-sm">
    <?php echo $form->labelEx($model, 'contact_first_name', array('class' => 'col-sm-1 control-label')); ?>
    <div class="col-sm-3">
        <?php echo $form->textField($model, 'contact_first_name', array('class' => 'form-control', 'maxlength' => 255)) ?>
        <?php echo $form->error($model, 'contact_first_name') ?>
    </div>
</div>
<div class="form-group form-group-sm">
    <?php echo $form->labelEx($model, 'contact_last_name', array('class' => 'col-sm-1 control-label')); ?>
    <div class="col-sm-3">
        <?php echo $form->textField($model, 'contact_last_name', array('class' => 'form-control', 'maxlength' => 255)) ?>
        <?php echo $form->error($model, 'contact_last_name') ?>
    </div>
</div>


<div class="form-group form-group-sm">
    <?php echo $form->labelEx($model, 'email', array('class' => 'col-sm-1 control-label')); ?>
    <div class="col-sm-3">
        <?php
        if (isset($_GET['email'])) {
            $e_email = base64_decode($_GET['email']);
            echo $form->textField($model, 'email', array('class' => 'form-control', 'maxlength' => 255, 'readonly' => 'readonly', 'value' => $e_email));
        } else {
            echo $form->textField($model, 'email', array('class' => 'form-control', 'maxlength' => 255, 'readonly' => 'readonly'));
        }
        ?>
        <?php echo $form->error($model, 'email') ?>
    </div>
</div>


<div class="form-group form-group-sm">
    <?php echo $form->labelEx($model, 'phone', array('class' => 'col-sm-1 control-label')); ?>
    <div class="col-sm-3">
        <?php echo $form->textField($model, 'phone', array('class' => 'form-control', 'maxlength' => 255)) ?>
        <?php echo $form->error($model, 'phone') ?>
    </div>
</div>


<div class="form-group form-group-sm">
    <?php echo $form->labelEx($model, 'address1', array('class' => 'col-sm-1 control-label')); ?>
    <div class="col-sm-3">
        <?php echo $form->textField($model, 'address1', array('class' => 'form-control', 'maxlength' => 255)) ?>
        <?php echo $form->error($model, 'address1') ?>
    </div>
</div>

<div class="form-group form-group-sm">
    <?php echo $form->labelEx($model, 'address2', array('class' => 'col-sm-1 control-label')); ?>
    <div class="col-sm-3">
        <?php echo $form->textField($model, 'address2', array('class' => 'form-control', 'maxlength' => 255)) ?>
        <?php echo $form->error($model, 'address2') ?>
    </div>
</div>


<div class="form-group form-group-sm">
    <?php echo $form->labelEx($model, 'postal_code', array('class' => 'col-sm-1 control-label')); ?>
    <div class="col-sm-3">
        <?php echo $form->textField($model, 'postal_code', array('class' => 'form-control', 'maxlength' => 255)) ?>
        <?php echo $form->error($model, 'postal_code') ?>
    </div>
</div>

<div class="form-group form-group-sm">
    <?php echo $form->labelEx($model, 'area_code_id', array('class' => 'col-sm-1 control-label')); ?>
    <div class="col-sm-3">
        <?php
        echo $form->dropDownList($model, 'area_code_id', Countries::getDropdownlistWithTableName(), array('class' => 'form-control', 'empty' => '---Select country--'))
        ?>
        <?php echo $form->error($model, 'area_code_id') ?>
    </div>
</div>

<!--<div class="form-group form-group-sm">
    <label class="col-sm-1 control-label">Ship to This Address</label>
    <div class="col-sm-3">
        <input type="checkbox" id="shiptothisaddress" onclick="shiptothisaddress11()" /> 
    </div>
</div>-->



