<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'student_id',
        'answer_text',
        'score',
        'status',
        'instructor_feedback'
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
    
    public function isPending()
    {
        return $this->status === 'pending';
    }
    
    public function isGraded()
    {
        return $this->status === 'graded';
    }
}
