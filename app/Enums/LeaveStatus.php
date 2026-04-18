<?php

namespace App\Enums;

enum LeaveStatus: String
{
    case Pending = 'Pending'; 
    case Approved = 'Approved';
    case Declined = 'Declined';
    case Review = 'Reviewing';
}
