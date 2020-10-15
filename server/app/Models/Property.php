<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    /**
     * membershipTypeとのリレーション
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function membershipTypes()
    {
        return $this->belongsToMany('App\Models\MembershipType')
            ->withTimestamps()
            ->withPivot([
                'price',
            ]);
    }
}
