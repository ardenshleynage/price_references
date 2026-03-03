<?php

namespace App\Helpers;

use Carbon\Carbon;

trait DateTimeHelper
{
    private function getCurrentDateTime(): array
    {
        $currentDateTime = Carbon::now('America/Port-au-Prince');
        return [
            'date' => $currentDateTime->format('d F, l Y'),
            'time' => $currentDateTime->format('h:i A'),
        ];
    }
}
