<?php $this->beginContent('//layouts/main'); ?>
<div class="container">
	<div class="span-19">
		<div id="content">
			<?php echo $content; ?>
		</div><!-- content -->
	</div>
	<div class="span-3b last">
		<div id="sidebar">
		<?php
			array_unshift($this->menu, array('label'=>'Operations'));

			$this->widget('bootstrap.widgets.BootMenu', array(
				'type'=>'list',
				'items'=>$this->menu,
			));
		?>
		</div><!-- sidebar -->
	</div>
</div>
<?php $this->endContent(); ?>