<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Message de Prise de rendez-vous</title>
</head>
<body>

    <img src="https://i.ibb.co/2s5RXmM/Use-Case-Gestion-Rendez-Vous.png" alt="" style="width: 100px; height:100px; margin: 0 auto">
    
    <p><strong>Objet :</strong> S2S Santé - Acceptation de votre Consultation</p>

<p>Cher(e) {{ $patient->nom }},</p>

<p>Nous sommes ravis de vous informer que votre demande de consultation en ligne a été
    acceptée par le médecin. Voici les détails de votre consultation :</p>

<ul>
    <li><strong>Date et Heure :</strong> {{ $planning->jour }} à {{$consultation->heure}}</li>
    <li><strong>Médecin :</strong> {{ $medecin->nom }}</li>
    <li><strong>Motif de la Consultation :</strong> {{ $consultation->motif }}</li>
</ul>

<p>Veuillez vous connecter sur Whatsapp
à l'heure prévue de la consultation. Assurez-vous d'être prêt(e) avec toutes les
informations nécessaires pour une expérience fluide.</p>

<p>N'oubliez pas que votre satisfaction et votre bien-être sont notre priorité
    absolue. Si vous avez des questions ou des préoccupations, n'hésitez pas à
    nous contacter.</p>


<p>Cordialement,</p>

<p>L'équipe de S2S Santé</p>
</body>
</html>
