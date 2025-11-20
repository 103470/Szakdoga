<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login and Registration</title>
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(120deg, #f0f0f0 0%, #d6d6d6 100%);
            color: #222;
        }

        /* HEADER */
        header {
            background-color: #2b2b2b;
            color: #eaeaea;
            text-align: center;
            padding: 1.2em 0;
            font-size: 1.1em;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        }

        /* MAIN */
        main {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2em 1em;
        }

        /* FORM CONTAINER */
        .form-container {
            background: #fff;
            padding: 2.5em 2em;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.08);
            max-width: 420px;
            width: 100%;
        }

        form div {
            margin-bottom: 1.2em;
        }

        label {
            display: block;
            margin-bottom: 0.4em;
            font-weight: 500;
            color: #333;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 0.75em;
            border: 1px solid #bdbdbd;
            border-radius: 6px;
            font-size: 1em;
            transition: border-color 0.2s, box-shadow 0.2s;
            background-color: #fafafa;
        }

        input:focus {
            border-color: #5a5a5a;
            box-shadow: 0 0 4px rgba(0,0,0,0.1);
            outline: none;
        }

        /* BUTTONS */
        button {
            width: 100%;
            padding: 0.75em;
            background: linear-gradient(90deg, #3a3a3a 0%, #1f1f1f 100%);
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 1em;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.1s ease;
        }

        button:hover {
            background: linear-gradient(90deg, #505050 0%, #2c2c2c 100%);
            transform: translateY(-2px);
        }

        /* FOOTER */
        footer {
            background-color: #2b2b2b;
            color: #ccc;
            text-align: center;
            padding: 1em 0;
            font-size: 0.9em;
            border-top: 1px solid #444;
        }
    </style>
</head>
<body>
    <header style="position: relative; display: flex; align-items: center; justify-content: center; padding: 1.2em 1em; background-color: #2b2b2b; color: #eaeaea; box-shadow: 0 2px 6px rgba(0,0,0,0.15);">

        <a href="{{ route('home') }}" style="position: absolute; left: 1em;">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height:50px;">
        </a>

        <div style="font-weight: bold; text-align: center;">
            Bejelentkezés és Regisztráció
        </div>
    </header>




    <main>
        {{ $slot }}
    </main>

    <footer>
        &copy; {{ date('Y') }} B + M Autóalkatrész Webshop. Minden jog fenntartva.
    </footer>
</body>
</html>
