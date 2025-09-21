<!DOCTYPE html>
<html lang="en">
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
            background: linear-gradient(120deg, #f4f4f4 0%, #e0e7ff 100%);
        }
        header, footer {
            background-color: #22223b;
            color: #fff;
            text-align: center;
            padding: 1.5em 0;
            letter-spacing: 1px;
            font-size: 1.2em;
        }
        main {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .form-container {
            background: #fff;
            padding: 2.5em 2em;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(34,34,59,0.12);
            max-width: 400px;
            width: 100%;
        }
        form div {
            margin-bottom: 1.5em;
        }
        label {
            display: block;
            margin-bottom: 0.5em;
            font-weight: 500;
            color: #22223b;
        }
        input[type="email"], input[type="password"] {
            width: 100%;
            padding: 0.75em;
            border: 1px solid #c9c9c9;
            border-radius: 8px;
            font-size: 1em;
            transition: border-color 0.2s;
        }
        input[type="email"]:focus, input[type="password"]:focus {
            border-color: #4f46e5;
            outline: none;
        }
        button {
            width: 100%;
            padding: 0.75em;
            background: linear-gradient(90deg, #4f46e5 0%, #22223b 100%);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 1em;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        button:hover {
            background: linear-gradient(90deg, #22223b 0%, #4f46e5 100%);
        }
    </style>
</head>
<body>
    <header>
        <!-- Add your site header here -->
        Login and Registration
    </header>
    <main>
        {{ $slot }}
    </main>
    <footer>
        <!-- Add your site footer here -->
        &copy; {{ date('Y') }} Bazsa. All rights reserved.
    </footer>
</body>
</html>