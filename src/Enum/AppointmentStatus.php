<?php

namespace App\Enum;

enum AppointmentStatus: string
{
    case PROGRAM = 'program';
    case IN_PROGRESS = 'in_progress';
    case FINISH = 'finish';
}