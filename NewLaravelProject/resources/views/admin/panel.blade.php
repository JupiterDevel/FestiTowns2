<x-app-layout>
    <x-slot name="header">
        <h1 class="display-6 fw-bold text-primary mb-0">
            <i class="bi bi-shield-check me-2"></i>Admin Panel
        </h1>
    </x-slot>

    <div class="container">
        @php
            $activeTab = request()->get('tab', 'comments');
            // Determine which tabs to show based on user role
            $showComments = auth()->user()->isAdmin() || auth()->user()->isTownHall();
            $showUsers = auth()->user()->isAdmin();
            $showAdvertisements = auth()->user()->isAdmin();
            
            // Set default tab if requested tab is not available
            if ($activeTab === 'users' && !$showUsers) {
                $activeTab = 'comments';
            }
            if ($activeTab === 'advertisements' && !$showAdvertisements) {
                $activeTab = 'comments';
            }
            if ($activeTab === 'comments' && !$showComments) {
                $activeTab = $showUsers ? 'users' : ($showAdvertisements ? 'advertisements' : 'comments');
            }
        @endphp

        <!-- Bootstrap Tabs -->
        <ul class="nav nav-tabs mb-4" id="adminTabs" role="tablist">
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
                        <i class="bi bi-chat-dots me-1"></i>Moderate Comments
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
                        <i class="bi bi-people me-1"></i>Users
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
                        <i class="bi bi-badge-ad me-1"></i>Advertisements
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
        </div>
    </div>
</x-app-layout>

