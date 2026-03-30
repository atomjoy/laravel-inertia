<?php

namespace App\Enums\Auth\Permissions;

enum UserEnum: string
{
    case USER_VIEW = 'user_view';
    case USER_CREATE = 'user_create';
    case USER_UPDATE = 'user_update';
    case USER_DELETE = 'user_delete';
}
