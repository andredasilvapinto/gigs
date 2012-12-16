<div class="gigView">
	
	<div class="gigDate">

		<div class="gigWeekDay">
			<?php echo CHtml::encode(Yii::app()->dateFormatter->format('ccc', $data->dateTime)); ?>
		</div>

		<div class="gigDay">
			<?php echo CHtml::encode(Yii::app()->dateFormatter->format('d', $data->dateTime)); ?>
		</div>
		
		<div class="gigMonth">
			<?php echo CHtml::encode(Yii::app()->dateFormatter->format('LLL', $data->dateTime)); ?>
		</div>
		
	</div>
	
	<div class="gigDesc">
		
		<div class="gigTitle">
			<?php echo CHtml::link(CHtml::encode(Utils::trimString($data->title, 40)), array('viewGigWidget', 'id'=>$data->id)); ?>
		</div>
		
		<div class="gigVenueAndLocation">
			<?php
				$venue = Utils::trimString($data->venue, 25);
				$location = Utils::trimString($data->location, 50);

				echo CHtml::encode($venue) . ' - ' . CHtml::encode($location);
			?>
		</div>

		<div class="gigDescription">
			<?php echo CHtml::encode(Utils::trimString($data->description, 75)); ?>
		</div>
		
	</div>
	
</div>

<div style="clear: both"></div>