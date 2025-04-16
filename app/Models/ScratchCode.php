<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScratchCode extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'student_id', 'uses_left'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function isUsable()
    {
        return $this->uses_left > 0 && is_null($this->student_id);
    }
}
