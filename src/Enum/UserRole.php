<?php

namespace App\Enum;

enum UserRole: string
{
    case USER = 'user';
    case ADMIN = 'admin';
}