<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Assetera</title>

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            background: #f4f6fa;
            font-family: 'Inter', 'Segoe UI', sans-serif;
        }

        .primary { color: #6b87b5; }
        .bg-primary { background-color: #6b87b5; }

        .card {
            background: #f8fafc;
            border-radius: 20px;
            padding: 48px;
            width: 480px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        }

        .input {
            background: #eef2f7;
            border-radius: 10px;
            padding: 16px;
            font-size: 14px;
            width: 100%;
        }

        .input:focus {
            outline: none;
            box-shadow: 0 0 0 2px #6b87b5;
        }

        .btn {
            background: #6b87b5;
            color: white;
            padding: 16px;
            border-radius: 10px;
            width: 100%;
            font-size: 15px;
            font-weight: 600;
            transition: 0.2s;
        }

        .btn:hover {
            opacity: 0.9;
        }
    </style>
</head>

<body>

<div class="min-h-screen flex">

    <!-- LEFT SIDE -->
    <div class="w-1/2 flex flex-col justify-center px-36 relative">

       
        <!-- LOGO (NO BACKGROUND) -->
        <div class="mb-10">
            <a href="{{ url('/') }}">
                <img src="{{ asset('logo.png') }}" 
                    alt="Logo Assetera" 
                    class="h-28 w-auto"> <!-- 🔥 diperbesar -->
            </a>
        </div>

        <!-- LINE -->
        <div class="w-20 h-[3px] bg-primary mb-8 rounded-full"></div>

        <!-- DESC -->
        <p class="primary text-base leading-relaxed max-w-md mb-12">
            Digital Infrastructure for the National Nutritious Meal Program.
            Securely managing assets for Indonesia's future.
        </p>

        <!-- FEATURE -->
        <div class="flex items-start gap-4">
            <div class="bg-gray-200 p-3 rounded-lg text-primary text-lg">✔</div>
            <div>
                <p class="font-semibold text-base text-gray-800">
                    Institutional Security
                </p>
                <p class="text-sm text-gray-500">
                    Encrypted protocols for national logistics data.
                </p>
            </div>
        </div>

        <!-- FOOTER -->
        <div class="absolute bottom-8 text-sm text-gray-400">
            © 2024 Assetera - Dapur MBG.
        </div>
    </div>


    <!-- RIGHT SIDE -->
    <div class="w-1/2 flex items-center justify-center">

        <div class="card">

            <!-- TITLE -->
            <h2 class="text-xl font-semibold text-gray-800">
                Access Control
            </h2>

            <p class="text-sm text-gray-500 mt-2 mb-6">
                Authorized personnel only. Please verify your credentials.
            </p>

            <!-- SESSION -->
            @if (session('status'))
                <div class="mb-4 text-sm text-green-600">
                    {{ session('status') }}
                </div>
            @endif

            <!-- FORM -->
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- EMAIL -->
                <div>
                    <label class="text-sm text-gray-600">Email</label>
                    <input type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        class="input mt-2"
                        placeholder="admin@assetera.com">

                    @error('email')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- PASSWORD -->
                <div>
                    <div class="flex justify-between text-sm text-gray-600">
                        <label>Access Password</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                               class="text-primary">
                                Forgot Password?
                            </a>
                        @endif
                    </div>

                    <input type="password"
                        name="password"
                        required
                        class="input mt-2"
                        placeholder="••••••••">

                    @error('password')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- INFO -->
                <div class="border border-gray-200 rounded-lg p-4 text-center text-sm text-gray-400">
                    No operational kitchen assigned yet?
                </div>

                <!-- BUTTON -->
                <button type="submit" class="btn">
                    Login
                </button>

            </form>

        </div>

    </div>

</div>

</body>
</html>