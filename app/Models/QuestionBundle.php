<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionBundle extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    public function questions()
    {
        return $this->belongsToMany(Question::class, 'bundle_questions');
    }

    public function sections()
    {
        return $this->hasMany(TestSection::class);
    }
}
