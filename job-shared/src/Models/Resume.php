<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\JobApplication;

class Resume extends Model
{
    use SoftDeletes, HasUuids, HasFactory;

    protected $table = 'resumes';

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'fileName',
        'fileUrl',
        'contactDetails',
        'summary',
        'education',
        'experience',
        'skills',
        'jobSeekerId'
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

    public function user()
    {
        return $this->belongsTo(User::class, 'jobSeekerId','id');
    }

    public function jobApplications()
    {
        return $this->hasMany(JobApplication::class, 'resumeId','id');
    }
}
