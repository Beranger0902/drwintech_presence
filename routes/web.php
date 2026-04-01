<?php


use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DemandeCongeController;
use App\Http\Controllers\Admin\PermissionController as AdminPermissionController;
use App\Http\Controllers\Admin\EmployeController;
use App\Http\Controllers\Admin\StatistiqueController as AdminStatistiqueController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Agent\PresenceController;
use App\Http\Controllers\Agent\RapportController;
use App\Http\Controllers\Agent\StatistiqueController as AgentStatistiqueController;
use App\Http\Controllers\Agent\TempsTravailController;
use App\Http\Controllers\Employe\CongeController;
use App\Http\Controllers\Employe\PermissionController;
use App\Http\Controllers\Employe\HistoriqueController;
use App\Http\Controllers\Employe\PointageController;
use App\Http\Controllers\Employe\TempsTravailController as EmployeTempsTravailController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = auth()->user();

    if (! $user) {
        return redirect()->route('login');
    }

    return match ($user->role) {
        'administrateur' => redirect()->route('admin.dashboard'),
        'employe' => redirect()->route('employe.dashboard'),
        'agent_accueil' => redirect()->route('agent.dashboard'),
        default => abort(403, 'Rôle non autorisé.'),
    };
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ADMIN
        Route::prefix('admin')->name('admin.')->middleware('role:administrateur')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

        Route::resource('utilisateurs', UserController::class);
        Route::resource('employes', EmployeController::class);
        Route::get('/demandes/conges', [DemandeCongeController::class, 'index'])->name('demandes.conges.index');
        Route::patch('/demandes/conges/{demande}/approuver', [DemandeCongeController::class, 'approuver'])->name('demandes.conges.approuver');
        Route::patch('/demandes/conges/{demande}/refuser', [DemandeCongeController::class, 'refuser'])->name('demandes.conges.refuser');
        Route::get('/demandes/permissions', [AdminPermissionController::class, 'index'])->name('demandes.permissions.index');
        Route::patch('/demandes/permissions/{demande}/approve', [AdminPermissionController::class, 'approve'])->name('demandes.permissions.approve');
        Route::patch('/demandes/permissions/{demande}/refuse', [AdminPermissionController::class, 'refuse'])->name('demandes.permissions.refuse');
        Route::get('/statistiques', [AdminStatistiqueController::class, 'index'])->name('statistiques.index');
    });

    // AGENT D'ACCUEIL
        Route::prefix('agent')->name('agent.')->middleware('role:agent_accueil')->group(function () {
        Route::view('/dashboard', 'agent.dashboard')->name('dashboard');

        Route::get('/presences', [PresenceController::class, 'index'])->name('presences.index');
        Route::get('/temps-travail', [TempsTravailController::class, 'index'])->name('temps-travail.index');
        Route::get('/rapports', [RapportController::class, 'index'])->name('rapports.index');
        Route::get('/statistiques', [AgentStatistiqueController::class, 'index'])->name('statistiques.index');
    });

    // EMPLOYE
        Route::prefix('employe')->name('employe.')->middleware('role:employe')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Employe\DashboardController::class, 'index'])->name('dashboard');

        Route::get('/pointage', [PointageController::class, 'index'])->name('pointage.index');
        Route::post('/pointage/arrivee', [PointageController::class, 'pointerArrivee'])->name('pointage.arrivee');
        Route::post('/pointage/depart', [PointageController::class, 'pointerDepart'])->name('pointage.depart');
        Route::get('/historique', [PointageController::class, 'historique'])->name('historique.index');
        Route::get('/temps-travail', [PointageController::class, 'tempsTravail'])->name('temps-travail.index');

        Route::get('/demandes/conges', [CongeController::class, 'index'])->name('demandes.conges.index');
        Route::get('/demandes/conges/create', [CongeController::class, 'create'])->name('demandes.conges.create');
        Route::post('/demandes/conges', [CongeController::class, 'store'])->name('demandes.conges.store');

        Route::get('/demandes/permissions', [PermissionController::class, 'index'])->name('demandes.permissions.index');
        Route::get('/demandes/permissions/create', [PermissionController::class, 'create'])->name('demandes.permissions.create');
        Route::post('/demandes/permissions', [PermissionController::class, 'store'])->name('demandes.permissions.store');
    });
});

require __DIR__.'/auth.php';
