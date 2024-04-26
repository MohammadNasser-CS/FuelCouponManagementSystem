<?php

namespace App\Enum;


enum UserRoleEnum: string
{
    case ADMIN = 'admin';
    case Driver = 'driver';
    case Employee = 'employee';
    
}
