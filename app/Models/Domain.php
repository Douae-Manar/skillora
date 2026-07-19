<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    use HasFactory;

    protected $table = 'domains';
    protected $primaryKey = 'id_domain';
    protected $fillable = ['domain_name'];

    public function jobOffers()
    {
        return $this->hasMany(JobOffer::class, 'id_domain', 'id_domain');
    }
}