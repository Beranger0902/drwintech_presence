# DrWinTech Presence - Mémoire du Projet

## Vue d'ensemble
Système de gestion de présence employés avec 3 rôles: Admin, Agent d'accueil, Employé

## Stack Technique
- **Backend**: Laravel 12, PHP 8.2+, MySQL
- **Frontend**: Vite, Tailwind CSS, Alpine.js, Axios, HTML, CSS, JavaScript
- **Auth**: Laravel Breeze
- **Tests**: PHPUnit
- **Linting**: Laravel Pint

## Architecture - Rôles

1. **Administrateur** (admin)
   - Dashboard global
   - Gérer utilisateurs et employés
   - Approuver/refuser demandes de congés et permissions
   - Débloquer employés après 3 refus (bouton dédié orange)
   - Voir statistiques

2. **Agent d'accueil** (agent_accueil)
   - Dashboard
   - Voir la présences d'aujourd'hui
   - Voir temps de travail
   - Consulter rapports
   - Statistiques

3. **Employé** (employe)
   - Dashboard personnel (calendrier avec week-end/fériés marqués)
   - Pointage arrivée/départ (avec GPS, détecte retard si après 08:30)
   - Historique des pointages (calcul heures supplémentaires automatique)
   - Demandes de congés (blocage après 3 refus + vérif chevauchement)
   - Demandes de permissions (blocage après 3 refus + vérif chevauchement)
   - Temps de travail
   - Auto-marquage absence après 20h si pas de départ
   - Auto-création présence pour cas spéciaux (week-end, fériés, congé, permission)

## Modèles de Données

- **User**: Authentification (id, email, password, role, actif)
- **Employe**: (matricule, nom, prenom, poste, département, date_embauche, refusals_count, demandes_bloquees)
- **Presence**: (employe_id, date, heure_arrivée/départ, GPS, duree, statut_arrivee, duree_normale, heures_supplementaires)
- **Demande**: (employe_id, type_demande, statut, observation)
- **Conge**: (demande_id, date_debut, date_fin, type_conge, piece_jointe)
- **Permission**: (demande_id, date_permission, heure_debut, heure_fin, duree)
- **JourFerie**: (date, nom, année)

## Contrôleurs

```
Admin/*:
  - DemandeCongeController: approuver, refuser, debloquer
  - PermissionController: approve, refuse, debloquer
  - Autres: Dashboard, Users, Employe, Statistique

Agent/*: Presence, Rapport, Statistique, TempsTravail

Employe/*:
  - PointageController: arrivée/départ, checks chevauchement + blocage
  - CongeController: store with checks chevauchement + blocage
  - PermissionController: store with checks chevauchement + blocage
  - Autres: Dashboard, Historique, TempsTravail
```

## Routes Principales
- `/dashboard` → Redirige selon rôle
- `/admin/*` → Admin endpoints (inclus debloquer conges & permissions)
- `/agent/*` → Agent d'accueil endpoints
- `/employe/*` → Employé endpoints

## Système de Blocage de Demandes ✅ (IMPLÉMENTÉ)
- Après 3 refus consécutifs (congé OU permission) → employe.demandes_bloquees = true
- Blocage actif: Employé ne peut pas soumettre si:
  - demandes_bloquees = true (bouton UI désactivé, grisé)
  - Ou si demande active/chevauchante existe (statut: en_attente, approuver, approuve)
- Admin peut débloquer (bouton orange ♫) → refusals_count = 0, demandes_bloquees = false
- Approuver une demande réinitialise automatiquement les compteurs
- Routes: `/admin/demandes/conges/{id}/debloquer` & `/admin/demandes/permissions/{id}/debloquer`

## Bugs/Notes Corrigés
- User.php: "boll" → "bool" (3 méthodes: isAdmin, isAgentAccueil, isEmplye) ✅
- Presence.php: "belongTo" → "belongsTo" ✅
- Time Bug: Carbon::createFromFormat(...) sans date → 1970. Fix: now()->copy()->setTimeFromTimeString() ✅
- Apostrophes: Smart quotes cause parser errors. Fix: Use double quotes (") not escaping single quotes ✅
- statut_arrivee: Champ séparé pour tracker statut arrivée indépendament du statut final ✅
- refusals_count & demandes_bloquees: Tracking consecutive refusals and blocking state ✅

## Fichiers clés
```
routes/web.php (avec debloquer routes)
app/Models/* (User, Employe, Presence, Demande, Conge, Permission, JourFerie)
app/Http/Controllers/Admin/DemandeCongeController.php (approuver, refuser, debloquer)
app/Http/Controllers/Admin/PermissionController.php (approve, refuse, debloquer)
app/Http/Controllers/Employe/PointageController.php (arrivée/départ avec checks)
app/Http/Controllers/Employe/CongeController.php (store avec blocage)
app/Http/Controllers/Employe/PermissionController.php (store avec blocage)
app/Http/Controllers/Employe/DashboardController.php (stats)
database/migrations/* (13 migrations + 2 nouvelles)
resources/views/employe/demandes/conges/index.blade.php (UI bloquée si demandes_bloquees)
resources/views/employe/demandes/permissions/index.blade.php (UI bloquée si demandes_bloquees)
resources/views/admin/demandes/conges/index.blade.php (bouton débloquer)
resources/views/admin/demandes/permissions/index.blade.php (bouton débloquer)
```

## Git
- Repo git initialisé
- Migrations: 2026_03_31_142450 (dernière init) + 2 nouvelles pour statut_arrivee & request tracking

## État Actuel ✅
- Pointage complet (GPS, temps, statuts retard/présence/absent)
- Demandes avec blocage 3-strike + vérif chevauchement
- UI employé bloquée quand demandes_bloquees = true
- Admin peut débloquer avec bouton orange dédié
- Auto-création présence pour cas spéciaux
- Dashboard calendrier avec marquage fériés/week-end

**À migrer**: `php artisan migrate` (ajoute statut_arrivee, refusals_count, demandes_bloquees)
