<?php
defined('BASEPATH') OR exit('No direct script access allowed');

echo "\nERROR: ",
	filter_var($heading, FILTER_SANITIZE_FULL_SPECIAL_CHARS),
	"\n\n",
	filter_var($message, FILTER_SANITIZE_FULL_SPECIAL_CHARS),
	"\n\n";