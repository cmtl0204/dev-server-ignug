<?php

namespace App\Models\Ignug;

use App\Models\Attendance\Workday;
use App\User;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class State extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $connection = 'pgsql-ignug';
    public $timestamps = false;
    protected $fillable = [
        'code',
        'name',
        'state',
    ];

    public function institutions()
    {
        return $this->hasMany(Institution::class);
    }

    public function workdays()
    {
        return $this->hasMany(Workday::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
