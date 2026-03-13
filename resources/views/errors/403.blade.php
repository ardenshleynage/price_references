<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accès refusé - Price References</title>
    <link rel="icon" type="image/png" href="{{ asset('images/bx--bxs-smile.png') }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .error-container {
            background: white;
            border-radius: 20px;
            padding: 40px;
            text-align: center;
            max-width: 450px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .error-icon {
            font-size: 80px;
            margin-bottom: 20px;
        }

        .error-code {
            font-size: 72px;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 10px;
            line-height: 1;
        }

        .error-title {
            font-size: 24px;
            color: #333;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .error-message {
            font-size: 16px;
            color: #666;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .btn-home {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 500;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .btn-home:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.4);
        }

        @media (max-width: 480px) {
            .error-container {
                padding: 30px 20px;
            }

            .error-code {
                font-size: 56px;
            }

            .error-title {
                font-size: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="error-container">
        <div class="error-icon">🚫</div>
        <div class="error-code">403</div>
        <h1 class="error-title">Accès refusé</h1>
        <p class="error-message">
            @if(isset($message))
                {{ $message }}
            @else
                Vous n'avez pas l'autorisation d'accéder à cette page.<br>
                Cette section est réservée aux administrateurs supérieurs.
            @endif
        </p>
        <a href="{{ url('/') }}" class="btn-home">Retour à l'accueil</a>
    </div>
</body>

</html>
