<?php

namespace App\Enums\Auth\Permissions;

enum OrderEnum: string
{
    case ORDER_VIEW = 'order_view';
    case ORDER_CREATE = 'order_create';
    case ORDER_UPDATE = 'order_update';
    case ORDER_DELETE = 'order_delete';
}
