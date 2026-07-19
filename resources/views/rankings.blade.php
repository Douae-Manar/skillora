<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skillora - Analytics & Rankings</title>
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
                <h1 class="text-5xl font-serif italic text-[#4a0404]">Analytics & Rankings</h1>
                <p class="text-[#4a0404]/60 tracking-widest text-xs uppercase mt-2">Analyses graphiques et comparaisons globales du marché</p>
            </div>
            
        </header>

        <!-- GRID 1: Top Métiers & Top Compétences -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- 1. Top Métiers Chart -->
            <div class="glass-card p-6 rounded-2xl">
                <h3 class="text-xs font-bold uppercase tracking-wider text-[#4a0404]/70 mb-4">🔥 Top Métiers les plus demandés</h3>
                <div class="h-64 relative">
                    <canvas id="jobsChart"></canvas>
                </div>
            </div>

            <!-- 2. Top Compétences Chart -->
            <div class="glass-card p-6 rounded-2xl">
                <h3 class="text-xs font-bold uppercase tracking-wider text-[#4a0404]/70 mb-4">🚀 Top Compétences clés requises</h3>
                <div class="h-64 relative">
                    <canvas id="skillsChart"></canvas>
                </div>
            </div>
        </div>

        <!-- GRID 2: Répartition & Comparaison des domaines -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- الدائرة د التوزيع د القطاعات -->
            <div class="glass-card p-6 rounded-2xl lg:col-span-1">
                <h3 class="text-xs font-bold uppercase tracking-wider text-[#4a0404]/70 mb-4">📊 Répartition des domaines</h3>
                <div class="h-64 relative flex justify-center">
                    <canvas id="domainsPieChart"></canvas>
                </div>
            </div>

            <!-- جدول المقارنة التفصيلي بين القطاعات -->
            <div class="glass-card p-6 rounded-2xl lg:col-span-2">
                <h3 class="text-xs font-bold uppercase tracking-wider text-[#4a0404]/70 mb-4">⚖️ Comparaison analytique entre domaines</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-[#4a0404]">
                        <thead>
                            <tr class="border-b border-[#4a0404]/10 text-xs uppercase tracking-wider opacity-60">
                                <th class="pb-3">Domaine / Secteur</th>
                                <th class="pb-3 text-right">Volume d'offres</th>
                                <th class="pb-3 text-right">Part de marché</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#4a0404]/5">
                            @php $totalJobsSum = $domainDistribution->sum('total'); @endphp
                            @foreach($domainDistribution as $dd)
                                <tr>
                                    <td class="py-3 font-serif italic font-bold uppercase text-xs">{{ $dd->domain_name }}</td>
                                    <td class="py-3 text-right font-medium">{{ $dd->total }}</td>
                                    <td class="py-3 text-right text-xs bg-[#4a0404]/5 px-2 rounded-md font-bold">
                                        {{ $totalJobsSum > 0 ? round(($dd->total / $totalJobsSum) * 100, 1) : 0 }}%
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript للـ Charts التفاعلية -->
    <script>
        // الألوان المتناسقة مع الـ Theme ديالك
        const colors = ['#4a0404', '#6b1d1d', '#8c3636', '#ad5050', '#ce6a6a', '#ef8484'];

        // 1. Top Jobs Chart (Horizontal Bar)
        const jobsCtx = document.getElementById('jobsChart').getContext('2d');
        new Chart(jobsCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($topJobs->pluck('title')) !!},
                datasets: [{
                    data: {!! json_encode($topJobs->pluck('total')) !!},
                    backgroundColor: colors[0],
                    borderRadius: 8
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { x: { grid: { display: false } }, y: { ticks: { font: { size: 10 } } } }
            }
        });

        // 2. Top Skills Chart (Vertical Bar)
        const skillsCtx = document.getElementById('skillsChart').getContext('2d');
        new Chart(skillsCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($topSkills->pluck('skill_name')) !!},
                datasets: [{
                    data: {!! json_encode($topSkills->pluck('total')) !!},
                    backgroundColor: colors[2],
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { grid: { color: 'rgba(74,4,4,0.03)' } }, x: { ticks: { font: { size: 10 } } } }
            }
        });

        // 3. Domains Pie Chart (Doughnut / Pie)
        const domainsCtx = document.getElementById('domainsPieChart').getContext('2d');
        new Chart(domainsCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($domainDistribution->pluck('domain_name')) !!},
                datasets: [{
                    data: {!! json_encode($domainDistribution->pluck('total')) !!},
                    backgroundColor: colors
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 9 } } } }
            }
        });
    </script>
</body>
</html>