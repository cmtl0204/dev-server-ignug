<?php

namespace App\Models\Attendance;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\Ignug\State;
use phpDocumentor\Reflection\Types\Boolean;

class Catalogue extends Model
{

    protected $connection = 'pgsql-attendance';
    protected $fillable = [
        'code',
        'name',
        'type',
        'icon'
    ];

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function parent()
    {
        return $this->belongsTo(Catalogue::class, 'parent_code_id');
    }

    public function children()
    {
        return $this->hasMany(Catalogue::class, 'parent_code_id');
    }

    public function setCodeAttribute($value)
    {
        $this->attributes['code'] = strtolower($value);
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtoupper($value);
    }
}
