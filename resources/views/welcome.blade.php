<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Assetera</title>

  <!-- Tailwind CDN -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Custom CSS -->
  <style>
    body {
      background-color: #f5f6f8;
      font-family: 'Segoe UI', sans-serif;
    }

    .text-primary {
      color: #6c8dbf;
      size: 1.25rem;
    }

    .bg-primary {
      background-color: #6c8dbf;
    }

    .border-primary {
      border-color: #6c8dbf;
    }

    .badge {
      background-color: #dbe6f6;
      color: #6c8dbf;
    }
  </style>
</head>

<body>

  <!-- Navbar -->
  <header class="flex justify-between items-center px-16 py-5">
    <div class="text-4xl font-bold text-primary">
      ASSETERA
    </div>

    <nav class="space-x-6 text-gray-700 font-medium">
      <a href="#">Stock</a>
      <a href="#">Kitchen Equipment</a>
      <a href="#">Logistics</a>
      <a href="#">Reports</a>
    </nav>

    <div class="flex items-center space-x-4">
      <a href="{{ route('login') }}" class="text-gray-700">Sign In</a>
      <a href="{{ route('register') }}" class="bg-primary text-white px-4 py-2 rounded-lg">Register</a>
        </div>
    </header>

  <!-- Hero -->
  <section class="px-16 py-20 max-w-2xl">

    <!-- Badge -->
    <div class="badge inline-block px-4 py-1 rounded-full text-sm mb-5">
      Institutional Grade Management
    </div>

    <!-- Title -->
    <h1 class="text-5xl font-bold text-primary leading-tight mb-6">
      Managing Assets <br>
      for a Nutritious <br>
      Future.
    </h1>

    <!-- Description -->
    <p class="text-gray-600 leading-relaxed mb-8">
      Institutional-grade asset management for Dapur MBG across Indonesia.
      Real-time tracking for food stocks, kitchen logistics, and equipment maintenance.
    </p>

    <!-- Buttons -->
    <div class="flex space-x-4">
      <button class="bg-primary text-white px-6 py-3 rounded-lg">
        Register Kitchen
      </button>

      <button class="border border-primary text-primary px-6 py-3 rounded-lg">
        Learn More
      </button>
    </div>

  </section>

</body>
</html>