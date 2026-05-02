<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Sign In | Personal Apparel</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            font-family: Arial, Helvetica, sans-serif;
            background: linear-gradient(180deg, #0f172a 0%, #020617 100%);
            color: #f8fafc;
            display: grid;
            place-items: center;
            padding: 20px;
        }
        .card {
            width: min(440px, 100%);
            background: #111827;
            border: 1px solid rgba(148, 163, 184, 0.24);
            border-radius: 14px;
            padding: 24px;
        }
        h1 {
            margin: 0 0 6px;
            font-size: 1.5rem;
        }
        p {
            margin: 0 0 18px;
            color: #cbd5e1;
            font-size: 0.92rem;
        }
        label {
            display: block;
            margin-bottom: 6px;
            font-size: 0.9rem;
            font-weight: 600;
        }
        input {
            width: 100%;
            padding: 10px 12px;
            border-radius: 10px;
            border: 1px solid rgba(148, 163, 184, 0.3);
            background: #0b1220;
            color: #f8fafc;
            margin-bottom: 14px;
        }
        button {
            width: 100%;
            padding: 11px 14px;
            border-radius: 10px;
            border: 0;
            font-weight: 700;
            background: #22c55e;
            color: #052e16;
            cursor: pointer;
        }
        .links {
            margin-top: 12px;
            text-align: center;
            font-size: 0.9rem;
        }
        a { color: #86efac; text-decoration: none; }
    </style>
</head>
<body>
    <div class="card">
        <h1>Seller Sign In</h1>
        <p>This sign-in is used to identify seller access for your personal apparel page.</p>
        @if ($errors->any())
            <p style="color:#fca5a5; margin-bottom:12px;">{{ $errors->first() }}</p>
        @endif

        <form method="post" action="{{ route('vendor.signin.submit') }}">
            @csrf
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="seller@email.com" required>

            <label for="password">Password</label>
            <input id="password" type="password" name="password" placeholder="Enter password" required>

            <button type="submit">Identify Seller</button>
        </form>

        <div class="links">
            <a href="{{ url('/') }}">Back to landing page</a>
        </div>
    </div>
</body>
</html>
