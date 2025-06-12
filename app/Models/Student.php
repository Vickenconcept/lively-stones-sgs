<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    //
    use HasFactory;

    protected $guarded = [];
    
    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function scores()
    {
        return $this->hasMany(StudentScore::class);
    }

    public function results()
    {
        return $this->hasMany(Result::class);
    }
}
