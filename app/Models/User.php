<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', 
        'university_name',
        'slug',
        'university_type',
        'university_category',
        'has_disability_study_program',
        'phone_number',
        'university_logo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'has_disability_study_program' => 'boolean',
        ];
    }

    // Relasi ke Submissions
    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

    // Ambil submission yang accepted (untuk ditampilkan di landing page)
    public function acceptedSubmission()
    {
        return $this->hasOne(Submission::class)
                    ->where('status', 'accepted')
                    ->latest('submitted_at');
    }

    // Auto-generate slug saat create/update
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (empty($user->slug) && !empty($user->university_name)) {
                $user->slug = static::generateUniqueSlug($user->university_name);
            }
        });

        static::updating(function ($user) {
            if ($user->isDirty('university_name')) {
                $user->slug = static::generateUniqueSlug($user->university_name, $user->id);
            }
        });
    }

    // Helper: Generate unique slug
    protected static function generateUniqueSlug($name, $ignoreId = null)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $count = 1;

        while (static::slugExists($slug, $ignoreId)) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }

    // Helper: Cek slug sudah ada atau belum
    protected static function slugExists($slug, $ignoreId = null)
    {
        $query = static::where('slug', $slug);
        
        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        return $query->exists();
    }
}