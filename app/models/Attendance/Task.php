<?php

namespace App\Models\Attendance;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\Ignug\State;
use App\Models\Attendance\Catalogue;

class Task extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $connection = 'pgsql-attendance';

    protected $fillable = [
        'description',
        'percentage_advance',
        'observations'
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
