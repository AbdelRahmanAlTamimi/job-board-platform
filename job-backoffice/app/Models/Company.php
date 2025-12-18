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
}
