<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Invoice App')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.15.0/dist/cdn.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        [x-cloak] { display: none !important; }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans">
<nav class="shadow-md border-b-4 border-[#003686]/20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <!-- Logo / Brand -->
            <div class="flex items-center">
                <div class="flex items-center space-x-3">
                    <div class="bg-[#FF6701] p-2 rounded-lg">
                        <i class="fas fa-file-invoice text-white text-lg"></i>
                    </div>
                    <h1 class="text-lg sm:text-xl font-bold text-[#003686]">Fasticore Invoice</h1>
                </div>
            </div>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center space-x-4">
                <a href="{{ route('invoices.index') }}" 
                   class="text-[#003686] font-medium transition-all duration-200 flex items-center transform hover:scale-105">
                    <i class="fas fa-list mr-2 text-[#FF6701] hover:text-[#003686]"></i>
                    All Invoices
                </a>
                <a href="{{ route('invoices.create') }}" 
                   class="bg-[#FF6701] hover:bg-[#FF6701]/90 text-white px-4 py-2 rounded-lg font-medium transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105 flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    Create Invoice
                </a>
            </div>

            <!-- Mobile Menu Button -->
            <div x-data="{ open: false }" class="md:hidden flex items-center">
                <button 
                    @click="open = !open" 
                    :aria-expanded="open.toString()"
                    aria-controls="mobile-menu"
                    class="text-[#003686] focus:outline-none focus:ring-2 focus:ring-[#FF6701] rounded-md p-2">
                    <i x-show="!open" class="fas fa-bars text-2xl"></i>
                    <i x-show="open" class="fas fa-times text-2xl"></i>
                </button>

                <!-- Mobile Menu -->
                <div x-cloak x-show="open" id="mobile-menu" class="absolute top-16 right-4 left-4 bg-white border border-gray-200 rounded-lg shadow-md z-50">
                    <div class="px-4 py-3 space-y-2">
                        <a href="{{ route('invoices.index') }}" 
                           class="text-[#003686] font-medium text-base transition-all duration-200 flex items-center px-4 py-3 rounded-md hover:bg-gray-100">
                            <i class="fas fa-list mr-3 text-[#FF6701] text-lg"></i>
                            All Invoices
                        </a>
                        <a href="{{ route('invoices.create') }}" 
                           class="bg-[#FF6701] hover:bg-[#FF6701]/90 text-white font-medium text-base transition-all duration-200 flex items-center px-4 py-3 rounded-md">
                            <i class="fas fa-plus mr-3 text-lg"></i>
                            Create Invoice
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>


    <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
                 class="mb-4 bg-green-50 border-l-4 border-green-500 text-green-700 px-4 sm:px-6 py-4 rounded-lg shadow-sm flex items-center">
                <i class="fas fa-check-circle text-green-500 mr-3 text-lg"></i>
                <span class="font-medium text-sm sm:text-base">{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 bg-red-50 border-l-4 border-red-500 text-red-700 px-4 sm:px-6 py-4 rounded-lg shadow-sm">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-circle text-red-500 mr-3 text-lg mt-0.5"></i>
                    <div class="flex-1">
                        <h4 class="font-medium text-red-800 mb-2 text-sm sm:text-base">Please fix the following errors:</h4>
                        <ul class="list-disc list-inside space-y-1 text-sm sm:text-base">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>