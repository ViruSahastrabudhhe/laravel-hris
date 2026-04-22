<?php

namespace App\Enums;

enum SalaryType: string
{
    case Monthly = 'monthly';
    case Hourly = 'hourly';
    case Contractual = 'contractual';
}
