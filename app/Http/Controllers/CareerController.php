<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class CareerController extends Controller
{
    public function index(Request $request)
    {
        $allTitles = DB::table('job_offers')->whereNotNull('title')->distinct()->orderBy('title', 'asc')->pluck('title');
        $allSkills = DB::table('skills')->orderBy('skill_name', 'asc')->get();

        $selectedTitle = $request->input('title');
        $selectedSkillId = $request->input('skill_id');

        // متغيرات الـ Métier
        $jobStats = null; $mandatorySkills = collect(); $recommendedSkills = collect(); $similarJobs = collect();
        $softSkills = collect(['Communication', 'Teamwork', 'Problem Solving', 'Adaptability']);
        
        // متغيرات الـ Skill الجداد
        $skillStats = null; $skillTopJobs = collect(); $skillRelatedSkills = collect(); $skillCompanies = collect();

        $totalAllJobs = DB::table('job_offers')->count();

        // [1] إيلا اختار الـ Métier
        if ($selectedTitle) {
            $jobsCount = DB::table('job_offers')->where('title', $selectedTitle)->count();
            $domainRow = DB::table('job_offers')->join('domains', 'job_offers.id_domain', '=', 'domains.id_domain')->where('job_offers.title', $selectedTitle)->select('domains.domain_name')->first();
            $domainName = $domainRow ? $domainRow->domain_name : 'IT / Tech';

            $jobPercentage = $totalAllJobs > 0 ? ($jobsCount / $totalAllJobs) * 100 : 0;
            if ($jobPercentage > 15) { $demandLevel = 'Très élevé 🔥'; $growth = '+18%'; $progress = 85; }
            elseif ($jobPercentage > 5) { $demandLevel = 'Élevé ⚡'; $growth = '+12%'; $progress = 70; }
            else { $demandLevel = 'Modéré 📈'; $growth = '+6%'; $progress = 50; }

            $jobStats = ['count' => $jobsCount, 'demand' => $demandLevel, 'domain' => $domainName, 'growth' => $growth, 'progress' => $progress];

            $allJobSkills = DB::table('job_offers')->join('job_skill', 'job_offers.id_job', '=', 'job_skill.id_job')->join('skills', 'job_skill.id_skill', '=', 'skills.id_skill')->where('job_offers.title', $selectedTitle)->select('skills.skill_name', DB::raw('count(*) as total'))->groupBy('skills.id_skill', 'skills.skill_name')->orderBy('total', 'desc')->get();
            $mandatorySkills = $allJobSkills->take(4)->map(function($item, $key) { $item->stars = 5 - $key; return $item; });
            $recommendedSkills = $allJobSkills->slice(4, 3);

            $similarJobs = DB::table('job_offers')->where('id_domain', function($query) use ($selectedTitle) { $query->select('id_domain')->from('job_offers')->where('title', $selectedTitle)->limit(1); })->where('title', '!=', $selectedTitle)->select('title', DB::raw('count(*) as total'))->groupBy('title')->orderBy('total', 'desc')->take(4)->pluck('title');
        }

        // [2] إيلا اختار الـ Skill (هنا فين تزداد الإبداع الحقيقي)
        if ($selectedSkillId) {
            $currentSkill = DB::table('skills')->where('id_skill', $selectedSkillId)->first();
            
            if ($currentSkill) {
                // عدد العروض المطلوبة فيها
                $skillJobsCount = DB::table('job_skill')->where('id_skill', $selectedSkillId)->count();
                $skillPercentage = $totalAllJobs > 0 ? ($skillJobsCount / $totalAllJobs) * 100 : 0;

                if ($skillPercentage > 20) { $score = '95/100 (Critique)'; $priority = 'Immédiate (Top 1) ⚡'; $impact = '+25% de valeur'; }
                elseif ($skillPercentage > 8) { $score = '75/100 (Élevé)'; $priority = 'Haute priorité 🎯'; $impact = '+15% de valeur'; }
                else { $score = '45/100 (Modéré)'; $priority = 'Recommandée 💡'; $impact = '+8% de valeur'; }

                $skillStats = [
                    'name' => $currentSkill->skill_name,
                    'count' => $skillJobsCount,
                    'score' => $score,
                    'priority' => $priority,
                    'impact' => $impact
                ];

                // Top métiers طالبينها
                $skillTopJobs = DB::table('job_offers')->join('job_skill', 'job_offers.id_job', '=', 'job_skill.id_job')->where('job_skill.id_skill', $selectedSkillId)->select('job_offers.title', DB::raw('count(*) as total'))->groupBy('job_offers.title')->orderBy('total', 'desc')->take(4)->get();

                // Top companies طالبينها
                $skillCompanies = DB::table('job_offers')->join('job_skill', 'job_offers.id_job', '=', 'job_skill.id_job')->where('job_skill.id_skill', $selectedSkillId)->whereNotNull('job_offers.company')->select('job_offers.company', DB::raw('count(*) as total'))->groupBy('job_offers.company')->orderBy('total', 'desc')->take(4)->get();

                // Related skills (المهارات المصاحبة ليها ف نفس العروض)
                $jobIds = DB::table('job_skill')->where('id_skill', $selectedSkillId)->pluck('id_job');
                if ($jobIds->isNotEmpty()) {
                    $skillRelatedSkills = DB::table('job_skill')->join('skills', 'job_skill.id_skill', '=', 'skills.id_skill')->whereIn('job_skill.id_job', $jobIds)->where('job_skill.id_skill', '!=', $selectedSkillId)->select('skills.skill_name', DB::raw('count(*) as total'))->groupBy('skills.id_skill', 'skills.skill_name')->orderBy('total', 'desc')->take(4)->pluck('skills.skill_name');
                }
            }
        }

        return view('career_advisor', compact(
            'allTitles', 'allSkills', 'selectedTitle', 'selectedSkillId', 
            'jobStats', 'mandatorySkills', 'recommendedSkills', 'similarJobs', 'softSkills',
            'skillStats', 'skillTopJobs', 'skillRelatedSkills', 'skillCompanies'
        ));
    }
}