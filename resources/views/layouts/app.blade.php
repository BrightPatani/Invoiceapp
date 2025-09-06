<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Invoice App')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.15.0/dist/cdn.min.js"></script> --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        [x-cloak] { display: none !important; }
    </style>
     @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans">
    <nav class="bg-gradient-to-r from-[#003686] to-blue-800 shadow-xl border-b border-[#003686]/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex items-center space-x-3">
                        <div class="bg-[#FF6701] p-2 rounded-lg">
                            <i class="fas fa-file-invoice text-white text-lg"></i>
                        </div>
                        <h1 class="text-xl font-bold text-[#003686]">Fasticore Invoice</h1>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('invoices.index') }}" 
                       class="text-white/80 hover:text-white font-medium transition-all duration-200 flex items-center">
                        <i class="fas fa-list mr-2 text-[#FF6701]"></i>
                        All Invoices
                    </a>
                    <a href="{{ route('invoices.create') }}" 
                       class="bg-[#FF6701] hover:bg-[#FF6701]/90 text-white px-4 py-2 rounded-lg font-medium transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105 flex items-center">
                        <i class="fas fa-plus mr-2"></i>
                        Create Invoice
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
                 class="mb-4 bg-green-50 border-l-4 border-green-500 text-green-700 px-6 py-4 rounded-lg shadow-sm flex items-center">
                <i class="fas fa-check-circle text-green-500 mr-3 text-lg"></i>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 bg-red-50 border-l-4 border-red-500 text-red-700 px-6 py-4 rounded-lg shadow-sm">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-circle text-red-500 mr-3 text-lg mt-0.5"></i>
                    <div class="flex-1">
                        <h4 class="font-medium text-red-800 mb-2">Please fix the following errors:</h4>
                        <ul class="list-disc list-inside space-y-1">
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