<?php
// src/Config/TextAlign.php
namespace App\Enum;

enum RoleEnum: string
{
    case ROLE_ADMIN = 'ROLE_ADMIN';
    case ROLE_TEACHER = 'ROLE_TEACHER';
    case ROLE_STUDENT = 'ROLE_STUDENT';
}