<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;

    protected $table = 'skills';
    protected $primaryKey = 'id_skill';
    protected $fillable = ['skill_name'];

    public function jobOffers()
    {
        return $this->belongsToMany(JobOffer::class, 'job_skill', 'id_skill', 'id_job');
    }
}