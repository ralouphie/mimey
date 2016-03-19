<?php

// Turn on all errors.
error_reporting(E_ALL);

require_once dirname(__DIR__) . '/vendor/autoload.php';

// Generate the mapping file.
require dirname(__DIR__) . '/bin/generate.php';
