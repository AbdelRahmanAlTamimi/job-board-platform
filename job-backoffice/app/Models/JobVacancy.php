<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Company;
use App\Models\JobCategory;

class JobVacancy extends Model
{
    use SoftDeletes, HasUuids, HasFactory;
    
    protected $table = 'job_vacancies';

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'title',
        'description',
        'location',
        'salary',
        'companyId',
        'jobCategoryId',
        'type'
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

    public function company()
    {
        return $this->belongsTo(Company::class, 'companyId','id');
    }

    public function jobCategory()
    {
        return $this->belongsTo(JobCategory::class, 'jobCategoryId','id');
    }

    public function jobApplications()
    {
        return $this->hasMany(JobApplication::class, 'jobVacancyId','id');
    }
}
