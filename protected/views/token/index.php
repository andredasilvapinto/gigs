<?php
$this->breadcrumbs=array(
	'Tokens',
);

$this->menu=array(
	array('label'=>'New Connection', 'url'=>array('create')),
	array('label'=>'Manage Connection', 'url'=>array('admin'), 'visible' => Yii::app()->user->checkAccess('admin')),
);
?>

<h1>Connections</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
