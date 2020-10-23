<?php
defined('BASEPATH') ;
?>

<div style="border:1px solid #990000;padding-left:20px;margin:0 0 10px 0;">

<h4>An uncaught Exception was encountered</h4>

<p>Type: <?php echo filter_var(get_class($exception), FILTER_SANITIZE_FULL_SPECIAL_CHARS); ?></p>
<p>Message: <?php echo filter_var($message, FILTER_SANITIZE_FULL_SPECIAL_CHARS); ?></p>
<p>Filename: <?php echo filter_var($exception->getFile(), FILTER_SANITIZE_FULL_SPECIAL_CHARS); ?></p>
<p>Line Number: <?php echo filter_var($exception->getLine(), FILTER_SANITIZE_FULL_SPECIAL_CHARS); ?></p>

<?php if (defined('SHOW_DEBUG_BACKTRACE') && SHOW_DEBUG_BACKTRACE === TRUE): ?>

	<p>Backtrace:</p>
	<?php foreach ($exception->getTrace() as $error): ?>

		<?php if (isset($error['file']) && strpos($error['file'], realpath(BASEPATH)) !== 0): ?>

			<p style="margin-left:10px">
			File: <?php echo filter_var($error['file'], FILTER_SANITIZE_FULL_SPECIAL_CHARS); ?><br />
			Line: <?php echo filter_var($error['line'], FILTER_SANITIZE_FULL_SPECIAL_CHARS); ?><br />
			Function: <?php echo filter_var($error['function'], FILTER_SANITIZE_FULL_SPECIAL_CHARS); ?>
			</p>
		<?php endif ?>

	<?php endforeach ?>

<?php endif ?>

</div>