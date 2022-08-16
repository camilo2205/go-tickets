<?php

use Carbon\Carbon;

function formatDate($date, $format = 'd/m/Y')
{
    if (!is_null($date)) {
        $fecha = Carbon::parse($date);
        return $fecha->format($format);
    } else {
        return '';
    }
}