<?php

namespace App\Traits\Models;

use App\Enums\Auth\RolesEnum;
use App\Enums\Auth\MembershipEnum;

/**
 * User model relations
 */
trait HasUserIsRole
{
    /**
     * Check is super_admin role (admin)
     *
     * @var string
     */
    public function isSuperAdmin()
    {
        return $this->hasRole([
            RolesEnum::SUPERADMIN,
        ]);
    }

    /**
     * Check is admin role (admin)
     *
     * @var string
     */
    public function isAdmin()
    {
        return $this->hasRole([
            RolesEnum::ADMIN,
        ]);
    }

    /**
     * Check is worker role (admin)
     *
     * @var string
     */
    public function isWorker()
    {
        return $this->hasRole([
            RolesEnum::WORKER,
        ]);
    }

    /**
     * Check is writer role (client)
     *
     * @var string
     */
    public function isWriter()
    {
        return $this->hasRole([
            RolesEnum::WRITER
        ]);
    }

    /**
     * Check is partner role (client)
     *
     * @var string
     */
    public function isPartner()
    {
        return $this->hasRole([
            RolesEnum::PARTNER
        ]);
    }

    /**
     * Check is editor role (client)
     *
     * @var string
     */
    public function isEditor()
    {
        return $this->hasRole([
            RolesEnum::EDITOR
        ]);
    }

    /**
     * Check is lite role (client)
     *
     * @var string
     */
    public function isMembershipLite()
    {
        return $this->hasRole([
            MembershipEnum::LITE
        ]);
    }

    /**
     * Check is pro role (client)
     *
     * @var string
     */
    public function isMembershipPro()
    {
        return $this->hasRole([
            MembershipEnum::PRO
        ]);
    }

    /**
     * Check is vip role (client)
     *
     * @var string
     */
    public function isMembershipVip()
    {
        return $this->hasRole([
            MembershipEnum::VIP
        ]);
    }
}
