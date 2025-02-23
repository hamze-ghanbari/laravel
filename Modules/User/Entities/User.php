<?php

namespace Modules\User\Entities;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Casts\PersianDateCast;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Modules\User\Traits\Permissions\HasPermission;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes, HasPermission;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [
        'id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
//        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'mobile_verified_at' => 'datetime',
        'birth_date' => PersianDateCast::class,
    ];


    protected function fullName(): Attribute
    {
    return Attribute::make(
        get: function(){
            if (isset($this->attributes['first_name']) && isset($this->attributes['last_name'])) {
                return $this->attributes['first_name'] . ' ' . $this->attributes['last_name'];

            } elseif (isset($this->attributes['first_name'])) {
                return $this->attributes['first_name'];

            } elseif (isset($this->attributes['last_name'])) {
                return $this->attributes['last_name'];
            } else {
                return '-----';
            }
        }
    );
    }

    protected function createdAt(): Attribute{
        return Attribute::make(
            get: fn (string $value) =>  jalaliDate($value)
        );
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }


    public function scopeSearch(Builder $query, $term = null, $time = null)
    {
        return $query->when($term, function (Builder $query, $term) {
            $query->where(function ($query) use ($term) {
                $query->where('first_name', 'like', "%{$term}%")
                    ->orWhere('last_name', 'like', "%{$term}%")
                    ->orWhere('mobile', 'like', "%{$term}%")
                    ->orWhere('email', 'like', "%{$term}%");
            });
        })->when($time, function (Builder $query, $time) {
            $query->where('created_at', '<=', $time);
        });
    }

}
