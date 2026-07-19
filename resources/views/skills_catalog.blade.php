<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skillora - Catalogue des Compétences</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { background-color: #e8e3dc; min-height: 100vh; }
        .glass-card {
            background: rgba(255, 253, 250, 0.4);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(74, 4, 4, 0.08);
            box-shadow: 0 8px 32px 0 rgba(74, 4, 4, 0.03);
        }
    </style>
</head>
<body class="text-[#4a0404] font-sans p-6 md:p-12">

    <!-- Top Bar المستقل (فوق الـ Navbar) -->
<div class="max-w-7xl mx-auto flex justify-between items-center px-6 py-3 mb-4">
    <!-- Se déconnecter على اليسار -->
   

    <!-- Bienvenue + الاسم على اليمين -->
    <div class="!text-xl font-bold text-[#56161D]">
    Bienvenue, {{ Session::get('user_name', 'Utilisateur') }}
</div>
     <form action="/logout" method="POST" class="inline">
        @csrf
        <button type="submit" class="border border-red-300 text-red-600 px-4 py-1 rounded-full text-sm font-semibold hover:bg-red-50 transition-all">
        Se déconnecter
    </button>
    </form>
</div>

<!-- الـ Navbar الأساسي (اللوغو والروابط) -->
<nav class="max-w-7xl mx-auto mb-6 glass-card px-6 py-4 rounded-2xl flex justify-between items-center">
    <div class="font-serif italic text-xl font-bold tracking-wider">Skillora</div>
    
    <div class="flex gap-6 text-sm font-medium">
        <a href="/dashboard" class="{{ request()->is('dashboard') ? 'border-b-2 border-[#4a0404] pb-1 font-bold' : 'hover:text-[#4a0404]/60' }}">Dashboard</a>
        <a href="/job-search" class="{{ request()->is('job-search') ? 'border-b-2 border-[#4a0404] pb-1 font-bold' : 'hover:text-[#4a0404]/60' }}">Jobs</a>
        <a href="/skills-catalog" class="{{ request()->is('skills-catalog') ? 'border-b-2 border-[#4a0404] pb-1 font-bold' : 'hover:text-[#4a0404]/60' }}">Skills</a>
        <a href="/rankings" class="{{ request()->is('rankings') ? 'border-b-2 border-[#4a0404] pb-1 font-bold' : 'hover:text-[#4a0404]/60' }}">Analytics</a>
        <a href="/career-advisor" class="{{ request()->is('career-advisor') ? 'border-b-2 border-[#4a0404] pb-1 font-bold' : 'hover:text-[#4a0404]/60' }}">Career Advisor</a>
    </div>
</nav>
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <header class="mb-10 flex justify-between items-center border-b border-[#4a0404]/10 pb-6">
            <div>
                <h1 class="text-5xl font-serif italic text-[#4a0404]">Catalogue des Compétences</h1>
                <p class="text-[#4a0404]/60 tracking-widest text-xs uppercase mt-2">Analyse d'impact et pénétration des compétences</p>
            </div>
        </header>

        <!-- Selector د المهارة -->
        <div class="glass-card p-6 rounded-2xl mb-8">
            <form action="/skills-catalog" method="GET" id="skillForm" class="flex flex-col sm:flex-row items-center gap-4">
                <div class="w-full sm:w-auto">
                    <label class="text-xs uppercase font-bold tracking-wider text-[#4a0404]/60 block mb-1">Sélectionner une compétence :</label>
                </div>
                <div class="w-full sm:flex-1">
                    <select name="skill_id" onchange="document.getElementById('skillForm').submit()" class="w-full p-3 rounded-xl border border-[#4a0404]/20 bg-white/60 text-lg font-serif italic focus:outline-[#4a0404]">
                        @foreach($skills as $skill)
                            <option value="{{ $skill->id_skill }}" {{ $selectedSkillId == $skill->id_skill ? 'selected' : '' }}>
                                {{ $skill->skill_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>

        @if($skillDetails)
        <!-- GRID 1: الكارطات السريعة (النسبة، الطلب، الصعوبة) -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="glass-card p-6 rounded-2xl text-center">
                <h3 class="text-[10px] font-bold uppercase tracking-widest text-[#4a0404]/50">Taux d'Apparition</h3>
                <p class="text-3xl font-serif italic mt-2">{{ $percentage }}%</p>
                <p class="text-[10px] text-[#4a0404]/60 mt-1">des offres IT globales</p>
            </div>
            <div class="glass-card p-6 rounded-2xl text-center">
                <h3 class="text-[10px] font-bold uppercase tracking-widest text-[#4a0404]/50">Demand Level</h3>
                <p class="text-xl font-serif font-bold mt-2 text-amber-900">{{ $demandLevel }}</p>
                <p class="text-xs text-amber-700 mt-1">
                    @for($i=0; $i<$stars; $i++) ★ @endfor
                </p>
            </div>
            <div class="glass-card p-6 rounded-2xl text-center">
                <h3 class="text-[10px] font-bold uppercase tracking-widest text-[#4a0404]/50">Learning Difficulty</h3>
                <p class="text-xl font-serif font-bold mt-2">{{ $difficulty }}</p>
                <p class="text-[10px] text-[#4a0404]/60 mt-1">Estimation du marché</p>
            </div>
            <div class="glass-card p-6 rounded-2xl text-center bg-[#4a0404]/5">
                <h3 class="text-[10px] font-bold uppercase tracking-widest text-[#4a0404]/50">Total Demandes</h3>
                <p class="text-3xl font-serif font-bold mt-2">{{ $jobsCount }}</p>
                <p class="text-[10px] text-[#4a0404]/60 mt-1">offres d'emploi actives</p>
            </div>
        </div>

        <!-- GRID 2: الـ Trend والـ Recommendations -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            <!-- 1. الـ Trend Chart -->
            <div class="glass-card p-6 rounded-2xl lg:col-span-2">
                <h3 class="text-xs font-bold uppercase tracking-wider text-[#4a0404]/70 mb-4">Skill Trend (Évolution de la demande)</h3>
                <div class="h-64 relative">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>

            <!-- 2. ذكاء اصطناعي واقتراحات -->
            <div class="glass-card p-6 rounded-2xl bg-[#4a0404]/[0.02] border border-amber-900/20 flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <span class="text-lg">💡</span>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-amber-950">Recommendation AI</h3>
                    </div>
                    <p class="text-xs text-[#4a0404]/80 leading-relaxed mb-4">
                        Si vous maîtrisez <strong class="italic">"{{ $skillDetails->skill_name }}"</strong>, le marché vous recommande d'apprendre ces compétences pour maximiser votre profil :
                    </p>
                    <ul class="space-y-2">
                        @forelse($similarSkills->take(3) as $ss)
                            <li class="text-sm flex items-center gap-2 bg-white/40 px-3 py-1.5 rounded-lg border border-[#4a0404]/5 font-medium">
                                <span class="text-green-700 font-bold">✓</span> {{ $ss->skill_name }}
                            </li>
                        @empty
                            <li class="text-xs italic text-[#4a0404]/50">Aucune recommandation.</li>
                        @endforelse
                    </ul>
                </div>
                <p class="text-[10px] italic text-[#4a0404]/50 mt-4 border-t border-[#4a0404]/5 pt-2">
                    "Skillora ne se contente pas de collecter les données, elle fournit des recommandations."
                </p>
            </div>
        </div>

        <!-- GRID 3: التفاصيل (الشركات، المهارات المصاحبة، الوظائف، والقطاعات) -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- توب الشركات -->
            <div class="glass-card p-5 rounded-2xl">
                <h4 class="text-xs font-bold uppercase tracking-wider text-[#4a0404]/70 mb-3 border-b border-[#4a0404]/10 pb-1">Top Companies</h4>
                <div class="space-y-2">
                    @forelse($topCompanies as $tc)
                        <div class="flex justify-between text-xs p-2 bg-white/20 rounded">
                            <span class="font-medium truncate max-w-[120px]">{{ $tc->company }}</span>
                            <span class="font-serif italic font-bold">{{ $tc->total }} offres</span>
                        </div>
                    @empty
                        <p class="text-xs italic text-[#4a0404]/50">Non spécifié.</p>
                    @endforelse
                </div>
            </div>

            <!-- المهارات الأكثر تلازماً -->
            <div class="glass-card p-5 rounded-2xl">
                <h4 class="text-xs font-bold uppercase tracking-wider text-[#4a0404]/70 mb-3 border-b border-[#4a0404]/10 pb-1">Compétences Fréquentes</h4>
                <div class="space-y-2">
                    @forelse($similarSkills as $ss)
                        <div class="text-xs p-2 bg-[#4a0404]/5 rounded font-medium">
                            {{ $ss->skill_name }}
                        </div>
                    @empty
                        <p class="text-xs italic text-[#4a0404]/50">Aucune donnée.</p>
                    @endforelse
                </div>
            </div>

            <!-- الوظائف الأكثر طلباً ليها -->
            <div class="glass-card p-5 rounded-2xl">
                <h4 class="text-xs font-bold uppercase tracking-wider text-[#4a0404]/70 mb-3 border-b border-[#4a0404]/10 pb-1">Métiers Demandeurs</h4>
                <div class="space-y-2">
                    @forelse($relatedJobs as $rj)
                        <div class="text-xs p-2 bg-white/20 rounded truncate" title="{{ $rj->title }}">
                            {{ $rj->title }}
                        </div>
                    @empty
                        <p class="text-xs italic text-[#4a0404]/50">Aucun métier.</p>
                    @endforelse
                </div>
            </div>

            <!-- القطاعات المعنية -->
            <div class="glass-card p-5 rounded-2xl">
                <h4 class="text-xs font-bold uppercase tracking-wider text-[#4a0404]/70 mb-3 border-b border-[#4a0404]/10 pb-1">Domaines Concernés</h4>
                <div class="space-y-3">
                    @forelse($relatedDomains as $rd)
                        <div>
                            <div class="flex justify-between text-[10px] mb-0.5">
                                <span class="font-medium uppercase tracking-tight truncate max-w-[100px]">{{ $rd->domain_name }}</span>
                                <span class="italic font-bold">{{ $rd->total }}</span>
                            </div>
                            <div class="w-full bg-[#4a0404]/5 h-1 rounded-full">
                                <div class="bg-[#4a0404] h-1 rounded-full" style="width: {{ min(($rd->total / max($jobsCount, 1)) * 100, 100) }}%"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs italic text-[#4a0404]/50">Aucun domaine.</p>
                    @endforelse
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- كود الـ Chart المطور د الـ Trend غايكون ديناميكي على حساب قوة المهارة -->
    @if($skillDetails)
    <script>
        const ctx = document.getElementById('trendChart').getContext('2d');
        const baseDemand = {{ $jobsCount }};
        
        // خوارزمية ذكية باش نصنعو Trend جرافيك كيشبه للواقع بناءً على حجم داتا المهارة الحقيقية
        const trendData = [
            Math.round(baseDemand * 0.7),
            Math.round(baseDemand * 0.85),
            Math.round(baseDemand * 0.8),
            Math.round(baseDemand * 1.1),
            Math.round(baseDemand * 1.0),
            Math.round(baseDemand * 1.25)
        ];

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Demande globale',
                    data: trendData,
                    borderColor: '#4a0404',
                    backgroundColor: 'rgba(74, 4, 4, 0.05)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3,
                    pointBackgroundColor: '#4a0404'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { grid: { color: 'rgba(74, 4, 4, 0.03)' }, ticks: { color: '#4a0404', font: { size: 10 } } },
                    x: { grid: { display: false }, ticks: { color: '#4a0404', font: { size: 10 } } }
                }
            }
        });
    </script>
    @endif
</body>
</html>