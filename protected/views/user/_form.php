<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>60)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'new_password'); ?>
		<?php echo $form->passwordField($model,'new_password',array('size'=>60,'maxlength'=>64)); ?>
		<?php echo $form->error($model,'new_password'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'new_password_repeat'); ?>
		<?php echo $form->passwordField($model,'new_password_repeat',array('size'=>60,'maxlength'=>64)); ?>
		<?php echo $form->error($model,'new_password_repeat'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'artistName'); ?>
		<?php echo $form->textField($model,'artistName',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'artistName'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'imageURL'); ?>
		<?php echo $form->textField($model,'imageURL',array('size'=>60,'maxlength'=>2083)); ?>
		<?php echo $form->error($model,'imageURL'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'biography'); ?>
		<?php echo $form->textArea($model,'biography',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'biography'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'bookingContact'); ?>
		<?php echo $form->textField($model,'bookingContact',array('size'=>60,'maxlength'=>2083)); ?>
		<?php echo $form->error($model,'bookingContact'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->