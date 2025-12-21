<nav class="navbar navbar-expand-lg navbar-dark navbar-enhanced" style="background-color: #FEB101;">
    <div class="container">
        <!-- Brand -->
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}" aria-label="Elalmadelafiesta">
            <img src="{{ asset('storage/fiesta-logo.png') }}" alt="Elalmadelafiesta" style="height: 40px; width: auto; display: block;">
            <span class="brand-text" style="font-size: 1.5rem; color: #E5483B;">Elalmadelafiesta</span>
        </a>

        <!-- Toggler -->
        <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon">
                <span></span>
            </span>
        </button>

        <!-- Navigation Links -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('localities.*') ? 'active' : '' }}" href="{{ route('localities.index') }}">Localidades</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('festivities.*') ? 'active' : '' }}" href="{{ route('festivities.index') }}">Festividades</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('festivities.most-voted') ? 'active' : '' }}" href="{{ route('festivities.most-voted') }}">Las Más Votadas</a>
                </li>
                @auth
                    @if(auth()->user()->isAdmin() || auth()->user()->isTownHall())
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.*') || request()->routeIs('comments.*') || request()->routeIs('users.*') || request()->routeIs('advertisements.*') ? 'active' : '' }}" href="{{ route('admin.panel') }}">Admin</a>
                        </li>
                    @endif
                @endauth
            </ul>

            <!-- User Menu -->
            <ul class="navbar-nav ms-auto">
                @auth
                    <li class="nav-item dropdown">
                        @php($user = Auth::user())
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-2"></i>
                            {{ $user->name }}
                            @if($user->isAdmin() || $user->isTownHall())
                                <span class="badge ms-2 text-uppercase" style="background-color: #111827; color: #F9FAFB; font-size: 0.68rem;">
                                    {{ $user->isAdmin() ? 'Admin' : 'Town Hall' }}
                                </span>
                            @endif
                        </a>
                               <ul class="dropdown-menu dropdown-menu-end">
                                   <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Perfil</a></li>
                            <li><hr class="dropdown-divider"></li>
                                   <li>
                                       <form method="POST" action="{{ route('logout') }}">
                                           @csrf
                                           <button type="submit" class="dropdown-item">Cerrar Sesión</button>
                                       </form>
                                   </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item me-2">
                        <a class="btn btn-sm" href="{{ route('login') }}"
                           style="border-radius: 999px; border: 1px solid #FFFFFF; color: #FFFFFF; background: transparent; padding: 0.35rem 1rem; font-weight: 500;">
                            Iniciar Sesión
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-sm" href="{{ route('register') }}"
                           style="border-radius: 999px; background-color: #FFFFFF; color: #E5483B; padding: 0.35rem 1rem; font-weight: 600; border: none;">
                            Registrarse
                        </a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
