<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
    */
    public $fillable = [
      'name', 'email','website'
    ];

    public function employees()
    {
      return $this->hasMany(Employee::class);
    }
}
