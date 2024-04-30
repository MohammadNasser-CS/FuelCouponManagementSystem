<?php

namespace App\Enum;


enum UserRoleEnum: string
{
    const MAP = [
        'admin',
        'driver',
        'employee'
    ];
    case ADMIN = 'admin';
    case Driver = 'driver';
    case Employee = 'employee';
}
