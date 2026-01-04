<x-app-layout>
    <x-slot name="header">
        <h1 class="h2 mb-0 fw-bold" style="color:#1F2937;">
            <i class="bi bi-shield-check me-2"></i>Panel de administración
        </h1>
    </x-slot>

    <style>
        /* Extend admin panel background to footer on desktop */
        @media (min-width: 768px) {
            main {
                display: flex;
                flex-direction: column;
            }
            
            main > div[style*="radial-gradient"] {
                flex: 1;
                display: flex;
                flex-direction: column;
                margin-bottom: 0 !important;
            }
            
            main > div[style*="radial-gradient"] > .container {
                flex: 1;
                display: flex;
                flex-direction: column;
            }
        }
    </style>

    <div style="background: radial-gradient(circle at top, rgba(254,177,1,0.12), #F3F4F6); margin: -1.5rem 0 -3rem 0; padding: 2rem 0 3rem 0;">
        <div class="container">
        @php
            $activeTab = request()->get('tab', 'comments');
            // Determine which tabs to show based on user role
            $showComments = auth()->user()->isAdmin() || auth()->user()->isTownHall();
            $showUsers = auth()->user()->isAdmin();
            $showAdvertisements = auth()->user()->isAdmin();
            $showVoting = auth()->user()->isAdmin();
            $showContact = auth()->user()->isAdmin();
            $showFestivities = auth()->user()->isAdmin();
            
            // Set default tab if requested tab is not available
            if ($activeTab === 'users' && !$showUsers) {
                $activeTab = 'comments';
            }
            if ($activeTab === 'advertisements' && !$showAdvertisements) {
                $activeTab = 'comments';
            }
            if ($activeTab === 'voting' && !$showVoting) {
                $activeTab = 'comments';
            }
            if ($activeTab === 'contact' && !$showContact) {
                $activeTab = 'comments';
            }
            if ($activeTab === 'festivities' && !$showFestivities) {
                $activeTab = 'comments';
            }
            if ($activeTab === 'comments' && !$showComments) {
                $activeTab = $showUsers ? 'users' : ($showAdvertisements ? 'advertisements' : ($showVoting ? 'voting' : ($showContact ? 'contact' : ($showFestivities ? 'festivities' : 'comments'))));
            }
        @endphp

        <!-- Bootstrap Tabs + Content Panel -->
        <div class="bg-white rounded-3 shadow-sm p-3 p-md-4">
        <ul class="nav nav-tabs profile-tabs mb-4" id="adminTabs" role="tablist">
            @if($showComments)
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $activeTab === 'comments' ? 'active' : '' }}" 
                            id="comments-tab" 
                            data-bs-toggle="tab" 
                            data-bs-target="#comments" 
                            type="button" 
                            role="tab" 
                            aria-controls="comments" 
                            aria-selected="{{ $activeTab === 'comments' ? 'true' : 'false' }}">
                        <i class="bi bi-chat-dots me-1"></i>Comentarios
                    </button>
                </li>
            @endif
            @if($showUsers)
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $activeTab === 'users' ? 'active' : '' }}" 
                            id="users-tab" 
                            data-bs-toggle="tab" 
                            data-bs-target="#users" 
                            type="button" 
                            role="tab" 
                            aria-controls="users" 
                            aria-selected="{{ $activeTab === 'users' ? 'true' : 'false' }}">
                        <i class="bi bi-people me-1"></i>Usuarios
                    </button>
                </li>
            @endif
            @if($showAdvertisements)
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $activeTab === 'advertisements' ? 'active' : '' }}" 
                            id="advertisements-tab" 
                            data-bs-toggle="tab" 
                            data-bs-target="#advertisements" 
                            type="button" 
                            role="tab" 
                            aria-controls="advertisements" 
                            aria-selected="{{ $activeTab === 'advertisements' ? 'true' : 'false' }}">
                        <i class="bi bi-badge-ad me-1"></i>Anuncios
                    </button>
                </li>
            @endif
            @if($showVoting)
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $activeTab === 'voting' ? 'active' : '' }}" 
                            id="voting-tab" 
                            data-bs-toggle="tab" 
                            data-bs-target="#voting" 
                            type="button" 
                            role="tab" 
                            aria-controls="voting" 
                            aria-selected="{{ $activeTab === 'voting' ? 'true' : 'false' }}">
                        <i class="bi bi-heart me-1"></i>Votaciones
                    </button>
                </li>
            @endif
            @if($showFestivities)
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $activeTab === 'festivities' ? 'active' : '' }}" 
                            id="festivities-tab" 
                            data-bs-toggle="tab" 
                            data-bs-target="#festivities" 
                            type="button" 
                            role="tab" 
                            aria-controls="festivities" 
                            aria-selected="{{ $activeTab === 'festivities' ? 'true' : 'false' }}">
                        <i class="bi bi-calendar-event me-1"></i>Festividades
                    </button>
                </li>
            @endif
            @if($showContact)
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $activeTab === 'contact' ? 'active' : '' }}" 
                            id="contact-tab" 
                            data-bs-toggle="tab" 
                            data-bs-target="#contact" 
                            type="button" 
                            role="tab" 
                            aria-controls="contact" 
                            aria-selected="{{ $activeTab === 'contact' ? 'true' : 'false' }}">
                        <i class="bi bi-envelope me-1"></i>Contacto
                    </button>
                </li>
            @endif
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="adminTabsContent">
            @if($showComments)
                <div class="tab-pane fade {{ $activeTab === 'comments' ? 'show active' : '' }}" 
                     id="comments" 
                     role="tabpanel" 
                     aria-labelledby="comments-tab">
                    @include('admin.partials.comments')
                </div>
            @endif

            @if($showUsers)
                <div class="tab-pane fade {{ $activeTab === 'users' ? 'show active' : '' }}" 
                     id="users" 
                     role="tabpanel" 
                     aria-labelledby="users-tab">
                    @include('admin.partials.users')
                </div>
            @endif

            @if($showAdvertisements)
                <div class="tab-pane fade {{ $activeTab === 'advertisements' ? 'show active' : '' }}" 
                     id="advertisements" 
                     role="tabpanel" 
                     aria-labelledby="advertisements-tab">
                    @include('admin.partials.advertisements')
                </div>
            @endif

            @if($showVoting)
                <div class="tab-pane fade {{ $activeTab === 'voting' ? 'show active' : '' }}" 
                     id="voting" 
                     role="tabpanel" 
                     aria-labelledby="voting-tab">
                    @include('admin.partials.voting')
                </div>
            @endif

            @if($showFestivities)
                <div class="tab-pane fade {{ $activeTab === 'festivities' ? 'show active' : '' }}" 
                     id="festivities" 
                     role="tabpanel" 
                     aria-labelledby="festivities-tab">
                    @include('admin.partials.festivities')
                </div>
            @endif

            @if($showContact)
                <div class="tab-pane fade {{ $activeTab === 'contact' ? 'show active' : '' }}" 
                     id="contact" 
                     role="tabpanel" 
                     aria-labelledby="contact-tab">
                    @include('admin.partials.contact')
                </div>
            @endif
        </div>
        </div>
    </div>
        </div>
        </div>
    </div>
</x-app-layout>
