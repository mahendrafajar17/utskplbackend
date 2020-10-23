<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

An uncaught Exception was encountered

Type:        <?php echo filter_var((get_class($exception)."\n"), FILTER_SANITIZE_FULL_SPECIAL_CHARS); ?>
Message:     <?php echo filter_var(($message."\n"), FILTER_SANITIZE_FULL_SPECIAL_CHARS); ?>
Filename:    <?php echo filter_var(($exception->getFile()."\n"), FILTER_SANITIZE_FULL_SPECIAL_CHARS); ?>
Line Number: <?php echo filter_var($exception->getLine(), FILTER_SANITIZE_FULL_SPECIAL_CHARS); ?>

<?php if (defined('SHOW_DEBUG_BACKTRACE') && SHOW_DEBUG_BACKTRACE === TRUE): ?>

Backtrace:
<?php	foreach ($exception->getTrace() as $error): ?>
<?php		if (isset($error['file']) && strpos($error['file'], realpath(BASEPATH)) !== 0): ?>
	File: <?php echo filter_var(($error['file']."\n"), FILTER_SANITIZE_FULL_SPECIAL_CHARS); ?>
	Line: <?php echo filter_var(($error['line']."\n"), FILTER_SANITIZE_FULL_SPECIAL_CHARS); ?>
	Function: <?php echo filter_var(($error['function']."\n\n"), FILTER_SANITIZE_FULL_SPECIAL_CHARS); ?>
<?php		endif ?>
<?php	endforeach ?>

<?php endif ?>
