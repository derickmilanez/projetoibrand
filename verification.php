<html>
<head>
    <title>Sistema</title>
    <meta name="viewport" content="initial-scale=1.0">
    <meta charset="utf-8">
    <style>
        html, body{
            height: auto;
        }

        body{
            background: #403F3F;
            font-family: 'Roboto', sans-serif;

            display: flex;
            flex-direction: column;
        }

        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .header {
            width: 100%;
            height: 70px;
            background: #E57B19;
            position: fixed;
            display: flex;
            align-items: center;
        }

        .header p {
            margin-left: auto;
            margin-right: auto;
            font-size: 1.4rem;
            color: #ffffff;
        }

        .form {
            margin-top: 5%;
            margin-left: auto;
            margin-right: auto;
            width: 25%;
            height: 30%;
            border: 1px solid #ffffff;
            border-radius: 15px;

            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .form p{
            text-align: center;
            color: #ffffff;
            margin-top: 1rem;
        }

        .title {
            margin-top: 10%;
            color: #ff8717;
            font-size: 1.5rem;
            text-align: center;
        }

        .button {
            background-color: #E57B19;
            border: none;
            color: white;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            
        }

        .input{
            width: 80%;
            padding: 12px 20px;
            margin: 4px 0;
            box-sizing: border-box;
            font-size: 16;
            border: 3px solid #E57B19;
        }

        .input:focus{
            border: 3px solid #E57B19;
        }
    </style>
</head>
<body>
<header class="header">
    <p>iBrand</p>
</header>
<h3 class="title">Admin,  confirme seu <br>acesso</h3>
<form method="post" action="verifying.php" class="form">
    <p>Digite a senha:</p><br>
    <input type="password" name="senha" class="input"><br>
    <input type="submit" class="button">
</form>
</body>
</html>