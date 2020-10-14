<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    public function membershipTypes()
    {
        return $this->belongsToMany('App\Models\MembershipType')
            ->withTimestamps()
            ->withPivot([
                            'price',
                        ]);
    }
}
