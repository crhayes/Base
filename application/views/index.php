<?php extend ('layouts/master') ?>

<?php section('content') ?>
	<h1>Welcome to Base!</h1>
	<p>This is a small, simple, awesome framework!</p>

	<?php foreach ($users as $user) : ?>
		<?= $user->user_id ?>
		<br>
	<?php endforeach; ?>
<?php close() ?>