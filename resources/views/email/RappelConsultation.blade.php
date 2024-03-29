<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rappel  de rendez-vous</title>
    <style>
                body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333333;
        }
        p {
            color: #666666;
        }
        .button {
            display: inline-block;
            font-size: 14px;
            color: #ffffff;
            text-decoration: none;
            background-color: #3490dc;
            padding: 10px 20px;
            border-radius: 5px;
        }

    </style>
</head>
<body>

    <img src="https://i.ibb.co/2s5RXmM/Use-Case-Gestion-Rendez-Vous.png"
     alt="" style="width: 100px; height:100px; margin: 0 auto">
    
    <h1><strong>Objet :</strong> 2S Santé - Rappel de votre Consultation</h1>

    <div class="container">
       
        <p>Bonjour {{ $consultation->user->nom }},</p>

        <p>Ceci est un rappel pour votre consultation prévue pour le {{ $consultation->date }} à {{ $consultation->heure }}.</p>
    <p>Merci de vous connecter à l'heure.</p>

        <p>Cordialement,<br>2S Santé</p>
    </div>


</body>
</html>
