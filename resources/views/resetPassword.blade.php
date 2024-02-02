<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
body {
    margin: 0;
    padding: 0;
    font-family: Arial, sans-serif;
    background-color: #fff; /* Blanc */
    color: #333; /* Noir */
}

.container {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100vh;
}

.reset-form {
    background-color: #ff5555; /* Rouge */
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    width: 300px;
    text-align: center;
}

h2 {
    color: #fff; /* Blanc */
}

label {
    display: block;
    margin-top: 10px;
    color: #fff; /* Blanc */
}

input {
    width: 100%;
    padding: 8px;
    margin-top: 5px;
    margin-bottom: 10px;
    box-sizing: border-box;
}

button {
    background-color: #fff; /* Blanc */
    color: #ff5555; /* Rouge */
    padding: 10px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    width: 100%;
    font-size: 16px;
}

button:hover {
    background-color: #eee;
}

    </style>
</head>
<body>
        <div class="container">
        <form class="reset-form" action="{{route('reset.password.post')}}" method="post" >
            @csrf
            <h2>Reset Password</h2>
          
            <input type="password" id="password" name="password" placeholder="Password" required>
            <input type="password" id="password_confirmation" name="password_confirmation"
            placeholder="Password Confirmation " required>
            <button type="submit">Envoyer</button>
        </form>
    </div>
</body>
</html>
