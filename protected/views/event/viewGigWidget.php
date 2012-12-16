<?php

$this->layout = 'widgetLayout';

?>

<div class="gigView">

	<div class = "gigSingleTitle">
		<?php echo CHtml::encode($model->title); ?>
	</div>
	
	<div class = "gigAuthor">
		<?php echo 'in ' . CHtml::link(CHtml::encode($model->user->name) . "'s agenda", array('listWidget', 'userId'=>$model->userId)); ?>
	</div>

	<div class="gigFullDate">
		<?php
			echo CHtml::encode(
				Yii::app()->dateFormatter->format('EEEE, d MMMM yyyy', $model->dateTime) .
				' at ' . Yii::app()->dateFormatter->format('HH:mm', $model->dateTime)
			);
		?>
	</div>

	<div class="gigVenueAndLocation">
		<?php 
			echo CHtml::encode($model->venue) . '<br />';
			echo CHtml::encode($model->location) . ' (' . CHtml::link('map', 'http://maps.google.com/maps?q=' . urlencode($model->location)) . ')';
		?>
	</div>

	<div class="gigSingleDescription">
		<?php echo CHtml::encode($model->description); ?>
	</div>
	
</div>