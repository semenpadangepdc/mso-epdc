<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- FontAwesome Icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <!-- Vite Assets -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Select2 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

        <!-- Custom Select2 Styling -->
        <style>
            .select2-container--default .select2-selection--single {
                border: 2px solid #E5E7EB;
                border-radius: 8px;
                height: auto;
                padding: 0.5rem;
                transition: all 0.3s ease;
            }
            
            .select2-container--default.select2-container--focus .select2-selection--single {
                border-color: #DC2626;
                box-shadow: 0 0 0 3px #FEE2E2;
            }
            
            .select2-container--default .select2-selection--single .select2-selection__rendered {
                line-height: 1.5;
                padding-left: 0;
            }
            
            .select2-container--default .select2-selection--single .select2-selection__arrow {
                height: 100%;
            }
            
            .select2-dropdown {
                border: 2px solid #DC2626;
                border-radius: 8px;
            }
            
            .select2-container--default .select2-results__option--highlighted[aria-selected] {
                background-color: #DC2626;
            }
            
            .select2-search--dropdown .select2-search__field {
                border: 2px solid #E5E7EB;
                border-radius: 6px;
                padding: 0.5rem;
            }
            
            .select2-search--dropdown .select2-search__field:focus {
                border-color: #DC2626;
                outline: none;
            }
        </style>
        
        @stack('styles')
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Flash Messages -->
            @if(session('success'))
                <div class="alert alert-success" style="background: #D1FAE5; color: #065F46; padding: 1rem; border-radius: 8px; margin: 1rem;">
                    ✅ {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger" style="background: #FEE2E2; color: #991B1B; padding: 1rem; border-radius: 8px; margin: 1rem;">
                    ❌ {{ session('error') }}
                </div>
            @endif

            <!-- Page Content -->
            <main>
                @yield('content')
            </main>
        </div>

        <!-- Scripts Stack -->
        @stack('scripts')
    </body>
</html>