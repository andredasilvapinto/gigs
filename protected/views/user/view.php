<?php
$this->breadcrumbs=array(
	'Users'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List User', 'url'=>array('index')),
	array('label'=>'Create User', 'url'=>array('create'), 'visible' => Yii::app()->user->isGuest),
	array('label'=>'Update User', 'url'=>array('update', 'id'=>$model->id), 'visible' => Yii::app()->user->id == $model->id),
	array('label'=>'Delete User', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?'), 'visible' => Yii::app()->user->id == $model->id),
	array('label'=>'Manage User', 'url'=>array('admin'), 'visible' => Yii::app()->user->checkAccess('admin')),
);
?>

<h1>User <?php echo $model->name; ?></h1>

<?php
$this->widget('bootstrap.widgets.BootDetailView', array(
    'data'=>$model,
	'attributes'=>array(
		'name',
		'artistName',
		array(
			'name'=>'imageURL',
			'type'=>'html',
			'value'=>html_entity_decode(CHtml::image($model->imageURL,'User image',array('width'=>150,'height'=>150))),
		),
		'biography',
		'bookingContact',
	),
)); 
?>
