<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - Assetera</title>

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
            padding: 14px;
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

        <!-- LOGO -->
        <div class="mb-10">
            <a href="{{ url('/') }}">
                <img src="{{ asset('logo.png') }}" 
                    alt="Logo Assetera" 
                    class="h-28 w-auto">
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
    <div class="w-1/2 flex items-center justify-center py-10">

        <div class="card">

            <!-- TITLE -->
            <h2 class="text-xl font-semibold text-gray-800">
                Create Account
            </h2>

            <p class="text-sm text-gray-500 mt-2 mb-6">
                Register a new account as default role <span class="font-semibold text-gray-700">staff</span>.
            </p>

            <!-- FORM -->
            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <!-- NAME -->
                <div>
                    <label class="text-sm text-gray-600">Full Name</label>
                    <input type="text"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        autofocus
                        class="input mt-1"
                        placeholder="John Doe">

                    @error('name')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- USERNAME -->
                <div>
                    <label class="text-sm text-gray-600">Username</label>
                    <input type="text"
                        name="username"
                        value="{{ old('username') }}"
                        required
                        class="input mt-1"
                        placeholder="johndoe">

                    @error('username')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- EMAIL -->
                <div>
                    <label class="text-sm text-gray-600">Email</label>
                    <input type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        class="input mt-1"
                        placeholder="john.doe@assetera.com">

                    @error('email')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- PASSWORD -->
                <div>
                    <label class="text-sm text-gray-600">Password</label>
                    <input type="password"
                        name="password"
                        required
                        class="input mt-1"
                        placeholder="••••••••">

                    @error('password')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- CONFIRM PASSWORD -->
                <div>
                    <label class="text-sm text-gray-600">Confirm Password</label>
                    <input type="password"
                        name="password_confirmation"
                        required
                        class="input mt-1"
                        placeholder="••••••••">

                    @error('password_confirmation')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between text-sm pt-1">
                    <a href="{{ route('login') }}" class="text-primary hover:underline">
                        Already Registered?
                    </a>
                </div>

                <!-- BUTTON -->
                <button type="submit" class="btn mt-2">
                    Register
                </button>

            </form>

        </div>

    </div>

</div>

</body>
</html>
