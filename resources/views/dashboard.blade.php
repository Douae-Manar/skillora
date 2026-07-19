<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skillora - Deep Analytics Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-image: linear-gradient(rgba(232, 227, 220, 0.92), rgba(232, 227, 220, 0.92)), url('your-image-url-here.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
        }
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
        <!-- Header -->
<header class="mb-10 flex justify-between items-center border-b border-[#4a0404]/10 pb-6">
    <div>
        @if(request()->is('dashboard'))
            <h1 class="text-5xl font-serif italic text-[#4a0404]">Dashboard</h1>
            <p class="text-[#4a0404]/60 tracking-widest text-xs uppercase mt-2">Analyse Avancée du Marché d'Emploi & Compétences</p>
        @else
            <h1 class="text-5xl font-serif italic text-[#4a0404]">Offres d'Emploi</h1>
            <p class="text-[#4a0404]/60 tracking-widest text-xs uppercase mt-2">Trouvez votre opportunité idéale</p>
        @endif
    </div>
    
    <!-- هنا تبدلات Live Data بسمية الـ User -->
    
</header>

        <!-- 1. Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div class="glass-card p-6 rounded-2xl">
                <h3 class="text-xs font-bold uppercase tracking-widest text-[#4a0404]/50">Total Offres d'Emploi</h3>
                <p class="text-4xl font-light font-serif mt-2">{{ $totalJobs }}</p>
            </div>
            <div class="glass-card p-6 rounded-2xl">
                <h3 class="text-xs font-bold uppercase tracking-widest text-[#4a0404]/50">Secteurs & Domains</h3>
                <p class="text-4xl font-light font-serif mt-2">{{ $totalDomains }}</p>
            </div>
            <div class="glass-card p-6 rounded-2xl">
                <h3 class="text-xs font-bold uppercase tracking-widest text-[#4a0404]/50">Compétences Clés</h3>
                <p class="text-4xl font-light font-serif mt-2">{{ $totalSkills }}</p>
            </div>
        </div>

        <!-- 2. Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
            <div class="glass-card p-6 rounded-2xl">
                <h2 class="text-xs font-bold uppercase tracking-wider mb-6 text-[#4a0404]/70">Top 5 Secteurs les plus Demandés</h2>
                <div class="relative h-80">
                    <canvas id="domainsChart"></canvas>
                </div>
            </div>
            <div class="glass-card p-6 rounded-2xl">
                <h2 class="text-xs font-bold uppercase tracking-wider mb-6 text-[#4a0404]/70">Top 7 Compétences Clés</h2>
                <div class="relative h-80 flex justify-center">
                    <canvas id="skillsChart"></canvas>
                </div>
            </div>
        </div>

        <!-- 3. Advanced Insights Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
            <!-- فكرة 1: تنوع الوظائف ف كل قطاع -->
            <div class="glass-card p-6 rounded-2xl">
                <h2 class="text-xs font-bold uppercase tracking-wider mb-4 text-[#4a0404]/70">Indice de Diversité des Postes</h2>
                <div class="space-y-4 mt-2">
                    @foreach($diversityData as $item)
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="font-medium">{{ $item->domain_name }}</span>
                            <span class="text-xs italic">{{ $item->unique_titles }} métiers distincts</span>
                        </div>
                        <div class="w-full bg-[#4a0404]/5 h-2 rounded-full">
                            <div class="bg-[#4a0404] h-2 rounded-full" style="width: {{ min(($item->unique_titles / max($totalJobs, 1)) * 100 + 30, 100) }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- فكرة 2: المهارة الأكثر طلباً ف كل قطاع -->
            <div class="glass-card p-6 rounded-2xl">
                <h2 class="text-xs font-bold uppercase tracking-wider mb-4 text-[#4a0404]/70">Matrice des Compétences par Secteur</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-2">
                    @foreach($skillsPerDomain as $sd)
                    <div class="p-4 bg-white/20 rounded-xl border border-[#4a0404]/5">
                        <p class="text-xs uppercase tracking-wider text-[#4a0404]/50 font-bold">{{ $sd->domain_name }}</p>
                        <p class="text-lg font-serif italic mt-1 text-[#4a0404]">{{ $sd->skill_name }}</p>
                        <p class="text-xs text-[#4a0404]/60 mt-1">Top dominante</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- 4. فكرة 3: جدول أحدث الوظائف لتبان الداتا حية -->
        <div class="glass-card p-6 rounded-2xl">
            <h2 class="text-xs font-bold uppercase tracking-wider mb-4 text-[#4a0404]/70">Aperçu des Dernières Offres Analysées</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-[#4a0404]/10 text-xs uppercase tracking-wider text-[#4a0404]/60">
                            <th class="py-3 px-4">Intitulé du Poste</th>
                            <th class="py-3 px-4">Secteur / Domaine</th>
                            <th class="py-3 px-4">Description Extrait</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-[#4a0404]/5">
                        @foreach($latestOffers as $offer)
                        <tr>
                            <td class="py-3 px-4 font-medium">{{ $offer->title }}</td>
                            <td class="py-3 px-4 text-xs"><span class="px-2 py-1 bg-[#4a0404]/10 rounded-md">{{ $offer->domain->domain_name ?? 'General' }}</span></td>
                            <td class="py-3 px-4 text-[#4a0404]/70 italic text-xs truncate max-w-xs">{{ Str::limit($offer->description, 60) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Script Chart.js -->
    <script>
        const domainsLabels = {!! json_encode($domainsData->pluck('domain_name')) !!};
        const domainsTotals = {!! json_encode($domainsData->pluck('total')) !!};
        const skillsLabels = {!! json_encode($skillsData->pluck('skill_name')) !!};
        const skillsTotals = {!! json_encode($skillsData->pluck('total')) !!};

        new Chart(document.getElementById('domainsChart'), {
            type: 'bar',
            data: {
                labels: domainsLabels,
                datasets: [{ data: domainsTotals, backgroundColor: '#4a0404', borderRadius: 4 }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { grid: { color: 'rgba(74, 4, 4, 0.05)' }, ticks: { color: '#4a0404' } },
                    x: { grid: { display: false }, ticks: { color: '#4a0404' } }
                }
            }
        });

        new Chart(document.getElementById('skillsChart'), {
            type: 'doughnut',
            data: {
                labels: skillsLabels,
                datasets: [{
                    data: skillsTotals,
                    backgroundColor: ['#4a0404', '#610f14', '#781f25', '#94363c', '#ad5156', '#c77176', '#dec3c5'],
                    borderWidth: 3,
                    borderColor: 'rgba(255, 253, 250, 0.5)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'right', labels: { color: '#4a0404', font: { size: 11 } } } }
            }
        });
    </script>
</body>
</html>