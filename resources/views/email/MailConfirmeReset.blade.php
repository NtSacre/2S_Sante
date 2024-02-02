<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Message Réinitialiser mot de passe</title>
</head>
<body>
<p>Cliquez sur le lien ci-dessous pour réinitialiser votre mot de passe :</p>
    <a href="{{ route('reset.password.get', $token) }}">Réinitialiser le mot de passe</a>
</body>
</html>
