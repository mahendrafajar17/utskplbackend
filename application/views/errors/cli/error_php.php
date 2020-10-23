<?php defined('BASEPATH') ; ?>

A PHP Error was encountered

Severity:    <?php echo filter_var($severity, FILTER_SANITIZE_FULL_SPECIAL_CHARS), "\n"; ?>
Message:     <?php echo filter_var($message, FILTER_SANITIZE_FULL_SPECIAL_CHARS), "\n"; ?>
Filename:    <?php echo filter_var($filepath, FILTER_SANITIZE_FULL_SPECIAL_CHARS), "\n"; ?>
Line Number: <?php echo filter_var($line, FILTER_SANITIZE_FULL_SPECIAL_CHARS); ?>

<?php if (defined('SHOW_DEBUG_BACKTRACE') && SHOW_DEBUG_BACKTRACE === TRUE): ?>

Backtrace:
<?php	foreach (debug_backtrace() as $error): ?>
<?php		if (isset($error['file']) && strpos($error['file'], realpath(BASEPATH)) !== 0): ?>
	File: <?php echo filter_var($error['file'], FILTER_SANITIZE_FULL_SPECIAL_CHARS), "\n"; ?>
	Line: <?php echo filter_var($error['line'], FILTER_SANITIZE_FULL_SPECIAL_CHARS), "\n"; ?>
	Function: <?php echo filter_var($error['function']), "\n\n"; ?>
<?php		endif ?>
<?php	endforeach ?>

<?php endif ?>
