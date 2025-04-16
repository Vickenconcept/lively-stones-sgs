<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    //
    use HasFactory;

    protected $guarded = [];
    
    public function classSubjectTerms()
    {
        return $this->hasMany(ClassSubjectTerm::class);
    }

    public function scores()
    {
        return $this->hasMany(StudentScore::class);
    }
}
