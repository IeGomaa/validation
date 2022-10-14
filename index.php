<?php

require_once 'Validation.php';
use Validation\Validation;
$validation = new Validation();

$validation->input('username')->value('ibrahim')->string()->required();
$validation->successful();