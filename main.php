<?php

declare(strict_types=1);

require_once 'vendor/autoload.php';

use App\Application;
use Carbon\Carbon;

$app = new Application();
$app->run();
//$now = (new Carbon())->now();
//$event = (new Carbon('2000-01-01 10:00'));
//echo $now->diffInDays($event);
