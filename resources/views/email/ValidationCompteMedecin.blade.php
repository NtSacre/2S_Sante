<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Message de Validation de Compte Médecin</title>
</head>

<body>

    <img src="https://i.ibb.co/2s5RXmM/Use-Case-Gestion-Rendez-Vous.png" alt="" style="width: 100px; height:100px; margin: 0 auto">

    <p><strong>Objet :</strong> 2S Santé - Validation de Compte Médecin</p>

    <p>
        Cher(e) {{ $medecin->nom }},
    </p>

    <p>
        Nous sommes ravis de vous accueillir sur 2S Santé en tant que professionnel de la santé.
        Votre compte de médecin a été accepté, et vous êtes désormais membre de notre communauté.
    </p>

    <p>
        Voici quelques informations importantes pour commencer :
    </p>

    <ul>
        <li><strong>Nom d'utilisateur :</strong> {{ $medecin->nom }}</li>
        <li><strong>Adresse e-mail :</strong> {{ $medecin->email }}</li>
    </ul>

    <p>
        Connectez-vous dès maintenant pour explorer les fonctionnalités
        de 2Santé et bénéficier des avantages réservés aux médecins.
    </p>

    <p>
        Si vous avez des questions ou rencontrez des problèmes,
        n'hésitez pas à nous contacter à sacrentandou2.0@gmail.com.
    </p>

    <p>
        Bienvenue à bord !
    </p>




    <p>Cordialement,</p>

    <p>L'équipe de 2S Santé</p>
</body>

</html>