<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Skill;
use DB;

class SkillController extends Controller
{
    public function index(Request $request)
    {
        $skills = Skill::orderBy('skill_name', 'asc')->get();
       $defaultSkill = $skills->firstWhere('skill_name', "'Java'") ?? $skills->first();
$selectedSkillId = $request->input('skill_id', $defaultSkill?->id_skill);

        $skillDetails = null;
        $jobsCount = 0;
        $relatedJobs = collect();
        $relatedDomains = collect();
        $topCompanies = collect();
        $similarSkills = collect();
        
        // إحصائيات عامة
        $totalAllJobs = DB::table('job_offers')->count();

        if ($selectedSkillId) {
            $skillDetails = Skill::find($selectedSkillId);

            if ($skillDetails) {
                // 1. عدد العروض
                $jobsCount = DB::table('job_skill')->where('id_skill', $selectedSkillId)->count();

                // 2. المهن المطلوبة
                $relatedJobs = DB::table('job_offers')
                    ->join('job_skill', 'job_offers.id_job', '=', 'job_skill.id_job')
                    ->select('job_offers.title', DB::raw('count(*) as total'))
                    ->where('job_skill.id_skill', $selectedSkillId)
                    ->groupBy('job_offers.title')
                    ->orderBy('total', 'desc')->take(5)->get();

                // 3. القطاعات
                $relatedDomains = DB::table('job_offers')
                    ->join('job_skill', 'job_offers.id_job', '=', 'job_skill.id_job')
                    ->join('domains', 'job_offers.id_domain', '=', 'domains.id_domain')
                    ->select('domains.domain_name', DB::raw('count(*) as total'))
                    ->where('job_skill.id_skill', $selectedSkillId)
                    ->groupBy('domains.id_domain', 'domains.domain_name')
                    ->orderBy('total', 'desc')->take(5)->get();

                // 4. توب الشركات اللي طالبين المهارة
                $topCompanies = DB::table('job_offers')
                    ->join('job_skill', 'job_offers.id_job', '=', 'job_skill.id_job')
                    ->select('job_offers.company', DB::raw('count(*) as total'))
                    ->where('job_skill.id_skill', $selectedSkillId)
                    ->whereNotNull('job_offers.company')
                    ->groupBy('job_offers.company')
                    ->orderBy('total', 'desc')->take(4)->get();

                // 5. خوارزمية المهارات المصاحبة (Skills Similaires)
                // كنقلبو على كاع العروض اللي فيهم المهارة الحالية، وكنشوفو شنو المهارات لخرين لي معاهم
                $jobIdsWithSkill = DB::table('job_skill')
                    ->where('id_skill', $selectedSkillId)
                    ->pluck('id_job');

                if ($jobIdsWithSkill->isNotEmpty()) {
                    $similarSkills = DB::table('job_skill')
                        ->join('skills', 'job_skill.id_skill', '=', 'skills.id_skill')
                        ->select('skills.skill_name', DB::raw('count(*) as total'))
                        ->whereIn('job_skill.id_job', $jobIdsWithSkill)
                        ->where('job_skill.id_skill', '!=', $selectedSkillId) // حيد المهارة الحالية
                        ->groupBy('skills.id_skill', 'skills.skill_name')
                        ->orderBy('total', 'desc')->take(5)->get();
                }
            }
        }

        // حساب النسبة المئوية والتصنيف
        $percentage = $totalAllJobs > 0 ? round(($jobsCount / $totalAllJobs) * 100, 1) : 0;
        
        if ($percentage > 25) { $demandLevel = 'Très Forte'; $stars = 5; $difficulty = 'Hard'; }
        elseif ($percentage > 10) { $demandLevel = 'Forte'; $stars = 4; $difficulty = 'Medium'; }
        elseif ($percentage > 5) { $demandLevel = 'Moyenne'; $stars = 3; $difficulty = 'Medium'; }
        else { $demandLevel = 'Faible'; $stars = 2; $difficulty = 'Easy'; }

        return view('skills_catalog', compact(
            'skills', 'skillDetails', 'jobsCount', 'relatedJobs', 
            'relatedDomains', 'selectedSkillId', 'topCompanies', 
            'similarSkills', 'percentage', 'demandLevel', 'stars', 'difficulty'
        ));
    }
}