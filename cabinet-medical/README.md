# 🏥 Système de Gestion de Cabinet Médical

Projet Laravel 11 — Module Programmation Backend PHP (S6)  
**Faculté des Sciences Semlalia, Université Cadi Ayyad**

---

## ✅ Fonctionnalités implémentées

| Module | Statut |
|--------|--------|
| Authentification (inscription/connexion/déconnexion) | ✅ |
| Gestion des rôles (Admin / Médecin / Secrétaire / Patient) | ✅ |
| Gestion des patients (CRUD + dossier médical) | ✅ |
| Calendrier des disponibilités des médecins | ✅ |
| Prise / modification / annulation de rendez-vous | ✅ |
| Notifications email (confirmation + rappel RDV) | ✅ |
| Consultation médicale avec signes vitaux | ✅ |
| Génération d'ordonnances | ✅ |
| Export PDF (ordonnances + comptes-rendus) | ✅ |
| Tableau de bord avec graphiques (Chart.js) | ✅ |
| Calendrier interactif (FullCalendar) | ✅ |
| Tests unitaires et fonctionnels (PHPUnit) | ✅ |
| Architecture MVC + Eloquent ORM | ✅ |
| Protection CSRF + hashage des mots de passe | ✅ |
| Seeders pour les données de démonstration | ✅ |

---

## ⚙️ Prérequis

- PHP >= 8.2
- Composer
- MySQL >= 8.0
- Node.js (optionnel, pour assets)

---

## 🚀 Installation

### 1. Cloner le projet

```bash
git clone https://github.com/votre-username/cabinet-medical.git
cd cabinet-medical
```

### 2. Installer les dépendances PHP

```bash
composer install
```

### 3. Configurer l'environnement

```bash
cp .env.example .env
php artisan key:generate
```

Modifier `.env` :
```env
DB_DATABASE=cabinet_medical
DB_USERNAME=root
DB_PASSWORD=votre_mot_de_passe

MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_USERNAME=votre_username_mailtrap
MAIL_PASSWORD=votre_password_mailtrap
```

### 4. Créer la base de données

```sql
CREATE DATABASE cabinet_medical CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 5. Exécuter les migrations et seeders

```bash
php artisan migrate --seed
```

### 6. Lancer le serveur

```bash
php artisan serve
```

L'application est disponible sur : **http://localhost:8000**

---

## 🔑 Comptes de démonstration

| Rôle | Email | Mot de passe |
|------|-------|-------------|
| Administrateur | admin@cabinet.ma | password |
| Médecin | dr.alami@cabinet.ma | password |
| Secrétaire | secretaire@cabinet.ma | password |
| Patient | m.berrada@gmail.com | password |

---

## 📁 Structure du projet

```
cabinet-medical/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/AuthController.php
│   │   │   ├── Admin/AdminController.php
│   │   │   ├── Doctor/DoctorController.php
│   │   │   ├── Secretary/SecretaryController.php
│   │   │   ├── AppointmentController.php
│   │   │   ├── ConsultationController.php
│   │   │   └── PatientController.php
│   │   └── Middleware/RoleMiddleware.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Doctor.php
│   │   ├── Patient.php
│   │   ├── Secretary.php
│   │   ├── Appointment.php
│   │   ├── Consultation.php
│   │   ├── Prescription.php
│   │   ├── PrescriptionItem.php
│   │   └── DoctorAvailability.php
│   └── Notifications/
│       ├── AppointmentConfirmed.php
│       ├── AppointmentCancelled.php
│       └── AppointmentReminder.php
├── database/
│   ├── migrations/          # 9 fichiers de migration
│   ├── seeders/DatabaseSeeder.php
│   └── factories/UserFactory.php
├── resources/views/
│   ├── layouts/             # app.blade.php + guest.blade.php
│   ├── auth/                # login, register, forgot-password
│   ├── admin/               # dashboard, users, doctors
│   ├── doctor/              # dashboard
│   ├── secretary/           # dashboard
│   ├── patient/             # dashboard
│   ├── appointments/        # index, create, show, calendar
│   ├── consultations/       # create, show
│   ├── patients/            # index, show, edit
│   └── pdf/                 # prescription, consultation
├── routes/web.php
└── tests/
    ├── Unit/AppointmentTest.php
    └── Feature/AuthenticationTest.php
```

---

## 🧪 Lancer les tests

```bash
php artisan test
# ou
./vendor/bin/phpunit --testdox
```

---

## 🌐 Déploiement (Railway / Render)

### Railway

```bash
# Installer Railway CLI
npm install -g @railway/cli

# Login et déploiement
railway login
railway init
railway up
```

Variables d'environnement à configurer dans Railway :
- `APP_ENV=production`
- `APP_KEY=` (générer avec `php artisan key:generate`)
- `DB_*` (fournis par Railway MySQL)
- `MAIL_*` (Mailtrap ou SendGrid)

### Variables d'env production

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://votre-app.railway.app
SESSION_DRIVER=database
QUEUE_CONNECTION=database
```

---

## 📧 Configuration email (Mailtrap)

1. Créer un compte sur [mailtrap.io](https://mailtrap.io)
2. Copier les credentials SMTP
3. Mettre à jour `.env` avec vos identifiants

---

## 🛠️ Technologies utilisées

| Composant | Technologie |
|-----------|-------------|
| Backend | Laravel 11.x |
| Frontend | Bootstrap 5.3 |
| Base de données | MySQL 8.0 |
| Graphiques | Chart.js 4.x |
| Calendrier | FullCalendar 6.x |
| PDF | DomPDF (barryvdh/laravel-dompdf) |
| Email | Laravel Mail + Mailtrap |
| Tests | PHPUnit 11 |
| Versioning | Git / GitHub |

---

## 👥 Équipe

- **Mme JABIR Somaya** — Enseignante
- **Mme BABA Naima** — Enseignante

Développé dans le cadre du module **Programmation Backend PHP** — Semestre S6 — Année 2025/2026

---

## 📄 Licence

MIT License
