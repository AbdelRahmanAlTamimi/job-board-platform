<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\JobVacancy;
use App\Models\User;
use App\Models\Resume;

class JobApplication extends Model
{
    use SoftDeletes, HasUuids, HasFactory;

    protected $table = 'job_applications';

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'status',
        'aiGeneratedScore',
        'aiGeneratedFeedback',
        'jobVacancyId',
        'jobSeekerId',
        'resumeId'
    ];

    protected $dates = [
        'deleted_at'
    ];
    protected function casts(): array
    {
        return [
            'deleted_at' => 'datetime'
        ];
    }

    public function jobVacancy()
    {
        return $this->belongsTo(JobVacancy::class, 'jobVacancyId','id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'jobSeekerId','id');
    }

    public function resume()
    {
        return $this->belongsTo(Resume::class, 'resumeId','id');
    }
}
