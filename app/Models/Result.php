<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    //
    use HasFactory;

    protected $guarded = [];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function term()
    {
        return $this->belongsTo(Term::class);
    }

    public function sessionYear()
    {
        return $this->belongsTo(SessionYear::class);
    }
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
