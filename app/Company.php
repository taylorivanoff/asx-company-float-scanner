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
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'float_integer',
        'code_short'
    ];

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
     * Get the company name
     *
     * @return string
     */
    public function getNameAttribute($value)
    {
        return ucwords(strtolower($value));
	}

    /**
     * Get the company float in integer.
     *
     * @return string
     */
    public function getFloatIntegerAttribute()
    {
        $letters = [
            'k' => 1000,
            'M' => 1000000,
            'B' => 1000000000,
        ];

        foreach ($letters as $letter => $multiple) {
            if (strpos($this->float, $letter) !== false) {
                str_replace($letter, "", $this->float);
                return $this->attributes['float_integer'] = (float) $this->float * $multiple;
            }
        }
    }

    /**
     * Get the company code without AX.
     *
     * @return string
     */
    public function getCodeShortAttribute()
    {
        return $this->attributes['code_short'] = str_replace(".AX", "", $this->code);
    }

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
