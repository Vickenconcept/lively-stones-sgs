<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    //
    use HasFactory;

    protected $guarded = [];

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function classSubjectTerms()
    {
        return $this->hasMany(ClassSubjectTerm::class);
    }

    public function results()
    {
        return $this->hasMany(Result::class);
    }
}
