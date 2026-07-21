<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skillora - Career Advisor</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
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
<body class="text-[#4a0404] font-sans p-4 sm:p-6 md:p-12">

    <!-- Top Bar المستقل متجاوب -->
<div class="max-w-7xl mx-auto flex flex-col sm:flex-row justify-between items-center gap-4 px-4 sm:px-8 py-4 mb-4">
    <!-- Bienvenue -->
    <div class="text-lg sm:text-xl font-bold text-[#56161D] text-center sm:text-left">
        Bienvenue, {{ Session::get('user_name', 'Utilisateur') }}
    </div>
    <!-- Se déconnecter -->
    <form action="/logout" method="POST" class="inline">
        @csrf
       <button type="submit" class="border border-red-300 text-red-600 px-4 py-1 rounded-full text-sm font-semibold hover:bg-red-50 transition-all">
        Se déconnecter
    </button>
    </form>
</div>

<!-- الـ Navbar الأساسي المتجاوب -->
<nav class="max-w-7xl mx-auto mb-6 glass-card px-4 sm:px-6 py-4 rounded-2xl flex flex-col lg:flex-row justify-between items-center gap-4">
    <div class="font-serif italic text-xl font-bold tracking-wider">Skillora</div>
    
    <div class="flex flex-wrap justify-center gap-3 sm:gap-6 text-xs sm:text-sm font-medium">
        <a href="/dashboard" class="{{ request()->is('dashboard') ? 'border-b-2 border-[#4a0404] pb-1 font-bold' : 'hover:text-[#4a0404]/60' }}">Dashboard</a>
        <a href="/job-search" class="{{ request()->is('job-search') ? 'border-b-2 border-[#4a0404] pb-1 font-bold' : 'hover:text-[#4a0404]/60' }}">Jobs</a>
        <a href="/skills-catalog" class="{{ request()->is('skills-catalog') ? 'border-b-2 border-[#4a0404] pb-1 font-bold' : 'hover:text-[#4a0404]/60' }}">Skills</a>
        <a href="/rankings" class="{{ request()->is('rankings') ? 'border-b-2 border-[#4a0404] pb-1 font-bold' : 'hover:text-[#4a0404]/60' }}">Analytics</a>
        <a href="/career-advisor" class="{{ request()->is('career-advisor') ? 'border-b-2 border-[#4a0404] pb-1 font-bold' : 'hover:text-[#4a0404]/60' }}">Career Advisor</a>
    </div>
