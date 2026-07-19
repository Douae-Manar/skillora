<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobOffer;
use App\Models\Domain;
use App\Models\Skill;
use DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. الإحصائيات السريعة العامة
        $totalJobs = JobOffer::count();
        $totalDomains = Domain::count();
        $totalSkills = Skill::count();

        // 2. أكثر الـ Domains المطلوبة (Top 5)
        $domainsData = JobOffer::join('domains', 'job_offers.id_domain', '=', 'domains.id_domain')
            ->select('domains.domain_name', DB::raw('count(job_offers.id_job) as total'))
            ->groupBy('domains.id_domain', 'domains.domain_name')
            ->orderBy('total', 'desc')
            ->take(5)
            ->get();

        // 3. أكثر المهارات المطلوبة عامة (Top 7)
        $skillsData = Skill::join('job_skill', 'skills.id_skill', '=', 'job_skill.id_skill')
            ->select('skills.skill_name', DB::raw('count(job_skill.id_job) as total'))
            ->groupBy('skills.id_skill', 'skills.skill_name')
            ->orderBy('total', 'desc')
            ->take(7)
            ->get();

        // 4. [فكرة 1] تنوع المهن داخل كل قطاع (Top Domains by Diversity)
        $diversityData = JobOffer::join('domains', 'job_offers.id_domain', '=', 'domains.id_domain')
            ->select('domains.domain_name', DB::raw('COUNT(DISTINCT job_offers.title) as unique_titles'))
            ->groupBy('domains.id_domain', 'domains.domain_name')
            ->orderBy('unique_titles', 'desc')
            ->take(4)
            ->get();

        // 5. [فكرة 2] المهارة الأكثر طلباً في كل قطاع (Top Skill per Domain)
        $skillsPerDomain = DB::select("
            SELECT d.domain_name, s.skill_name, COUNT(*) as total
            FROM job_offers j
            JOIN domains d ON j.id_domain = d.id_domain
            JOIN job_skill js ON j.id_job = js.id_job
            JOIN skills s ON js.id_skill = s.id_skill
            WHERE (j.id_domain, s.id_skill) IN (
                SELECT id_domain, id_skill FROM (
                    SELECT j2.id_domain, js2.id_skill, COUNT(*) as cnt,
                           ROW_NUMBER() OVER (PARTITION BY j2.id_domain ORDER BY COUNT(*) DESC) as rn
                    FROM job_offers j2
                    JOIN job_skill js2 ON j2.id_job = js2.id_job
                    GROUP BY j2.id_domain, js2.id_skill
                ) tmp WHERE rn = 1
            )
            GROUP BY d.id_domain, d.domain_name, s.id_skill, s.skill_name
            LIMIT 4
        ");

        // 6. [فكرة 3] عينات عشوائية لأحدث العروض (Latest Offers Preview)
        $latestOffers = JobOffer::with('domain')
            ->orderBy('id_job', 'desc')
            ->take(4)
            ->get();

        return view('dashboard', compact(
            'totalJobs', 'totalDomains', 'totalSkills', 
            'domainsData', 'skillsData', 'diversityData', 
            'skillsPerDomain', 'latestOffers'
        ));
    }
}
