<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $guarded = ['id'];

    // Relasi ke Pilihan Jawaban
    public function options()
    {
        return $this->hasMany(QuestionOption::class);
    }

    // Relasi ke Pertanyaan Induk (Self Join)
    public function parent()
    {
        return $this->belongsTo(Question::class, 'depends_on_question_id');
    }
}