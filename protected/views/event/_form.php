<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'event-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
	
	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textArea($model,'title',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'shortTitle'); ?>
		<?php echo $form->textField($model,'shortTitle',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'shortTitle'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'venue'); ?>
		<?php echo $form->textArea($model,'venue',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'venue'); ?>
	</div>

	<div class="row">
        <?php echo $form->labelEx($model,'dateTime'); ?>
		<?php Yii::import('application.extensions.timepicker.timepicker');
		$this->widget('timepicker', array(
			'model'=>$model,
			'name'=>'dateTime',
		));
		?>
        <?php echo $form->error($model,'dateTime'); ?>
    </div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php echo $form->dropDownList($model,'status', $model->getStatusTypes()); ?>
		<?php echo $form->error($model,'venue'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'location'); ?>
		<?php echo $form->textArea($model,'location',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'location'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->