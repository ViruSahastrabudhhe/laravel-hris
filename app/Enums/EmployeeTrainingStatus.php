<?php

namespace App\Enums;

enum EmployeeTrainingStatus: string
{
    case Pending  = 'Pending';
    case Enrolled  = 'Enrolled';
    case Ongoing= 'Ongoing';
    case Completed = 'Completed';
    case Declined    = 'Declined';
    case Failed    = 'Failed';
    case Canceled  = 'Canceled';
}
