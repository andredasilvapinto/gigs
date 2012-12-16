<?php
$this->breadcrumbs=array(
	'Events'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Event', 'url'=>array('index')),
	array('label'=>'Manage Event', 'url'=>array('admin')),
);
?>

<h1>Create Event</h1>

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
			'options'=>array('timeFormat'=>'hh:mm'),
		));
		?>
        <?php echo $form->error($model,'dateTime'); ?>
    </div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php echo $form->dropDownList($model,'status', $model->getStatusTypes(),
				array('options' => array(
					Event::STATUS_CONFIRMED => array('selected' => true),
					'1' => array('selected' => false) //removing the default selected tag (always 1?)
				))); ?>
		<?php echo $form->error($model,'venue'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'location'); ?>
		<?php echo $form->textArea($model,'location',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'location'); ?>
	</div>

<!--	<div class="row">
		<?php echo $form->labelEx($model,'latitude'); ?>
		<?php echo $form->textField($model,'latitude',array('size'=>18,'maxlength'=>18)); ?>
		<?php echo $form->error($model,'latitude'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'longitude'); ?>
		<?php echo $form->textField($model,'longitude',array('size'=>18,'maxlength'=>18)); ?>
		<?php echo $form->error($model,'longitude'); ?>
	</div>-->

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