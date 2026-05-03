<?php

namespace App\Enums;

enum EmployeeTrainingStatus: string
{
    case Enrolled  = 'Enrolled';
    case Completed = 'Completed';
    case Failed    = 'Failed';
    case Canceled  = 'Canceled';
}
