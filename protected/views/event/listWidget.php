<?php

$this->layout = 'widgetLayout';

?>

<div class = "gigListTitle">
	<?php echo CHtml::encode($user->name) . "'s next events" ?>
</div>

<?php

$this->widget('zii.widgets.CListView', array(
    'dataProvider'=>$dataProvider,
    'itemView'=>'_listWidget',   // refers to the partial view named '_post'
	'template'=>'{items} {summary} {pager}',
	'cssFile' => 'false',
	'summaryCssClass' => 'gigSecundary',
    'sortableAttributes'=>array(
        'dateTime',
		'venue',
    ),
));

?>
