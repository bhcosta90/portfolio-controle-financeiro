<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset(mix('css/app.css')) }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        @auth
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    Cadastros
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="{{ route('entidade.fornecedor.index', ['tenant' => tenant()]) }}">Fornecedores</a></li>
                                    <li><a class="dropdown-item" href="{{ route('entidade.cliente.index', ['tenant' => tenant()]) }}">Clientes</a></li>
                                    <li><a class="dropdown-item" href="{{ route('entidade.banco.index', ['tenant' => tenant()]) }}">Bancos</a></li>
                                    <li><a class="dropdown-item" href="{{ route('cobranca.contabancaria.index', ['tenant' => tenant()]) }}">Conta bancária</a></li>
                                    <li><a class="dropdown-item" href="{{ route('cobranca.frequencia.index', ['tenant' => tenant()]) }}">Frequências</a></li>
                                    <li><a class="dropdown-item" href="{{ route('cobranca.formapagamento.index', ['tenant' => tenant()]) }}">Forma PGTO</a></li>
                                    {{-- <li>
                                        <hr class="dropdown-divider">
                                    </li> --}}
                                </ul>
                            </li>

                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    Movimentações
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="{{ route('cobranca.conta.pagar.index', ['tenant' => tenant(), 'data_inicio' => (new \Carbon\Carbon)->firstOfMonth()->format('Y-m-d'), 'data_final' => (new \Carbon\Carbon)->endOfMonth()->format('Y-m-d')]) }}">Contas a pagar</a></li>
                                    <li><a class="dropdown-item" href="{{ route('cobranca.conta.receber.index', ['tenant' => tenant(), 'data_inicio' => (new \Carbon\Carbon)->firstOfMonth()->format('Y-m-d'), 'data_final' => (new \Carbon\Carbon)->endOfMonth()->format('Y-m-d')]) }}">Contas a receber</a></li>
                                    <li><a class="dropdown-item" href="{{ route('cobranca.conta.transferencia.create', ['tenant' => tenant()]) }}">Tranferência entre contas</a></li>
                                    <li><a class="dropdown-item" href="{{ route('cobranca.contabancaria.movimentacao.index', ['tenant' => tenant(), 'data_inicio' => (new \Carbon\Carbon)->now()->format('Y-m-d'), 'data_final' => (new \Carbon\Carbon)->now()->format('Y-m-d')]) }}">Movimentação bancária</a></li>
                                    {{-- <li>
                                        <hr class="dropdown-divider">
                                    </li> --}}
                                </ul>
                            </li>

                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    Relatórios
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="{{ route('cobranca.relatorio.movimentacao.index', tenant())}}">Movimentação</a></li>
                                    {{-- <li>
                                        <hr class="dropdown-divider">
                                    </li> --}}
                                </ul>
                            </li>
                        @endif
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>

    <!-- Scripts -->
    <script src="{{ asset(mix('js/vendor.js')) }}"></script>
    @yield('js')
    @toastr_render

</body>
</html>
