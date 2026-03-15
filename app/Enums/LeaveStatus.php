<?php

namespace App\Enums;

enum LeaveStatus: String
{
    case Approved = 'Approved';
    case Pending = 'Pending'; 
    case Declined = 'Declined';
    case Review = 'Reviewing';
}
