<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('serviceType')); ?>:</b>
	<?php
		echo CHtml::link(CHtml::encode($data->getConnectionAsText()), array('view', 'id'=>$data->id));
		
		if ($data->serviceType == Token::CONNECTION_SOUNDCLOUD) {
			echo ' ' . CHtml::button('Refresh', array('submit' => array('token/refreshSoundcloud', 'id' => $data->id)));
		}
	?>

</div>