<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — MEDLINK</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style type="text/tailwindcss">
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 antialiased min-h-screen">

    <header class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
            <div class="font-bold text-green-700 text-lg">MEDLINK Dashboard</div>
            
            <div class="flex items-center gap-4">
                <div class="text-sm">
                    <span class="text-gray-500">Logged in as:</span>
                    <span class="font-semibold">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</span>
                    <span class="ml-1 inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20 uppercase">
                        {{ auth()->user()->role }}
                    </span>
                </div>
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm font-semibold text-red-600 hover:text-red-800 transition-colors">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-6 py-12">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
            <h1 class="text-2xl font-bold mb-2">Welcome to the {{ ucfirst(auth()->user()->role) }} Portal</h1>
            <p class="text-gray-500">This is a temporary dashboard to prove that the login and role verification works.</p>
        </div>
    </main>

</body>
</html>
