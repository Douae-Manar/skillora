<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skillora - Recherche d'Emploi</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        body {
            background-color: #e8e3dc;
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
        .glass-modal {
            background: rgba(255, 253, 250, 0.95);
            backdrop-filter: blur(25px);
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
        @if(request()->is('dashboard'))
            <h1 class="text-5xl font-serif italic text-[#4a0404]">Skillora Dashboard</h1>
            <p class="text-[#4a0404]/60 tracking-widest text-xs uppercase mt-2">Analyse Avancée du Marché d'Emploi & Compétences</p>
        @else
            <h1 class="text-5xl font-serif italic text-[#4a0404]">Offres d'Emploi</h1>
            <p class="text-[#4a0404]/60 tracking-widest text-xs uppercase mt-2">Trouvez votre opportunité idéale</p>
        @endif
    </div>
    
   
</header>

        <!-- Formulaire de Filtres -->
        <form action="/job-search" method="GET" class="glass-card p-6 rounded-2xl mb-10 grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Recherche par mot clé -->
            <div>
                <label class="text-xs uppercase font-bold tracking-wider text-[#4a0404]/60">Recherche</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Poste..." class="w-full mt-1 p-2 rounded-lg border border-[#4a0404]/20 bg-white/50 text-sm focus:outline-[#4a0404]">
            </div>

            <!-- Domaine -->
            <div>
                <label class="text-xs uppercase font-bold tracking-wider text-[#4a0404]/60">Domaine</label>
                <select name="domain_id" class="w-full mt-1 p-2 rounded-lg border border-[#4a0404]/20 bg-white/50 text-sm">
                    <option value="">Tous</option>
                    @foreach($domains as $d)
                        <option value="{{ $d->id_domain }}" {{ request('domain_id') == $d->id_domain ? 'selected' : '' }}>{{ $d->domain_name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Entreprise -->
            <div>
                <label class="text-xs uppercase font-bold tracking-wider text-[#4a0404]/60">Entreprise</label>
                <select name="company" class="w-full mt-1 p-2 rounded-lg border border-[#4a0404]/20 bg-white/50 text-sm">
                    <option value="">Toutes</option>
                    @foreach($companies as $comp)
                        <option value="{{ $comp }}" {{ request('company') == $comp ? 'selected' : '' }}>{{ $comp }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Actions (Filtrer & Reset) -->
            <div class="flex items-end gap-2">
                <button type="submit" class="w-full bg-[#4a0404] text-[#e8e3dc] py-2 rounded-lg text-sm font-semibold hover:opacity-90 transition">Filtrer</button>
                <a href="/job-search" class="w-full text-center bg-white/40 border border-[#4a0404]/20 py-2 rounded-lg text-sm font-semibold hover:bg-white/60 transition">Reset</a>
            </div>
        </form>

        <!-- Liste des Métiers -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            @forelse($jobs as $job)
                <div class="glass-card p-6 rounded-2xl flex flex-col justify-between hover:border-[#4a0404]/30 transition duration-300">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-widest text-[#4a0404]/50">{{ $job->company ?? 'Entreprise Anonyme' }}</span>
                        <h2 class="text-xl font-serif italic mt-1 font-bold">{{ $job->title }}</h2>
                        <p class="text-xs mt-2"><span class="px-2 py-0.5 bg-[#4a0404]/10 rounded-md">{{ $job->domain->domain_name ?? 'Général' }}</span></p>
                        <p class="text-sm mt-3 text-[#4a0404]/70 line-clamp-3">{{ $job->description }}</p>
                    </div>
                    
                    <button onclick="openJobModal({{ json_encode($job->load(['domain', 'skills'])) }})" class="mt-6 w-full text-center border border-[#4a0404] py-2 rounded-lg text-xs uppercase tracking-wider font-semibold hover:bg-[#4a0404] hover:text-[#e8e3dc] transition">
                        Voir les détails
                    </button>
                </div>
            @empty
                <div class="col-span-3 glass-card p-12 text-center rounded-2xl">
                    <p class="italic text-[#4a0404]/60">Aucune offre ne correspond à vos critères.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-10 flex justify-center">
            {{ $jobs->links() }}
        </div>
    </div>

    <!-- MODAL DETAILS (Pop-up تفاعلي) -->
    <div id="jobModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/20 hidden backdrop-blur-sm">
        <div class="glass-modal w-full max-w-2xl p-8 rounded-3xl mx-4 border border-[#4a0404]/20 max-h-[85vh] overflow-y-auto text-[#4a0404]">
            <div class="flex justify-between items-start border-b border-[#4a0404]/10 pb-4">
                <div>
                    <span id="modalCompany" class="text-xs font-bold uppercase tracking-widest text-[#4a0404]/60"></span>
                    <h2 id="modalTitle" class="text-3xl font-serif italic font-bold mt-1"></h2>
                </div>
                <button onclick="closeJobModal()" class="text-2xl font-light hover:opacity-70">&times;</button>
            </div>

            <div class="mt-6 space-y-4">
                <div class="flex gap-4 text-xs">
                    <p><strong>Secteur:</strong> <span id="modalDomain" class="px-2 py-0.5 bg-[#4a0404]/10 rounded"></span></p>
                    <p><strong>Ville:</strong> <span class="px-2 py-0.5 bg-[#4a0404]/10 rounded">Maroc</span></p>
                </div>

                <div>
                    <h3 class="text-xs uppercase font-bold tracking-wider text-[#4a0404]/60 mb-2">Description du poste</h3>
                    <p id="modalDescription" class="text-sm whitespace-pre-line leading-relaxed text-[#4a0404]/80"></p>
                </div>

                <div>
                    <h3 class="text-xs uppercase font-bold tracking-wider text-[#4a0404]/60 mb-2">Compétences requises</h3>
                    <div id="modalSkills" class="flex flex-wrap gap-2"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript التفاعلي للـ Modal -->
    <script>
        function openJobModal(job) {
            document.getElementById('modalTitle').innerText = job.title;
            document.getElementById('modalCompany').innerText = job.company ? job.company : 'Entreprise Anonyme';
            document.getElementById('modalDomain').innerText = job.domain ? job.domain.domain_name : 'Général';
            document.getElementById('modalDescription').innerText = job.description;

            const skillsContainer = document.getElementById('modalSkills');
            skillsContainer.innerHTML = '';
            
            if(job.skills && job.skills.length > 0) {
                job.skills.forEach(skill => {
                    const span = document.createElement('span');
                    span.className = 'text-xs px-2.5 py-1 bg-[#4a0404] text-[#e8e3dc] rounded-full';
                    span.innerText = skill.skill_name;
                    skillsContainer.appendChild(span);
                });
            } else {
                skillsContainer.innerHTML = '<span class="text-xs italic text-[#4a0404]/50">Aucune compétence spécifiée</span>';
            }

            document.getElementById('jobModal').classList.remove('hidden');
        }

        function closeJobModal() {
            document.getElementById('jobModal').classList.add('hidden');
        }
    </script>
</body>
</html>