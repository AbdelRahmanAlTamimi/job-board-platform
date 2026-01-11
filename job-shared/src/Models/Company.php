<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\JobVacancy;

class Company extends Model
{
    use SoftDeletes, HasUuids, HasFactory;
    protected $table = 'companies';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'name',
        'address',
        'website',
        'industry',
        'ownerId'
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

    public function owner()
    {
        return $this->belongsTo(User::class, 'ownerId','id');
    }

    public function jobVacancies()
    {
        return $this->hasMany(JobVacancy::class, 'companyId','id');
    }

    public function jobApplications()
    {
        // Pull applications via the company's vacancies to avoid relying on a missing companyId column on job_applications.
        return $this->hasManyThrough(
            JobApplication::class,
            JobVacancy::class,
            'companyId',      // Foreign key on job_vacancies pointing to companies
            'jobVacancyId',   // Foreign key on job_applications pointing to job_vacancies
            'id',             // Local key on companies
            'id'              // Local key on job_vacancies
        );
    }
}
