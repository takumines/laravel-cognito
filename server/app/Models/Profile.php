<?php

namespace App\Models;

use App\Http\Requests\Api\ProfileRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Profile extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'first_name', 'last_name', 'first_name_kana', 'last_name_kana', 'postal_code',
        'prefecture', 'municipality_county', 'address', 'building＿name', 'birthday', 'annual_income',
        'entry_sheet', 'identification_photo_front', 'identification_photo_reverse',
    ];

    public function insert(ProfileRequest $request)
    {
        Log::info($request['identification_photo_front']);
    }
}
