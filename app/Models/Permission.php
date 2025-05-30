<?php

namespace App\Models;


use App\Models\moduleMenu;
use Spatie\Permission\Guard;
use Ramsey\Uuid\Uuid as Generator;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Traits\RefreshesPermissionCache;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Exceptions\PermissionAlreadyExists;
use Spatie\Permission\Contracts\Permission as PermissionContract;


/**
 * App\Models\Permission
 *
 * @property int $id
 * @property string $uuid
 * @property string $module_id
 * @property string $name
 * @property string $guard_name
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read moduleMenu|null $modules
 * @property-read Collection|\Spatie\Permission\Models\Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read Collection|\Spatie\Permission\Models\Role[] $roles
 * @property-read int|null $roles_count
 * @property-read Collection|\App\Models\User[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|Permission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission newQuery()
 * @method static \Illuminate\Database\Query\Builder|Permission onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission query()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission role($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereGuardName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereModuleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereUuid($value)
 * @method static \Illuminate\Database\Query\Builder|Permission withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Permission withoutTrashed()
 * @mixin \Eloquent
 */

/*
|--------------------------------------------------------------------------
| Ibnudirsan Dev
| Backend Developer : ibudirsan
| Email             : ibnudirsan@gmail.com
| Copyright © ibnudirsan 2025
|--------------------------------------------------------------------------
*/
class Permission extends Model implements PermissionContract
{
    use HasFactory, HasRoles, RefreshesPermissionCache, SoftDeletes;

    protected $table = 'permissions';
    protected $fillable = [
        'uuid','module_id','name','guard_name'
    ];

    public function __construct(array $attributes = []) {
        $attributes['guard_name'] = $attributes['guard_name'] ?? config('auth.defaults.guard');

        parent::__construct($attributes);

        $this->guarded[] = $this->primaryKey;
    }

    /* Auto Create UUID
    *
    * @return void
    */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            try {
                $model->uuid = str_replace('-', '', Generator::uuid4()->toString());
            } catch (\Exception $e) {
                abort(500, $e->getMessage());
            }
        });
    }

    public function getTable() {
        return config('permission.table_names.permissions', parent::getTable());
    }

    public static function create(array $attributes = []) {
        $attributes['guard_name'] = $attributes['guard_name'] ?? Guard::getDefaultName(static::class);

        $permission = static::getPermission(['name' => $attributes['name'], 'guard_name' => $attributes['guard_name']]);

        if ($permission) {
            throw PermissionAlreadyExists::create($attributes['name'], $attributes['guard_name']);
        }

        return static::query()->create($attributes);
    }

    /**
    * A permission can be applied to roles.
    */
    public function roles(): BelongsToMany {
        return $this->belongsToMany(
            config('permission.models.role'),
            config('permission.table_names.role_has_permissions'),
            PermissionRegistrar::$pivotPermission,
            PermissionRegistrar::$pivotRole
        );
    }

    /**
    * A permission belongs to some users of the model associated with its guard.
    */
    public function users(): BelongsToMany {
        return $this->morphedByMany(
            getModelForGuard($this->attributes['guard_name']),
            'model',
            config('permission.table_names.model_has_permissions'),
            PermissionRegistrar::$pivotPermission,
            config('permission.column_names.model_morph_key')
        );
    }

    /**
    * Find a permission by its name (and optionally guardName).
    *
    * @param string $name
    * @param string|null $guardName
    *
    * @throws \Spatie\Permission\Exceptions\PermissionDoesNotExist
    *
    * @return \Spatie\Permission\Contracts\Permission
    */
    public static function findByName(string $name, $guardName = null): PermissionContract {
        $guardName = $guardName ?? Guard::getDefaultName(static::class);
        $permission = static::getPermission(['name' => $name, 'guard_name' => $guardName]);
        if (! $permission) {
            throw PermissionDoesNotExist::create($name, $guardName);
        }

        return $permission;
    }

    /**
    * Find a permission by its id (and optionally guardName).
    *
    * @param int $id
    * @param string|null $guardName
    *
    * @throws \Spatie\Permission\Exceptions\PermissionDoesNotExist
    *
    * @return \Spatie\Permission\Contracts\Permission
    */
    public static function findById(int $id, $guardName = null): PermissionContract {
        $guardName = $guardName ?? Guard::getDefaultName(static::class);
        $permission = static::getPermission(['id' => $id, 'guard_name' => $guardName]);

        if (! $permission) {
            throw PermissionDoesNotExist::withId($id, $guardName);
        }

        return $permission;
    }

    /**
    * Find or create permission by its name (and optionally guardName).
    *
    * @param string $name
    * @param string|null $guardName
    *
    * @return \Spatie\Permission\Contracts\Permission
    */
    public static function findOrCreate(string $name, $guardName = null): PermissionContract {
        $guardName = $guardName ?? Guard::getDefaultName(static::class);
        $permission = static::getPermission(['name' => $name, 'guard_name' => $guardName]);

        if (! $permission) {
            return static::query()->create(['name' => $name, 'guard_name' => $guardName]);
        }

        return $permission;
    }

    /**
    * Get the current cached permissions.
    *
    * @param array $params
    * @param bool $onlyOne
    *
    * @return \Illuminate\Database\Eloquent\Collection
    */
    protected static function getPermissions(array $params = [], bool $onlyOne = false): Collection {
        return app(PermissionRegistrar::class)
        ->setPermissionClass(static::class)
        ->getPermissions($params, $onlyOne);
    }

    /**
    * Get the current cached first permission.
    *
    * @param array $params
    *
    * @return \Spatie\Permission\Contracts\Permission
    */
    protected static function getPermission(array $params = []) {
        return static::getPermissions($params, true)->first();
    }

    public function modules() {
        return $this->hasOne(moduleMenu::class,'uuid','module_id')->select('uuid','module_name');
    }

}
