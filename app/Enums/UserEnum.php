<?php
namespace App\Enums;
 enum UserEnum: string
 {
    case SUPER_ADMIN = 'super-admin';
    case ADMIN = 'admin';
    case TEACHER = 'teacher';
    case STUDENT = 'student';
 }
