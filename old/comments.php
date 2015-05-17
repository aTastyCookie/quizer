<?php require_once 'app/init.php'; ?>

<?php echo View::make('header')->render() ?>
    
<div class="row">
	<div class="col-md-8">
		<h3 class="page-header">Comments</h3>
		
		<?php echo ajax_comments('1', 'My page'); ?>

	</div>
</div>

<?php echo View::make('footer')->render() ?>