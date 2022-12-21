<?php

namespace App\Enums;

enum UserEnum: string
{
    case SUPER_ADMIN = 'super-admin';
    case ADMIN = 'admin';
    case TEACHER = 'teacher';
    case STUDENT = 'student';

    public function getUserProperty(): array
    {
        return match ($this) {
            self::SUPER_ADMIN, self::ADMIN => [
                'url' => 'admin.users.index',
                'store' => __('Admin Created Successfully'),
                'update' => __('Admin Profile updated successfully'),
                'delete' => __('Admin Profile deleted successfully'),
                'trash' => __('Admin Profile moved to the Trash successfully'),
                'restore' => __('Admin Profile restored'),
            ],
            self::TEACHER => [
                'url' => 'admin.teachers.index',
                'store' => __('Teacher Created Successfully'),
                'update' => __('Teacher Profile updated successfully'),
                'delete' => __('Teacher Profile deleted successfully'),
                'trash' => __('Teacher Profile moved to the Trash successfully'),
                'restore' => __('Teacher Profile restored'),
            ],
            self::STUDENT => [
                'url' => 'admin.students.index',
                'store' => __('Student Created Successfully'),
                'update' => __('Student Profile updated successfully'),
                'delete' => __('Student Profile deleted successfully'),
                'trash' => __('Student Profile moved to the Trash successfully'),
                'restore' => __('Student Profile restored'),
            ]
        };
    }
}
