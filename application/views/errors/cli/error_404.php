<?php
defined('BASEPATH') ;

echo filter_var(("\nERROR: ".
	$heading.
	"\n\n".
	$message.
	"\n\n"), FILTER_SANITIZE_FULL_SPECIAL_CHARS);