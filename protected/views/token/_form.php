<div class="form">

<?php

$cs = Yii::app()->getClientScript();
$htmlValue = Token::CONNECTION_HTML;
$jscode = <<<EOD
function htmlCodeEnabler(obj){
	if(obj=="{$htmlValue}"){
		document.getElementById('htmlCodeID').style.display="block"; 
		document.getElementById('tokenSubmit').style.display="none";
	}else{
		document.getElementById('htmlCodeID').style.display="none";
		document.getElementById('tokenSubmit').style.display="block";
	}
}   
EOD;
	
$cs->registerScript('htmlCodeEnabler', $jscode, CClientScript::POS_HEAD);
$form=$this->beginWidget('CActiveForm', array(
	'id'=>'token-form',
	'enableAjaxValidation'=>false,
));
?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
	
	<div class="row">
		<?php echo $form->labelEx($model,'serviceType'); ?>
		<?php echo $form->dropDownList($model,'serviceType', $model->getAvailableConnectionTypes(), array('onchange'=>'htmlCodeEnabler(this.value)')); ?>
		<?php echo $form->error($model,'serviceType'); ?>
	</div>
    
    <div id="htmlCodeID" style="display:none">
		<div class="row">
			Just paste the following HTML code (or equivalent) in the desired place on your page:<br /><br />
			<code>
		<?php 
		$absUrl = Yii::app()->createAbsoluteUrl('event/listWidget', array('userId' => Yii::app()->user->id));
		$html = '<iframe width="100%" height="500" frameBorder="0" src="' . $absUrl . '">Browser not supported</iframe>';
			
			echo CHtml::encode($html);
		?>
			</code>
		</div>    
    </div>

	<div id="tokenSubmit" class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->