</nav>

    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <header class="mb-10 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 border-b border-[#4a0404]/10 pb-6">
            <div>
                <h1 class="text-3xl sm:text-5xl font-serif italic text-[#4a0404]">🎯 Career Advisor</h1>
                <p class="text-[#4a0404]/60 tracking-widest text-[10px] sm:text-xs uppercase mt-2">Analyses bidirectionnelles et stratégiques</p>
            </div>
        </header>

        <!-- الاختيارات -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
            <div class="glass-card p-6 rounded-2xl">
                <form action="/career-advisor" method="GET">
                    <label class="text-xs uppercase font-bold tracking-wider text-[#4a0404]/70 block mb-2">💼 Analyser un Métier :</label>
                    <select name="title" onchange="this.form.submit()" class="w-full p-3 rounded-xl border border-[#4a0404]/20 bg-white/60 font-serif italic focus:outline-[#4a0404]">
                        <option value="">-- Choisir un métier --</option>
                        @foreach($allTitles as $title)
                            <option value="{{ $title }}" {{ $selectedTitle == $title ? 'selected' : '' }}>{{ $title }}</option>
                        @endforeach
                    </select>
                </form>
            </div>

            <div class="glass-card p-6 rounded-2xl">
                <form action="/career-advisor" method="GET">
                    <label class="text-xs uppercase font-bold tracking-wider text-[#4a0404]/70 block mb-2">🛠️ Analyser une Compétence (Skill) :</label>
                    <select name="skill_id" onchange="this.form.submit()" class="w-full p-3 rounded-xl border border-[#4a0404]/20 bg-white/60 font-serif italic focus:outline-[#4a0404]">
                        <option value="">-- Choisir une compétence --</option>
                        @foreach($allSkills as $skill)
                            <option value="{{ $skill->id_skill }}" {{ $selectedSkillId == $skill->id_skill ? 'selected' : '' }}>{{ $skill->skill_name }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>

        <!-- ==================== [1] تحليلات الـ MÉTIER ==================== -->
        @if($selectedTitle && $jobStats)
        <div class="space-y-8 animate-fade-in">
            <div class="glass-card p-6 sm:p-8 rounded-3xl border-l-4 border-[#4a0404] bg-white/50">
                <h2 class="text-2xl sm:text-3xl font-serif italic font-bold">🎯 {{ $selectedTitle }}</h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-6 mt-4 text-xs">
                    <div><p class="opacity-50 font-bold uppercase">Offres</p><p class="text-xl font-bold mt-1">{{ $jobStats['count'] }}</p></div>
                    <div><p class="opacity-50 font-bold uppercase">Demande</p><p class="text-sm font-bold text-amber-900 mt-1">{{ $jobStats['demand'] }}</p></div>
                    <div><p class="opacity-50 font-bold uppercase">Secteur</p><p class="font-medium mt-1 uppercase">{{ $jobStats['domain'] }}</p></div>
                    <div><p class="opacity-50 font-bold uppercase">Salaire Moyen</p><p class="font-bold text-green-800 mt-1">38 000 DH</p></div>
                    <div><p class="opacity-50 font-bold uppercase">Croissance</p><p class="text-green-700 font-bold mt-1">{{ $jobStats['growth'] }}</p></div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="glass-card p-6 rounded-2xl lg:col-span-2 overflow-x-auto">
                    <h3 class="text-xs font-bold uppercase tracking-wider mb-4 border-b border-[#4a0404]/10 pb-2">🔥 2. Les compétences obligatoires</h3>
                    <table class="w-full text-left text-xs min-w-[300px]">
                        <tbody class="divide-y divide-[#4a0404]/5">
                            @foreach($mandatorySkills as $ms)
                                <tr><td class="py-3 font-semibold">{{ $ms->skill_name }}</td><td class="py-3 text-right text-amber-600">@for($i=0; $i<$ms->stars; $i++) ★ @endfor</td></tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="glass-card p-6 rounded-2xl">
                    <h3 class="text-xs font-bold uppercase tracking-wider mb-4 border-b border-[#4a0404]/10 pb-2">✨ 3. Recommended Skills</h3>
                    <div class="space-y-2">
                        @foreach($recommendedSkills as $rs) <div class="text-xs font-medium bg-white/40 p-2 rounded-xl">💡 {{ $rs->skill_name }}</div> @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- ==================== [2] تحليلات الـ SKILL الحية ==================== -->
        @if($selectedSkillId && $skillStats)
        <div class="space-y-8">
            <!-- البطاقة الكبيرة للمهارة المحددة -->
            <div class="glass-card p-6 sm:p-8 rounded-3xl border-l-4 border-amber-800 bg-white/50">
                <div class="flex items-center gap-3 mb-4">
                    <span class="text-3xl">🛠️</span>
                    <h2 class="text-2xl sm:text-3xl font-serif italic font-bold text-[#4a0404]">{{ $skillStats['name'] }}</h2>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-6 mt-6 text-xs">
                    <div>
                        <p class="opacity-50 font-bold uppercase tracking-widest">Jobs requiring this skill</p>
                        <p class="text-xl sm:text-2xl font-serif font-bold mt-1">{{ $skillStats['count'] }} offres</p>
                    </div>
                    <div>
                        <p class="opacity-50 font-bold uppercase tracking-widest">Demand Score</p>
                        <p class="text-sm font-bold mt-1 text-amber-950">{{ $skillStats['score'] }}</p>
                    </div>
                    <div>
                        <p class="opacity-50 font-bold uppercase tracking-widest">Salary Impact</p>
                        <p class="text-sm font-bold mt-1 text-green-800">{{ $skillStats['impact'] }}</p>
                    </div>
                    <div>
                        <p class="opacity-50 font-bold uppercase tracking-widest">Learning Priority</p>
                        <p class="text-xs font-bold mt-1 bg-amber-900/10 px-2 py-0.5 rounded text-amber-950 inline-block">{{ $skillStats['priority'] }}</p>
                    </div>
                    <div>
                        <p class="opacity-50 font-bold uppercase tracking-widest">Future Trend (2026-2028)</p>
                        <p class="text-sm font-bold text-green-700 mt-1">📈 En hausse continue</p>
                    </div>
                </div>
            </div>

            <!-- التفاصيل ف المجموعات الأربعة السفلية للـ Skill -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- 1. Top Métiers -->
                <div class="glass-card p-6 rounded-2xl">
                    <h3 class="text-xs font-bold uppercase tracking-wider mb-4 border-b border-[#4a0404]/10 pb-2">💼 Top Métiers demandeurs</h3>
                    <div class="space-y-2">
                        @foreach($skillTopJobs as $tj)
                            <div class="text-xs p-2.5 bg-white/40 rounded-xl flex justify-between font-medium">
                                <span class="truncate max-w-[200px]">💼 {{ $tj->title }}</span>
                                <span class="font-serif italic opacity-60 font-bold">{{ $tj->total }} fois</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- 2. Related Skills -->
                <div class="glass-card p-6 rounded-2xl">
                    <h3 class="text-xs font-bold uppercase tracking-wider mb-4 border-b border-[#4a0404]/10 pb-2">🔗 Related Skills (Forte corrélation)</h3>
                    <div class="space-y-2">
                        @forelse($skillRelatedSkills as $rs)
                            <div class="text-xs p-2.5 bg-[#4a0404]/5 rounded-xl font-semibold">
                                ⚡ {{ $rs }}
                            </div>
                        @empty
                            <p class="text-xs italic opacity-50">Aucune donnée.</p>
                        @endforelse
                    </div>
                </div>

                <!-- 3. Top Companies -->
                <div class="glass-card p-6 rounded-2xl">
                    <h3 class="text-xs font-bold uppercase tracking-wider mb-4 border-b border-[#4a0404]/10 pb-2">🏢 Top Companies Recruiting</h3>
                    <div class="space-y-2">
                        @forelse($skillCompanies as $sc)
                            <div class="text-xs p-2.5 bg-white/40 rounded-xl flex justify-between font-medium">
                                <span>🏢 {{ $sc->company }}</span>
                                <span class="text-[10px] bg-[#4a0404]/10 px-2 rounded font-bold">{{ $sc->total }} posts</span>
                            </div>
                        @empty
                            <p class="text-xs italic opacity-50">Non spécifiées.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        @endif

    </div>
</body>
</html>