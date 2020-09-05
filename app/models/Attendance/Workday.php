<?php

namespace App\Models\Attendance;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\Ignug\State;
use App\Models\Attendance\Catalogue;

class Workday extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $connection = 'pgsql-attendance';

    protected $fillable = [
        'start_time',
        'end_time',
        'description',
        'duration',
        'observations',
    ];

    protected $casts = [
        'start_time' => 'time:H:i:s',
        'end_time' => 'time:H:i:s',
        'observations' => 'array',
    ];

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }

    public function type()
    {
        return $this->belongsTo(Catalogue::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }
}
