<?php

namespace App\Enums;

enum TrainingStatus: string
{
    case Scheduled = 'Scheduled';
    case Ongoing   = 'Ongoing';
    case Completed = 'Completed';
    case Canceled  = 'Canceled';
}
