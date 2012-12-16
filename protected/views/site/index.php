<?php $this->pageTitle=Yii::app()->name; ?>

<?php $this->beginWidget('bootstrap.widgets.BootHero', array(
    'heading'=>'Welcome to ' . Yii::app()->name,
));
?>

<p>The website that lets you push your event updates to all your social networks
from one unique place.</p>

<?php

echo '<p align="center">' . CHtml::link('Sign up', array('site/login'), array('class' => 'btn btn-primary btn-large')) . '</p>';

$this->endWidget();

?>