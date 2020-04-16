<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Company extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

	/**
     * The attributes that are hidden.
     *
     * @var array
     */
    protected $hidden = [
    	'id',
    	'created_at',
    ];

    /**
	 * Get the route key for the model.
	 *
	 * @return string
	 */
	public function getRouteKeyName()
	{
	    return 'code';
	}


    /**
     * Get last updated human friendly time.
     *
     * @param  string  $value
     * @return string
     */
    // public function getNameAttribute($value)
    // {
    //     return ucwords(strtolower($value));
    // }


    /**
     * Get last updated human friendly time.
     *
     * @param  string  $value
     * @return string
     */
    public function getUpdatedAtAttribute($value)
    {
        return strtoupper(Carbon::parse($value)->diffForHumans());
    }
}
