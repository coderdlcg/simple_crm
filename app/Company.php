<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'logo', 'address',
    ];


    public function employees(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Employee');
    }
}
