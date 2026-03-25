<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Pointage de présence
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 rounded bg-green-100 p-4 text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 rounded bg-red-100 p-4 text-red-800">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 rounded bg-red-100 p-4 text-red-800">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="mb-6">
                    <p><strong>Date :</strong> {{ now()->format('d/m/Y') }}</p>
                    <p><strong>Heure actuelle :</strong> <span id="heure-actuelle"></span></p>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-2">Pointage du jour</h3>
                    <p><strong>Arrivée :</strong> {{ $presenceDuJour?->heure_arrivee ?? 'Non pointée' }}</p>
                    <p><strong>Départ :</strong> {{ $presenceDuJour?->heure_depart ?? 'Non pointé' }}</p>
                    <p><strong>Statut :</strong> {{ $presenceDuJour?->statut_pointage ?? 'Aucun pointage' }}</p>
                </div>

                <div class="flex gap-4">
                    <form method="POST" action="{{ route('employe.pointage.arrivee') }}" id="form-arrivee">
                        @csrf
                        <input type="hidden" name="latitude" id="latitude-arrivee">
                        <input type="hidden" name="longitude" id="longitude-arrivee">
                        <button type="button" onclick="envoyerPosition('arrivee')" class="rounded bg-green-600 px-4 py-2 text-white hover:bg-green-700">
                            Pointer l’arrivée
                        </button>
                    </form>

                    <form method="POST" action="{{ route('employe.pointage.depart') }}" id="form-depart">
                        @csrf
                        <input type="hidden" name="latitude" id="latitude-depart">
                        <input type="hidden" name="longitude" id="longitude-depart">
                        <button type="button" onclick="envoyerPosition('depart')" class="rounded bg-orange-600 px-4 py-2 text-white hover:bg-orange-700">
                            Pointer le départ
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function mettreAJourHeure() {
            const maintenant = new Date();
            document.getElementById('heure-actuelle').textContent = maintenant.toLocaleTimeString();
        }

        setInterval(mettreAJourHeure, 1000);
        mettreAJourHeure();

        function envoyerPosition(type) {
            if (!navigator.geolocation) {
                alert("La géolocalisation n'est pas supportée par votre navigateur.");
                return;
            }

            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const latitude = position.coords.latitude;
                    const longitude = position.coords.longitude;

                    if (type === 'arrivee') {
                        document.getElementById('latitude-arrivee').value = latitude;
                        document.getElementById('longitude-arrivee').value = longitude;
                        document.getElementById('form-arrivee').submit();
                    } else {
                        document.getElementById('latitude-depart').value = latitude;
                        document.getElementById('longitude-depart').value = longitude;
                        document.getElementById('form-depart').submit();
                    }
                },
                function(error) {
                    alert("Impossible de récupérer votre position.");
                }
            );
        }
    </script>
</x-app-layout>