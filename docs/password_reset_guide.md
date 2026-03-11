# Guide de Réinitialisation de Mot de Passe

## Processus Implémenté

### 1. Flow de Réinitialisation

```
┌─────────────────┐      ┌──────────────────────┐      ┌─────────────────────┐
│  Page Login     │ ───► │  Page Mot de passe   │ ───► │  Lien envoyé par    │
│  (login.blade)  │      │  oublié              │      │  email               │
└─────────────────┘      │  (password_forget)   │      └─────────────────────┘
                        └──────────────────────┘                │
                               │                                  ▼
                               │                       ┌─────────────────────┐
                               │                       │  Email de réinitial │
                               │                       │  (ResetPasswordNotif)│
                               │                       └─────────────────────┘
                               │                                  │
                               │                                  ▼
                               │                       ┌─────────────────────┐
                               │                       │  Page Nouveau mot   │
                               │                       │  de passe           │
                               │                       │  (new_password)     │
                               │                       └─────────────────────┘
                               │                                  │
                               ▼                                  ▼
                        ┌─────────────────────┐      ┌─────────────────────┐
                        │  Connexion          │ ◄─── │  Mot de passe       │
                        │  (login)            │      │  mis à jour         │
                        └─────────────────────┘      └─────────────────────┘
```

### 2. Fichiers Créés/Modifiés

#### Routes (`routes/web.php`)
```php
// Routes ajoutées pour la réinitialisation du mot de passe
Route::post('/password/email', [Appcontroller::class, 'sendResetLinkEmail'])
    ->name('password.email');
Route::get('/password/reset/{token}', [Appcontroller::class, 'showResetForm'])
    ->name('password.reset');
Route::post('/password/reset', [Appcontroller::class, 'reset'])
    ->name('password.update');
```

#### Controller (`app/Http/Controllers/Appcontroller.php`)
Trois méthodes ajoutées :
- `sendResetLinkEmail()` - Envoie le lien de réinitialisation par email
- `showResetForm()` - Affiche le formulaire de nouveau mot de passe
- `reset()` - Traite la demande de réinitialisation

#### Notifications (`app/Notifications/ResetPasswordNotification.php`)
Notification personnalisée pour l'email de réinitialisation

#### Vues Modifiées/Créées
- `resources/views/login/login.blade.php` - Lien vers "Mot de passe oublié"
- `resources/views/login/password_forget.blade.php` - Formulaire de demande
- `resources/views/login/new_password.blade.php` - Formulaire de nouveau mot de passe

### 3. Validation Implémentée

- Email doit être au format valide
- Email doit se terminer par `@gmail.com`
- Email doit exister dans la base de données
- Mot de passe : minimum 4 caractères, maximum 255
- Confirmation du mot de passe requise

### 4. Configuration Gmail (pour l'envoi réel des emails)

Pour recevoir les emails de réinitialisation, configurez SMTP Gmail dans le fichier `.env` :

```env
# Configuration Gmail
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=votre-email@gmail.com
MAIL_PASSWORD=votre-mot-de-passe-application
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=votre-email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

#### Comment obtenir un mot de passe application Gmail :
1. Allez sur votre compte Google
2. Sécurité > Validation en deux étapes
3. Mots de passe d'application
4. Créez un nouveau mot de passe d'application pour "Mail"
5. Utilisez ce mot de passe dans la configuration

### 5. currently, emails are logged

Par défaut, les emails sont enregistrés dans `storage/logs/laravel.log` au lieu d'être envoyés réellement. Cela permet de tester sans configuration SMTP.

Pour voir les emails de test :
```bash
tail -f storage/logs/laravel.log
```

### 6. Sécurité

- Token de réinitialisation à usage unique
- Expiration du token : 60 minutes (configurable dans `config/auth.php`)
- Mot de passe stocké de manière sécurisée avec Hash

### 7. Tests

1. Cliquez sur "Mot de passe oublié ?" sur la page de connexion
2. Entrez une adresse email valide (@gmail.com)
3. Vérifiez les logs ou l'email réel
4. Cliquez sur le lien de réinitialisation
5. Entrez un nouveau mot de passe
6. Connectez-vous avec le nouveau mot de passe
