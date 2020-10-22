<?php
defined('BASEPATH') OR exit('No direct script access allowed');

echo "\nDatabase error: ",
	html_escape($heading),
	"\n\n",
	$message,
	"\n\n";