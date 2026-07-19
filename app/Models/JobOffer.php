<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobOffer extends Model
{
    use HasFactory;

    protected $table = 'job_offers';
    protected $primaryKey = 'id_job';
    protected $fillable = ['title', 'company', 'description', 'id_city', 'id_domain'];

    public function domain()
    {
        return $this->belongsTo(Domain::class, 'id_domain', 'id_domain');
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'job_skill', 'id_job', 'id_skill');
    }
}