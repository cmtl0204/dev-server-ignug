<?php

namespace App\Models\Ignug;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Catalogue extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $connection = 'pgsql-ignug';
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

    public function applyFilters()
    {
        return function () {
            foreach (request('filter', []) as $filter => $value) {
                $scope = "scope" . ucfirst($filter);
                if (!method_exists($this->model, $scope)) {
                    abort(400, "The Filter '$filter' is not allowed");
                }
                $this->{$filter}($value);
            }
            return $this;
        };
    }

    public function scopeName($query, $value)
    {
        if ($value)
            return $query->where('name', 'LIKE', "%{$value}%");
    }
}
