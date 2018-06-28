<?php
define("ROOT", realpath(__DIR__));

require_once './app/config.php';
require_once ROOT.'/app/kernel/Kernel.php';

Kernel::run();