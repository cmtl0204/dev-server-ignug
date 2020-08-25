<?php

namespace App\Models\Attendance;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\Ignug\State;

class Catalogue extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $connection = 'pgsql-attendance';
    protected $fillable = [
        'code',
        'parent_code_id',
        'name',
        'type',
        'icon'
    ];

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function tasks()
    {
        return $this->hasMany(Catalogue::class, 'parent_code_id');
    }
}
