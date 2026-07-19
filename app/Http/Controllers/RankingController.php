<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class RankingController extends Controller
{
    public function index()
    {
        // 1. Top métiers (أكثر الوظائف طلباً)
        $topJobs = DB::table('job_offers')
            ->select('title', DB::raw('count(*) as total'))
            ->groupBy('title')
            ->orderBy('total', 'desc')
            ->take(5)
            ->get();

        // 2. Top compétences (أكثر المهارات طلباً)
        $topSkills = DB::table('job_skill')
            ->join('skills', 'job_skill.id_skill', '=', 'skills.id_skill')
            ->select('skills.skill_name', DB::raw('count(*) as total'))
            ->groupBy('skills.id_skill', 'skills.skill_name')
            ->orderBy('total', 'desc')
            ->take(6)
            ->get();

        // 3. Répartition des domaines (توزيع الوظائف على حسب القطاعات)
        $domainDistribution = DB::table('job_offers')
            ->join('domains', 'job_offers.id_domain', '=', 'domains.id_domain')
            ->select('domains.domain_name', DB::raw('count(*) as total'))
            ->groupBy('domains.id_domain', 'domains.domain_name')
            ->orderBy('total', 'desc')
            ->get();

        return view('rankings', compact('topJobs', 'topSkills', 'domainDistribution'));
    }
}