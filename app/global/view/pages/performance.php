<?php

use Resources\Restful\HttpResponse;

global $M;
$array = [
    "Elapsed Time" => $M->Performance->getElapsedTime(),
    "Time Interval" => $M->Performance->getTimeInterval(),
    "Timestamp" => $M->Performance->getTimestamp()
];

HttpResponse::sendOK($array);