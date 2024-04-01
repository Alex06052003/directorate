<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentPractic extends Model
{
    use HasFactory;
    protected $table = 'student_practic';
    public $timestamps = false;

    public function company(){
        return $this->hasOne(Company::class, 'id', 'company_id');
    }
    public function student(){
        return $this->hasOne(Student::class, 'id', 'student_id');
    }

    /// Не знаю надо ли оставлять (будет ли меняться хоть какое-то поле)
    protected $fillable = [
        'status',
    ];
}
