<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { text-align: center; padding: 20px; background: #f8f9fa; border-radius: 8px 8px 0 0; }
        .logo { width: 80px; height: 80px; }
        .header-title { font-size: 24px; font-weight: bold; margin-top: 10px; color: #333; }
        .content { padding: 30px; background: #fff; border: 1px solid #e9ecef; }
        .button { display: inline-block; padding: 12px 24px; background: #007bff; color: #fff; text-decoration: none; border-radius: 4px; margin: 20px 0; }
        .footer { text-align: center; padding: 20px; background: #f8f9fa; border-radius: 0 0 8px 8px; font-size: 14px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('images/bx--bxs-smile.png') }}" alt="Price References" class="logo">
            <div class="header-title">Price References</div>
        </div>

        <div class="content">
            <h2>Bonjour {{ $username }},</h2>

            <p>Vous recevez cet email car nous avons reçu une demande de réinitialisation de mot de passe pour votre compte.</p>

            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ $url }}" class="button">Réinitialiser le mot de passe</a>
            </div>

            <p>Ce lien de réinitialisation expirera dans <strong>60 minutes</strong>.</p>

            <p style="margin-top: 30px;">Si vous n'avez pas demandé de réinitialisation de mot de passe, aucune action n'est requise.</p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Price References. Tous droits réservés.</p>
        </div>
    </div>
</body>
</html>
