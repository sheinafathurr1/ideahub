<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    protected $guarded = ['id'];

    // UPDATE DI SINI: Gunakan model Option::class
    public function options(): HasMany
    {
        return $this->hasMany(Option::class);
    }

    // Relasi ke Pertanyaan Induk (Self Join)
    public function parent()
    {
        return $this->belongsTo(Question::class, 'depends_on_question_id');
    }
}