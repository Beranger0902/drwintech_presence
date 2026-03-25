<x-app-layout>
    <div class="h-screen overflow-hidden bg-[#eef3f8]">
        <div class="flex h-screen">
            <!-- Sidebar -->
            <aside class="w-[280px] bg-gradient-to-b from-[#0f2747] to-[#18365d] text-white flex flex-col">
                <div class="flex items-center gap-4 px-6 py-5 border-b border-white/10">
                    <img
                        src="https://ui-avatars.com/api/?name=Employe&background=ffffff&color=16365d&size=100"
                        alt="Profil"
                        class="w-14 h-14 rounded-full border border-white/20"
                    >
                    <h2 class="text-[22px] font-semibold">Employé</h2>
                </div>

                <nav class="flex-1 px-4 py-5 space-y-1.5 text-[15px]">
                    <a href="{{ route('employe.dashboard') }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-lg bg-[#1e5aa8] font-medium shadow-sm">
                        <span class="text-[16px]">📋</span>
                        <span>Tableau de bord</span>
                    </a>

                    <a href="{{ route('employe.pointage.index') }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-white/10 text-white/90">
                        <span class="text-[16px]">✅</span>
                        <span>Pointage de présence</span>
                    </a>

                    <a href="{{ route('employe.historique.index') }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-white/10 text-white/90">
                        <span class="text-[16px]">📄</span>
                        <span>Historique</span>
                    </a>

                    <a href="{{ route('employe.temps-travail.index') }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-white/10 text-white/90">
                        <span class="text-[16px]">🗂️</span>
                        <span>Temps de travail</span>
                    </a>

                    <div class="pt-1">
                        <div class="flex items-center justify-between px-4 py-3 rounded-lg hover:bg-white/10 text-white/90">
                            <div class="flex items-center gap-3">
                                <span class="text-[16px]">📁</span>
                                <span>Demande</span>
                            </div>
                            <span class="text-sm">⌄</span>
                        </div>

                        <div class="ml-6 mt-1 space-y-1 border-l border-white/10 pl-4">
                            <a href="{{ route('employe.demandes.conges.index') }}"
                               class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/10 text-white/80 text-[14px]">
                                <span>⊙</span>
                                <span>Congé</span>
                            </a>

                            <a href="{{ route('employe.demandes.permissions.index') }}"
                               class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/10 text-white/80 text-[14px]">
                                <span>⬒</span>
                                <span>Permission</span>
                            </a>
                        </div>
                    </div>

                    <a href="{{ route('profile.edit') }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-white/10 text-white/90">
                        <span class="text-[16px]">👤</span>
                        <span>Profil</span>
                    </a>
                </nav>

                <div class="px-4 pb-5">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="w-full flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-white/10 text-white font-semibold text-[15px]">
                            <span class="text-[16px]">⎋</span>
                            <span>Déconnexion</span>
                        </button>
                    </form>
                </div>
            </aside>

            <!-- Main -->
            <main class="flex-1 px-10 py-7 overflow-hidden">
                <!-- Header -->
                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-[24px] font-bold text-[#24364d]">Tableau de bord</h1>
                    <div class="flex items-center gap-5 text-[22px] text-[#53657a]">
                        <span>🔔</span>
                        <span>↻</span>
                        <span>⚙️</span>
                    </div>
                </div>

                <!-- Top cards -->
                <div class="grid grid-cols-4 gap-5 mb-5">
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 px-6 py-5">
                        <div class="flex items-center gap-2 text-[#2b4d73] font-semibold text-[14px] mb-4">
                            <span class="text-green-500 text-[20px]">✅</span>
                            <span>Pointage du jour</span>
                        </div>
                        <p class="text-[21px] leading-tight font-bold text-[#1f2f46]">Pointé à 08:15</p>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 px-6 py-5">
                        <div class="flex items-center gap-2 text-[#2b4d73] font-semibold text-[14px] mb-4">
                            <span class="text-[#3a5f93] text-[20px]">🕒</span>
                            <span>Heure d'arrivée</span>
                        </div>
                        <p class="text-[38px] leading-none font-bold text-[#1f2f46]">08:15</p>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 px-6 py-5">
                        <div class="flex items-center gap-2 text-[#2b4d73] font-semibold text-[14px] mb-4">
                            <span class="text-red-400 text-[20px]">🕒</span>
                            <span>Heure de départ</span>
                        </div>
                        <p class="text-[38px] leading-none font-bold text-[#1f2f46]">17:30</p>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 px-6 py-5">
                        <div class="flex items-center gap-2 text-[#2b4d73] font-semibold text-[14px] mb-4">
                            <span class="text-[#3a5f93] text-[20px]">📅</span>
                            <span>Temps de travail</span>
                        </div>
                        <p class="text-[38px] leading-none font-bold text-[#1f2f46]">8h 15m</p>
                    </div>
                </div>

                <!-- Middle -->
                <div class="grid grid-cols-[1.1fr_0.9fr] gap-5 mb-5">
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5">
                        <h2 class="text-[18px] font-bold text-[#31465f] mb-4">Activité récente</h2>

                        <div class="space-y-4">
                            <div class="flex items-center justify-between border-t pt-4">
                                <div class="flex items-center gap-3">
                                    <span class="text-green-500 text-[22px]">🟢</span>
                                    <div>
                                        <p class="text-[14px] font-semibold text-[#2a3f58]">Pointage</p>
                                        <p class="text-[13px] text-slate-500">Arrivée à 08:15</p>
                                    </div>
                                </div>
                                <span class="text-[13px] text-slate-400">Aujourd’hui</span>
                            </div>

                            <div class="flex items-center justify-between border-t pt-4">
                                <div class="flex items-center gap-3">
                                    <span class="text-red-400 text-[22px]">📛</span>
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <p class="text-[14px] font-semibold text-[#2a3f58]">Demande de congé</p>
                                        <span class="px-3 py-1 rounded-full bg-yellow-400 text-white text-[12px] font-semibold">
                                            En attente
                                        </span>
                                    </div>
                                </div>
                                <span class="text-[13px] text-slate-400">Hier</span>
                            </div>

                            <div class="flex items-center justify-between border-t pt-4">
                                <div class="flex items-center gap-3">
                                    <span class="text-blue-500 text-[22px]">🕘</span>
                                    <p class="text-[14px] font-semibold text-[#2a3f58]">Pointage</p>
                                </div>
                                <span class="text-[13px] text-slate-400">Hier</span>
                            </div>
                        </div>

                        <div class="mt-5">
                            <a href="{{ route('employe.historique.index') }}" class="text-[#2a6fcd] font-semibold text-[14px]">
                                Voir tout
                            </a>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5">
                        <h2 class="text-[18px] font-bold text-[#31465f] mb-4">Mes demandes</h2>

                        <div class="grid grid-cols-3 gap-3 mb-5">
                            <div class="rounded-2xl bg-[#f5a623] text-white p-4">
                                <p class="text-[13px] font-semibold leading-tight">Congés<br>en attente</p>
                                <p class="text-[34px] font-bold text-right mt-2">1</p>
                            </div>

                            <div class="rounded-2xl bg-[#2f7cf6] text-white p-4">
                                <p class="text-[13px] font-semibold leading-tight">Permissions<br>en attente</p>
                                <p class="text-[34px] font-bold text-right mt-2">0</p>
                            </div>

                            <div class="rounded-2xl bg-[#34c84a] text-white p-4">
                                <p class="text-[13px] font-semibold leading-tight">Demandes<br>approuvées</p>
                                <p class="text-[34px] font-bold text-right mt-2">3</p>
                            </div>
                        </div>

                        <div class="border-t pt-5 text-center">
                            <a href="{{ route('employe.demandes.conges.index') }}"
                               class="inline-block px-8 py-2.5 rounded-xl bg-[#2f7cf6] text-white text-[14px] font-semibold shadow">
                                Voir mes demandes
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Bottom -->
                <div class="grid grid-cols-2 gap-5">
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5">
                        <h2 class="text-[18px] font-bold text-[#31465f] mb-4">Temps de travail de la semaine</h2>

                        <div class="h-[250px] flex items-end justify-between gap-3 px-3 pt-5 border-t">
                            @php
                                $jours = [
                                    ['label' => 'Lun', 'height' => 'h-20', 'color' => 'bg-[#3f5f8c]'],
                                    ['label' => 'Mar', 'height' => 'h-24', 'color' => 'bg-[#4b6e9d]'],
                                    ['label' => 'Mer', 'height' => 'h-28', 'color' => 'bg-[#5275a7]'],
                                    ['label' => 'Jeu', 'height' => 'h-32', 'color' => 'bg-[#44b36c]'],
                                    ['label' => 'Ven', 'height' => 'h-28', 'color' => 'bg-[#5275a7]'],
                                    ['label' => 'Sam', 'height' => 'h-24', 'color' => 'bg-[#4b6e9d]'],
                                    ['label' => 'Dim', 'height' => 'h-22', 'color' => 'bg-[#3f5f8c]'],
                                ];
                            @endphp

                            @foreach ($jours as $jour)
                                <div class="flex flex-col items-center justify-end h-full w-full">
                                    <div class="w-10 rounded-t-md {{ $jour['height'] }} {{ $jour['color'] }}"></div>
                                    <span class="mt-2 text-[13px] text-slate-600">{{ $jour['label'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5">
                        <h2 class="text-[18px] font-bold text-[#31465f] mb-4">Localisation des pointages</h2>

                        <div class="border-t pt-4">
                            <div class="h-[250px] rounded-xl overflow-hidden bg-slate-100 flex items-center justify-center relative">
                                <img
                                    src="https://images.unsplash.com/photo-1524661135-423995f22d0b?q=80&w=1200&auto=format&fit=crop"
                                    alt="Carte"
                                    class="w-full h-full object-cover opacity-60"
                                >
                                <div class="absolute text-6xl">📍</div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</x-app-layout>