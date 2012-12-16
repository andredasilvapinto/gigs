<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->name), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('artistName')); ?>:</b>
	<?php echo CHtml::encode($data->artistName); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('imageURL')); ?>:</b>
	<?php echo html_entity_decode(CHtml::image($data->imageURL,'User image',array('width'=>150,'height'=>150))); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('biography')); ?>:</b>
	<?php echo CHtml::encode($data->biography); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('bookingContact')); ?>:</b>
	<?php echo CHtml::encode($data->bookingContact); ?>
	<br />

</div>