<?php
$this->breadcrumbs=array(
	'Tokens'=>array('index'),
	$model->getConnectionAsText(),
);

$this->menu=array(
	array('label'=>'List Connections', 'url'=>array('index')),
	array('label'=>'Create Connection', 'url'=>array('create')),
	array('label'=>'Delete Connection', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Connection', 'url'=>array('admin'), 'visible' => Yii::app()->user->checkAccess('admin')),
);
?>

<h1>Connection with <?php echo $model->getConnectionAsText(); ?></h1>

<?php
$this->widget('bootstrap.widgets.BootDetailView', array(
    'data'=>$model,
	'attributes'=>array(
		array(
			'name'  => 'userId',
			'value' => CHtml::encode($model->user->name),
		),
		array(
			'name'  => 'serviceType',
			'value' => CHtml::encode($model->getConnectionAsText()),
		),
	),
));

if ($model->serviceType == Token::CONNECTION_SOUNDCLOUD) {
	echo CHtml::button('Refresh', array('submit' => array('token/refreshSoundcloud', 'id' => $model->id)));
}
?>
