<?php
$this->breadcrumbs=array(
	'Events'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'List Event', 'url'=>array('index')),
	array('label'=>'Create Event', 'url'=>array('create')),
	array('label'=>'Update Event', 'url'=>array('update', 'id'=>$model->id), 'visible' => Yii::app()->user->id == $model->userId),
	array('label'=>'Delete Event', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?'), 'visible' => Yii::app()->user->id == $model->userId),
	array('label'=>'Manage Event', 'url'=>array('admin'), 'visible' => Yii::app()->user->checkAccess('admin')),
);
?>

<h1>Event <?php echo $model->title; ?></h1>

<?php
$this->widget('bootstrap.widgets.BootDetailView', array(
    'data'=>$model,
	'attributes'=>array(
		array(
			'name'  => 'userId',
			'value' => CHtml::encode($model->user->name),
		),
		array(
			'name' => 'dateTime',
			'value' => Yii::app()->dateFormatter->format('yyyy-MM-dd HH:mm', $model->dateTime),
		),
		'title',
		'venue',
		'shortTitle',
		array(
			'name'  => 'status',
			'value' => CHtml::encode($model->getStatusAsText()),
		),
		'location',
		'description',
	),
));
?>

<br>
<h1>Event Issues</h1>
<?php $this->widget('zii.widgets.CListView', array(
  'dataProvider'=>$dataProvider,
  'itemView'=>'/event/_view',
)); ?>