<?php

namespace App;

use App\Models\Attendance\Attendance;
use App\Models\Ignug\Catalogue;
use App\Models\Ignug\State;
use App\Models\Ignug\Teacher;
use App\Models\JobBoard\Company;
use App\Models\JobBoard\Professional;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use OwenIt\Auditing\Contracts\Auditable;

class User extends Authenticatable implements Auditable
{
    use HasApiTokens, Notifiable;
    use \OwenIt\Auditing\Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $connection = 'pgsql-authentication';
    protected $fillable = [
        'identification',
        'postal_code',
        'first_name',
        'second_name',
        'first_lastname',
        'second_lastname',
        'personal_email',
        'birthdate',
        'user_name',
        'email',
        'email_verified_at',
        'password',
        'change_password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function rules()
    {
        return [
            'email.unique' => 'The email has already been taken.',
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
            'user_name.unique' => 'The user_name has already been taken.',
        ];
    }


    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function teacher()
    {
        return $this->hasOne(Teacher::class);
    }

    public function professional()
    {
        return $this->hasOne(Professional::class);
    }

    public function company()
    {
        return $this->hasOne(Company::class);
    }

    public function attendances()
    {
        return $this->morphMany(Attendance::class, 'attendanceable');
    }

    public function ethnicOrigin()
    {
        return $this->belongsTo(Catalogue::class);
    }

    public function location()
    {
        return $this->belongsTo(Catalogue::class);
    }

    public function identificationType()
    {
        return $this->belongsTo(Catalogue::class);
    }

    public function sex()
    {
        return $this->belongsTo(Catalogue::class);
    }

    public function gender()
    {
        return $this->belongsTo(Catalogue::class);
    }

    public function bloodType()
    {
        return $this->belongsTo(Catalogue::class);
    }

    public function attendance()
    {
        return $this->hasOneThrough(Attendance::class);
    }

}
