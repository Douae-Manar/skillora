<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobOffer;
use App\Models\Domain;
use App\Models\Skill;
use Illuminate\Support\Facades\DB;

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

        // 4. تنوع المهن داخل كل قطاع
        $diversityData = JobOffer::join('domains', 'job_offers.id_domain', '=', 'domains.id_domain')
            ->select('domains.domain_name', DB::raw('COUNT(DISTINCT job_offers.title) as unique_titles'))
            ->groupBy('domains.id_domain', 'domains.domain_name')
            ->orderBy('unique_titles', 'desc')
            ->take(4)
            ->get();

        // 5. [تصحيح] المهارة الأكثر طلباً في كل قطاع بشكل دقيق لكل قطاع على حدة
        $skillsPerDomain = DB::select("
            SELECT d.domain_name, s.skill_name, COUNT(js.id_job) as total
            FROM domains d
            JOIN job_offers j ON d.id_domain = j.id_domain
            JOIN job_skill js ON j.id_job = js.id_job
            JOIN skills s ON js.id_skill = s.id_skill
            GROUP BY d.id_domain, d.domain_name, s.id_skill, s.skill_name
            HAVING (d.id_domain, COUNT(js.id_job)) IN (
                SELECT id_dom, MAX(cnt) FROM (
                    SELECT d2.id_domain as id_dom, s2.id_skill, COUNT(js2.id_job) as cnt
                    FROM domains d2
                    JOIN job_offers j2 ON d2.id_domain = j2.id_domain
                    JOIN job_skill js2 ON j2.id_job = js2.id_job
                    GROUP BY d2.id_domain, s2.id_skill
                ) t GROUP BY id_dom
            )
            LIMIT 4
        ");

        // 6. عينات عشوائية لأحدث العروض
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