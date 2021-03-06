<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    public const ROLES = [
        'super_admin' => 1,
        'admin' => 2,
        'client' => 3,
        'company' => 4,
        'travel' => 5,
    ];
    public const ROLES_ID = [
        self::ROLES['super_admin'] => 1,
        self::ROLES['admin'] => 2,
        self::ROLES['client'] => 3,
        self::ROLES['company'] => 4,
        self::ROLES['travel'] => 5,
    ];

    public const ROLE_TEXT = [
        self::ROLES['super_admin'] => 'Sistem Yönetici',
        self::ROLES['admin'] => 'Yönetici',
        self::ROLES['client'] => 'Müşteri',
        self::ROLES['company'] => 'Transfer Şirket',
        self::ROLES['travel'] => 'Seyahat Şirketi',
    ];
    public const ABILITIES = [
        'SUPER_ADMIN' => [
            /* COMPANIES START */
            'companies-can-get',
            'companies-can-add',
            'companies-can-edit',
            'companies-can-delete',
            /* COMPANIES END */

            /* COMPANIES START */
            'cities-can-get',
            'cities-can-add',
            'cities-can-edit',
            'cities-can-delete',
            /* COMPANIES END */

            /* CLIENTS START */
            'clients-can-get',
            'clients-can-edit',
            'clients-can-delete',
            'clients-can-add',
            /* CLIENTS END */


            /* TRANSFERS START */
            'transfers-can-get',
            'transfers-can-edit',
            'transfers-can-delete',
            'transfers-can-add',
            /* TRANSFERS END */
            /* TRANSFERS START */
            'settings-can-get',
            'settings-can-edit',
            'settings-can-add',
            /* TRANSFERS END */

            /* STATISTICS START */
            'statistics-can-get',
            'statistics-can-edit',
            /* STATISTICS END */
        ],
        'ADMIN_ABILITIES' => [
            /* COMPANIES START */
            'companies-can-get',
            'companies-can-add',
            'companies-can-edit',
            /* COMPANIES END */

            /* CLIENTS START */
            'clients-can-get',
            'clients-can-edit',
            'clients-can-delete',
            'clients-can-add',
            /* CLIENTS END */


            /* TRANSFERS START */
            'transfers-can-get',
            'transfers-can-edit',
            'transfers-can-delete',
            'transfers-can-add',
            /* TRANSFERS END */

            /* STATISTICS START */
            'statistics-can-get',
            /* STATISTICS END */


        ],
        'COMPANY_ABILITIES' => [
            /* CLIENTS START */
            'clients-can-get',
            'clients-can-edit',
            'clients-can-delete',
            'clients-can-add',
            /* CLIENTS END */

            /* ACCOUNT START */
            'account-company-can-get',
            'account-company-can-edit',
            /* ACCOUNT END */

            /* TRANSFERS START */
            'transfers-can-get',
            'transfers-can-edit',
            'transfers-can-delete',
            'transfers-can-add',
            /* TRANSFERS END */

            /* STATISTICS START */
            'statistics-can-get',
            /* STATISTICS END */


        ],
        'TRAVEL_ABILITIES' => [
            /* CLIENTS START */
            'clients-can-get',
            'clients-can-edit',
            'clients-can-delete',
            'clients-can-add',
            /* CLIENTS END */

            /* ACCOUNT START */
            'account-company-can-get',
            'account-company-can-edit',
            /* ACCOUNT END */

            /* TRANSFERS START */
            'transfers-can-get',
            'transfers-can-edit',
            'transfers-can-delete',
            'transfers-can-add',
            /* TRANSFERS END */

            /* STATISTICS START */
            'statistics-can-get',
            /* STATISTICS END */


        ],
        'CLIENT_ABILITIES' => [
            /* TRIPS START */
            'trips-can-get',
            'trips-can-add',
            'trips-can-edit',
            /* TRIPS END */

            /* TRIPS START */
            'account-can-get',
            'account-can-edit',
            /* TRIPS END */
        ],
    ];
    const ACTIVE = 1;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_verified_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'role_id' => 'integer'
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }


    public function company()
    {
        return $this->hasOne(Company::class);
    }

    public function getRole()
    {
        return $this->role->name;
    }

    public function isTravel(): bool
    {
        return $this->role->name === 'travel';
    }

    public function isAdmin(): bool
    {
        return $this->role->name === 'admin';
    }
    public function isAdminOrSuper(): bool
    {
        return $this->role->name === 'admin' ||  $this->role->name === 'super_admin';
    }

    public function scopeAdmin($q)
    {
        $admin = Role::admin()->first();
        return $q->where('role_id', $admin->id)->where('active', User::ACTIVE);
    }
    public function scopeSuper($q)
    {
        $admin = Role::superAdmin()->first();
        return $q->where('role_id', $admin->id)->where('active', User::ACTIVE);
    }

    public function scopeAgency($q)
    {
        $admin = Role::agency()->first();
        return $q->where('role_id', $admin->id)->where('active', User::ACTIVE);
    }
    public function scopeCompany($q)
    {
        $admin = Role::company()->first();
        return $q->where('role_id', $admin->id)->where('active', User::ACTIVE);
    }

    public function isSuperAdmin()
    {
        return $this->role->name === 'super_admin';
    }

    public function isCompany()
    {
        return $this->role->name === 'company';
    }

    public function getPermissions()
    {
        return json_decode($this->role->permissions, true);
    }

    public function setRole(string|int $role_id)
    {
        $this->role_id = $role_id;
    }


    public function setActive(bool $val = true)
    {
        $this->active = $val ? 1 : 0;
    }

    public function setPassword(string $password)
    {
        $this->password = bcrypt($password);
    }
}
