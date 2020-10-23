<?php
defined('BASEPATH') ;

echo "\nDatabase error: ",
	filter_var($heading, FILTER_SANITIZE_FULL_SPECIAL_CHARS),
	"\n\n",
	filter_var($message, FILTER_SANITIZE_FULL_SPECIAL_CHARS),
	"\n\n";