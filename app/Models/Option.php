<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Option extends Model
{
    // Laravel otomatis menganggap tabelnya 'options'
    
    protected $fillable = [
        'question_id',
        'option_label',
        'value',
        'has_text_input' // Penting untuk fitur "Lainnya"
    ];

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}