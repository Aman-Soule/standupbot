# StandupBot

> Application SaaS de daily standups asynchrones pour équipes remote.  
> Chaque membre soumet son standup quotidien, le manager voit un digest propre chaque matin.

![Laravel](https://img.shields.io/badge/Laravel-11-FF2D20?style=flat&logo=laravel&logoColor=white)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-16-4169E1?style=flat&logo=postgresql&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?style=flat&logo=php&logoColor=white)

---

## Fonctionnalités

- Authentification complète (inscription, connexion, vérification email, mot de passe oublié)
- Multi-tenant — chaque équipe a son workspace isolé
- Questions de standup configurables par workspace
- Soumission quotidienne avec contrainte 1 standup / user / jour
- Dashboard avec digest du jour, statut de l'équipe et statistiques
- Système d'invitations par email avec token et expiration
- Gestion des membres et des rôles (admin / membre)
- Interface responsive — sidebar desktop, vue mobile adaptée

---

## Stack technique

| Couche | Technologie |
|---|---|
| Backend | Laravel 11 |
| Base de données | PostgreSQL |
| Frontend | Blade + CSS natif |
| Auth | Laravel Breeze |
| Serveur local | php artisan serve |

---

## Prérequis

- PHP >= 8.2
- Composer
- Node.js >= 18
- PostgreSQL >= 14

---

## Installation

### 1. Cloner le projet

```bash
git clone https://github.com/votre-username/standupbot.git
cd standupbot
```

### 2. Installer les dépendances

```bash
composer install
npm install
```

### 3. Configurer l'environnement

```bash
cp .env.example .env
php artisan key:generate
```

Editer `.env` et renseigner la connexion PostgreSQL :

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=standupbot
DB_USERNAME=postgres
DB_PASSWORD=votre_mot_de_passe

APP_NAME=StandupBot
APP_URL=http://localhost:8000
```

### 4. Créer la base de données

```bash
# Dans psql ou pgAdmin, créer la base
createdb standupbot
```

### 5. Lancer les migrations

```bash
php artisan migrate
```

### 6. Compiler les assets

```bash
npm run build
# ou en développement
npm run dev
```

### 7. Lancer le serveur

```bash
php artisan serve
```

L'application est disponible sur [http://localhost:8000](http://localhost:8000).

---

## Structure du projet

```
app/
├── Http/Controllers/
│   ├── DashboardController.php     # Vue d'ensemble + stats
│   ├── WorkspaceController.php     # Création et switch de workspace
│   ├── StandupController.php       # Soumission et historique
│   └── MemberController.php        # Gestion des membres + invitations
├── Models/
│   ├── User.php
│   ├── Workspace.php
│   ├── WorkspaceMember.php
│   ├── Invitation.php
│   ├── Standup.php
│   ├── StandupQuestion.php
│   └── StandupAnswer.php

database/migrations/
├── create_workspaces_table
├── create_workspace_members_table
├── create_invitations_table
├── create_standup_questions_table
├── create_standups_table
├── create_standup_answers_table
└── create_subscriptions_table

resources/views/
├── layouts/
│   ├── app.blade.php               # Layout dashboard (sidebar)
│   └── auth.blade.php              # Layout auth (split screen)
├── auth/
│   ├── login.blade.php
│   └── register.blade.php
├── workspaces/
│   └── create.blade.php
├── standups/
│   └── create.blade.php
├── members/
│   └── index.blade.php
└── dashboard.blade.php
```

---

## Schéma de base de données

```
users
  └── workspaces (owner_id)
        ├── workspace_members (user_id, role)
        ├── invitations (email, token, status)
        ├── standup_questions (question, order)
        ├── standups (user_id, date)
        │     └── standup_answers (question_id, answer)
        └── subscriptions (stripe_id, plan)
```

---

## Flux utilisateur

```
Inscription
    └── Création du workspace
          └── Invitation des membres
                └── Soumission quotidienne du standup
                      └── Dashboard — digest de l'équipe
```

---

## Variables d'environnement importantes

| Variable | Description |
|---|---|
| `DB_CONNECTION` | Doit être `pgsql` |
| `DB_DATABASE` | Nom de la base PostgreSQL |
| `APP_KEY` | Généré via `php artisan key:generate` |
| `MAIL_*` | Configuration email (pour les invitations) |

---

## Roadmap

- [ ] Rappels automatiques par email (Laravel Scheduler)
- [ ] Résumé IA du standup de l'équipe (OpenAI API)
- [ ] Plans Free / Pro avec Laravel Cashier + Stripe
- [ ] Admin panel avec Filament PHP
- [ ] Extension Chrome pour soumettre rapidement

---

## Licence

MIT
