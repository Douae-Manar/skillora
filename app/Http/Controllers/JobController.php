<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobOffer;
use App\Models\Domain;
use App\Models\Skill;

class JobController extends Controller
{
    public function index(Request $request)
    {
        // 1. جلب خيارات الفلاتر المتوفرة
        $domains = Domain::all();
        $skills = Skill::all();
        $cities = []; // مصفوفة خاوية حيت الـ Model ما كاينش والـ CSV ما فيهش مدن

        // جلب الشركات الفريدة من الداتابيز
        $companies = JobOffer::whereNotNull('company')->distinct()->pluck('company');

        // 2. بناء كويري البحث والفلترة
        $query = JobOffer::with(['domain', 'skills']);

        // فلتر بكلمة البحث (Intitulé du poste)
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // فلتر بالقطاع (Domaine)
        if ($request->filled('domain_id')) {
            $query->where('id_domain', $request->domain_id);
        }

        // فلتر بالشركة (Company)
        if ($request->filled('company')) {
            $query->where('company', $request->company);
        }

        // فلتر بالمهارة (Skill)
        if ($request->filled('skill_id')) {
            $query->whereHas('skills', function($q) use ($request) {
                $q->where('skills.id_skill', $request->skill_id);
            });
        }

        // Pagination: 6 عروض ف كل صفحة مع الحفاظ على الـ Query Params ف الروابط
        $jobs = $query->paginate(6)->withQueryString();

        // عيطنا على الـ view ديريكت بـ 'job_search' حيت محطوط ف الـ root ديال views
        return view('job_search', compact('jobs', 'domains', 'skills', 'cities', 'companies'));
    }
}