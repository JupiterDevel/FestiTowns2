<x-app-layout>
    <!-- Compact Header Section -->
    <div class="header-most-voted">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="header-content">
                        <div class="d-flex align-items-center justify-content-center mb-3">
                            <h1 class="display-5 fw-bold text-white mb-0 welcome-title">
                                <span class="char-shake">🔥</span><span class="char-shake"> </span><span class="char-shake">L</span><span class="char-shake">a</span><span class="char-shake">s</span><span class="char-shake"> </span><span class="char-shake">M</span><span class="char-shake">á</span><span class="char-shake">s</span><span class="char-shake"> </span><span class="char-shake">V</span><span class="char-shake">o</span><span class="char-shake">t</span><span class="char-shake">a</span><span class="char-shake">d</span><span class="char-shake">a</span><span class="char-shake">s</span>
                            </h1>
                            <div class="voting-info-tooltip ms-3">
                                <button type="button" 
                                        class="btn btn-link text-white p-0 voting-info-btn" 
                                        id="votingInfoBtn">
                                    <i class="bi bi-info-circle fs-5"></i>
                                </button>
                                <div class="voting-info-popup" id="votingInfoPopup">
                                    <div class="voting-info-header">
                                        <i class="bi bi-star me-2"></i>
                                        <strong>Regla de votación</strong>
                                        <button type="button" class="btn-close-popup" id="closeVotingInfo">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    </div>
                                    <div class="voting-info-body">
                                        <p class="mb-0">
                                            <i class="bi bi-person-check me-2 voting-info-icon"></i>
                                            Cada usuario puede votar una vez al día.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Tab Navigation -->
                        @php
                            $activeTab = 'nacional';
                            if (request()->has('tab')) {
                                $activeTab = request()->get('tab');
                            } elseif (request()->has('community')) {
                                $activeTab = 'regional';
                            } elseif (request()->has('province')) {
                                $activeTab = 'provincial';
                            }
                        @endphp
                        <div class="ranking-tabs-header">
                            <div class="d-flex gap-2 justify-content-center" role="tablist">
                                <button class="ranking-tab-btn {{ $activeTab === 'nacional' ? 'active' : '' }}" 
                                        data-tab="nacional" 
                                        type="button"
                                        role="tab"
                                        aria-selected="{{ $activeTab === 'nacional' ? 'true' : 'false' }}">
                                    <i class="bi bi-flag-fill me-2"></i>
                                    <span class="fw-bold">Nacional</span>
                                </button>
                                <button class="ranking-tab-btn {{ $activeTab === 'regional' ? 'active' : '' }}" 
                                        data-tab="regional" 
                                        type="button"
                                        role="tab"
                                        aria-selected="{{ $activeTab === 'regional' ? 'true' : 'false' }}">
                                    <i class="bi bi-map-fill me-2"></i>
                                    <span class="fw-bold">Regional</span>
                                </button>
                                <button class="ranking-tab-btn {{ $activeTab === 'provincial' ? 'active' : '' }}" 
                                        data-tab="provincial" 
                                        type="button"
                                        role="tab"
                                        aria-selected="{{ $activeTab === 'provincial' ? 'true' : 'false' }}">
                                    <i class="bi bi-geo-alt-fill me-2"></i>
                                    <span class="fw-bold">Provincial</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container" style="padding-top: 2rem; padding-bottom: 0;">

        @if(!empty($votingInfoMessage ?? ''))
            <div class="alert alert-primary mb-4 d-flex align-items-start" role="alert" style="background: linear-gradient(120deg, rgba(31,164,169,0.06), #EFF6FF); border-color: #BFDBFE; color:#1F2937;">
                <div class="me-3 flex-shrink-0 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border-radius: 999px; background-color: #1FA4A9;">
                    <i class="bi bi-megaphone" style="color:#FFFFFF; font-size:1.2rem;"></i>
                </div>
                <div class="flex-grow-1" style="font-size:0.92rem; line-height:1.5;">
                    {!! $votingInfoMessage !!}
                </div>
            </div>
        @endif

        <!-- Tab Content -->
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="ranking-content">
                    <!-- Nacional Tab -->
                    <div class="ranking-tab-content {{ $activeTab === 'nacional' ? 'active' : '' }}" id="tab-nacional" role="tabpanel">
                        <div class="mb-4">
                            <h2 class="h5 fw-bold text-dark mb-2">Ranking Nacional</h2>
                            <p class="text-muted mb-0">Las 7 festividades más votadas de toda España</p>
                        </div>
                        
                        @if($nationalFestivities->count() > 0)
                            <div class="row g-3 justify-content-center">
                                @foreach($nationalFestivities as $festivity)
                                    <div class="col-12 col-md-6 col-lg-3 d-flex">
                                        @include('festivities.partials.festivity-card', ['festivity' => $festivity])
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle me-2"></i>No hay festividades votadas a nivel nacional.
                            </div>
                        @endif
                    </div>

                    <!-- Regional Tab -->
                    <div class="ranking-tab-content {{ $activeTab === 'regional' ? 'active' : '' }}" id="tab-regional" role="tabpanel">
                        <div class="mb-4">
                            <h2 class="h5 fw-bold text-dark mb-2">Ranking Regional</h2>
                            <p class="text-muted mb-3">Festividades más votadas por Comunidad Autónoma</p>
                            
                            <form method="GET" action="{{ route('festivities.most-voted') }}" class="mb-4" id="regionalForm">
                                <input type="hidden" name="tab" value="regional">
                                <div class="row g-3 align-items-end">
                                    <div class="col-md-8">
                                        <label for="community" class="form-label fw-semibold small">
                                            <i class="bi bi-geo-alt me-1"></i>Comunidad Autónoma
                                        </label>
                                        <select name="community" id="community" class="form-select" onchange="document.getElementById('regionalForm').submit();">
                                            <option value="">Selecciona una comunidad autónoma</option>
                                            @foreach($communities as $community)
                                                <option value="{{ $community }}" {{ $selectedCommunity == $community ? 'selected' : '' }}>
                                                    {{ $community }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @if($selectedProvince)
                                        <input type="hidden" name="province" value="{{ $selectedProvince }}">
                                    @endif
                                    <div class="col-md-4">
                                        <button type="button" class="btn btn-outline-secondary w-100" onclick="window.location.href='{{ route('festivities.most-voted') }}?tab=regional'">
                                            <i class="bi bi-x-circle me-1"></i>Limpiar
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        @if($regionalFestivities->count() > 0)
                            <div class="row g-3 justify-content-center">
                                @foreach($regionalFestivities as $festivity)
                                    <div class="col-12 col-md-6 col-lg-3 d-flex">
                                        @include('festivities.partials.festivity-card', ['festivity' => $festivity])
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                No hay festividades votadas en <strong>{{ $selectedCommunity }}</strong>.
                            </div>
                        @endif
                    </div>

                    <!-- Provincial Tab -->
                    <div class="ranking-tab-content {{ $activeTab === 'provincial' ? 'active' : '' }}" id="tab-provincial" role="tabpanel">
                        <div class="mb-4">
                            <h2 class="h5 fw-bold text-dark mb-2">Ranking Provincial</h2>
                            <p class="text-muted mb-3">Festividades más votadas por Provincia</p>
                            
                            <form method="GET" action="{{ route('festivities.most-voted') }}" class="mb-4" id="provincialForm">
                                <input type="hidden" name="tab" value="provincial">
                                <div class="row g-3 align-items-end">
                                    <div class="col-md-8">
                                        <label for="province" class="form-label fw-semibold small">
                                            <i class="bi bi-map me-1"></i>Provincia
                                        </label>
                                        <select name="province" id="province" class="form-select" onchange="document.getElementById('provincialForm').submit();">
                                            <option value="">Selecciona una provincia</option>
                                            @foreach($provinces as $province)
                                                <option value="{{ $province }}" {{ $selectedProvince == $province ? 'selected' : '' }}>
                                                    {{ $province }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @if($selectedCommunity)
                                        <input type="hidden" name="community" value="{{ $selectedCommunity }}">
                                    @endif
                                    <div class="col-md-4">
                                        <button type="button" class="btn btn-outline-secondary w-100" onclick="window.location.href='{{ route('festivities.most-voted') }}?tab=provincial'">
                                            <i class="bi bi-x-circle me-1"></i>Limpiar
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        @if($provincialFestivities->count() > 0)
                            <div class="row g-3 justify-content-center">
                                @foreach($provincialFestivities as $festivity)
                                    <div class="col-12 col-md-6 col-lg-3 d-flex">
                                        @include('festivities.partials.festivity-card', ['festivity' => $festivity])
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                No hay festividades votadas en <strong>{{ $selectedProvince }}</strong>.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        body {
            background-image: url('/storage/background.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-color: #f8f9fa;
        }
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: white;
            opacity: 0.5;
            z-index: -1;
            pointer-events: none;
        }
        main {
            background-color: transparent;
        }
        /* Remove only top padding for this page */
        main.py-4 {
            padding-top: 0 !important;
        }
        
        /* Compact Header Section with Background Image */
        .header-most-voted {
            position: relative;
            padding: 3rem 0 2rem;
            margin: 0;
            overflow: hidden;
            background-color: #0f172a;
        }
        
        .header-most-voted::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: url('/storage/hero-4.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 0.9;
            z-index: 0;
        }
        
        .header-most-voted::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, rgba(15,23,42,0.15) 0%, rgba(15,23,42,0.82) 65%, rgba(15,23,42,0.95) 100%);
            z-index: 0;
        }
        
        .header-content {
            position: relative;
            z-index: 1;
        }
        
        /* Welcome Title Styling */
        .welcome-title {
            letter-spacing: 0.02em;
            position: relative;
            animation: fadeInUp 0.8s ease-out;
            text-shadow: 0 2px 20px rgba(255, 255, 255, 0.15), 0 4px 30px rgba(255, 255, 255, 0.1);
            font-weight: 700;
            color: rgba(255, 255, 255, 0.98);
            -webkit-text-stroke: 1px rgba(255, 255, 255, 0.3);
            text-stroke: 1px rgba(255, 255, 255, 0.3);
        }
        
        .welcome-title .char-shake {
            display: inline-block;
            animation: letterTremor 2.5s ease-in-out infinite;
        }
        
        .welcome-title .char-shake:nth-child(1) { animation-delay: 0s; animation-duration: 2.8s; }
        .welcome-title .char-shake:nth-child(2) { animation-delay: 0.3s; animation-duration: 2.4s; }
        .welcome-title .char-shake:nth-child(3) { animation-delay: 0.15s; animation-duration: 2.6s; }
        .welcome-title .char-shake:nth-child(4) { animation-delay: 0.45s; animation-duration: 2.3s; }
        .welcome-title .char-shake:nth-child(5) { animation-delay: 0.1s; animation-duration: 2.7s; }
        .welcome-title .char-shake:nth-child(6) { animation-delay: 0.35s; animation-duration: 2.5s; }
        .welcome-title .char-shake:nth-child(7) { animation-delay: 0.2s; animation-duration: 2.6s; }
        .welcome-title .char-shake:nth-child(8) { animation-delay: 0.4s; animation-duration: 2.4s; }
        .welcome-title .char-shake:nth-child(9) { animation-delay: 0.05s; animation-duration: 2.8s; }
        .welcome-title .char-shake:nth-child(10) { animation-delay: 0.3s; animation-duration: 2.3s; }
        .welcome-title .char-shake:nth-child(11) { animation-delay: 0.15s; animation-duration: 2.7s; }
        .welcome-title .char-shake:nth-child(12) { animation-delay: 0.45s; animation-duration: 2.5s; }
        .welcome-title .char-shake:nth-child(13) { animation-delay: 0.1s; animation-duration: 2.6s; }
        .welcome-title .char-shake:nth-child(14) { animation-delay: 0.35s; animation-duration: 2.4s; }
        .welcome-title .char-shake:nth-child(15) { animation-delay: 0.2s; animation-duration: 2.7s; }
        .welcome-title .char-shake:nth-child(16) { animation-delay: 0.4s; animation-duration: 2.3s; }
        .welcome-title .char-shake:nth-child(17) { animation-delay: 0.05s; animation-duration: 2.8s; }
        
        .welcome-title::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 2px;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.6), transparent);
            animation: expandLine 1s ease-out 0.5s both, shimmer 2.5s ease-in-out 1.5s infinite;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(15px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes expandLine {
            from {
                width: 0;
                opacity: 0;
            }
            to {
                width: 60px;
                opacity: 1;
            }
        }
        
        @keyframes letterTremor {
            0%, 100% {
                transform: translate(0, 0) rotate(0deg);
            }
            12.5% {
                transform: translate(-0.15px, -0.25px) rotate(-0.1deg);
            }
            25% {
                transform: translate(0.15px, 0.25px) rotate(0.1deg);
            }
            37.5% {
                transform: translate(0.25px, -0.15px) rotate(0.08deg);
            }
            50% {
                transform: translate(-0.15px, 0.15px) rotate(-0.08deg);
            }
            62.5% {
                transform: translate(0.15px, -0.25px) rotate(0.1deg);
            }
            75% {
                transform: translate(-0.25px, 0.15px) rotate(-0.1deg);
            }
            87.5% {
                transform: translate(0.15px, 0.25px) rotate(0.08deg);
            }
        }
        
        @keyframes shimmer {
            0%, 100% {
                opacity: 0.6;
                width: 60px;
            }
            50% {
                opacity: 0.9;
                width: 65px;
            }
        }
        
        /* Tab Navigation in Header */
        .ranking-tabs-header {
            margin-top: 1.5rem;
        }
        
        .ranking-tab-btn {
            background: rgba(255, 255, 255, 0.15);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 8px;
            padding: 0.6rem 1.2rem;
            font-size: 0.95rem;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            backdrop-filter: blur(10px);
        }

        .ranking-tab-btn:hover {
            background: rgba(255, 255, 255, 0.25);
            border-color: rgba(255, 255, 255, 0.5);
            transform: translateY(-2px);
        }

        .ranking-tab-btn.active {
            background: #FEB101;
            color: white;
            border-color: #FEB101;
            font-weight: 600;
            box-shadow: 0 2px 6px rgba(254, 177, 1, 0.4);
        }

        .ranking-tab-content {
            display: none;
            animation: fadeIn 0.3s ease-in;
        }

        .ranking-tab-content.active {
            display: block;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .ranking-content {
            min-height: 400px;
        }

        /* Card improvements for tourism-style */
        .card {
            border-radius: 12px;
            overflow: hidden;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            border: 1px solid #e9ecef;
            box-shadow: 0 2px 8px rgba(0,0,0,0.25) !important;
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.3) !important;
        }

        .card-img-top {
            transition: transform 0.3s ease;
        }

        .card:hover .card-img-top {
            transform: scale(1.05);
        }

        /* Voting Info Tooltip Styles */
        .voting-info-tooltip {
            position: relative;
        }

        .voting-info-btn {
            transition: all 0.2s ease;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .voting-info-btn:hover {
            background-color: rgba(255, 255, 255, 0.2);
            transform: scale(1.1);
        }

        .voting-info-popup {
            position: absolute;
            top: 100%;
            left: 0;
            margin-top: 10px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.3);
            min-width: 320px;
            max-width: 400px;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid #e9ecef;
        }

        .voting-info-popup.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .voting-info-header {
            padding: 1rem 1.25rem;
            background: linear-gradient(135deg, #1FA4A9 0%, #0d7d82 100%);
            color: white;
            border-radius: 12px 12px 0 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 1rem;
        }

        .voting-info-header i {
            font-size: 1.1rem;
        }

        .btn-close-popup {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 0.9rem;
        }

        .btn-close-popup:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: scale(1.1);
        }

        .voting-info-body {
            padding: 1.25rem;
            color: #495057;
            font-size: 0.95rem;
            line-height: 1.6;
        }

        .voting-info-body p {
            margin-bottom: 0.75rem;
            display: flex;
            align-items: flex-start;
        }

        .voting-info-body p:last-child {
            margin-bottom: 0;
        }

        .voting-info-body i {
            margin-top: 2px;
            flex-shrink: 0;
        }
        
        .voting-info-icon {
            color: #1FA4A9;
        }

        @media (max-width: 768px) {
            .ranking-tab-btn {
                padding: 0.5rem 0.9rem;
                font-size: 0.85rem;
            }
            
            .header-most-voted {
                padding: 1.5rem 0 1rem;
            }
            
            h1.display-5 {
                font-size: 1.75rem;
            }
        }

        @media (max-width: 576px) {
            .voting-info-popup {
                min-width: 280px;
                left: auto;
                right: 0;
            }
            
            .ranking-tabs-header .d-flex {
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .ranking-tab-btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabButtons = document.querySelectorAll('.ranking-tab-btn');
            const tabContents = document.querySelectorAll('.ranking-tab-content');

            // Determine which tab should be active based on URL params
            // Priority: explicit tab param > community param > province param > default nacional
            const urlParams = new URLSearchParams(window.location.search);
            const explicitTab = urlParams.get('tab');
            const hasManualCommunity = urlParams.get('community');
            const hasManualProvince = urlParams.get('province');
            
            let activeTab = 'nacional';
            // Check explicit tab parameter first
            if (explicitTab && ['nacional', 'regional', 'provincial'].includes(explicitTab)) {
                activeTab = explicitTab;
            } else if (hasManualCommunity) {
                activeTab = 'regional';
            } else if (hasManualProvince) {
                activeTab = 'provincial';
            }

            // Only update if the current state doesn't match (to avoid flash)
            const currentActiveBtn = document.querySelector('.ranking-tab-btn.active');
            const currentActiveContent = document.querySelector('.ranking-tab-content.active');
            
            if (!currentActiveBtn || currentActiveBtn.dataset.tab !== activeTab) {
                // Set initial active tab
                tabButtons.forEach(btn => {
                    if (btn.dataset.tab === activeTab) {
                        btn.classList.add('active');
                        btn.setAttribute('aria-selected', 'true');
                    } else {
                        btn.classList.remove('active');
                        btn.setAttribute('aria-selected', 'false');
                    }
                });

                tabContents.forEach(content => {
                    if (content.id === `tab-${activeTab}`) {
                        content.classList.add('active');
                    } else {
                        content.classList.remove('active');
                    }
                });
            }

            // Tab switching
            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const targetTab = this.dataset.tab;

                    // Update buttons
                    tabButtons.forEach(btn => {
                        btn.classList.remove('active');
                        btn.setAttribute('aria-selected', 'false');
                    });
                    this.classList.add('active');
                    this.setAttribute('aria-selected', 'true');

                    // Update content
                    tabContents.forEach(content => {
                        content.classList.remove('active');
                    });
                    document.getElementById(`tab-${targetTab}`).classList.add('active');

                    // Update URL without reloading to preserve state
                    const urlParams = new URLSearchParams(window.location.search);
                    urlParams.set('tab', targetTab);
                    const newUrl = window.location.pathname + '?' + urlParams.toString();
                    window.history.pushState({ tab: targetTab }, '', newUrl);
                });
            });

            // Voting info popup functionality
            const votingInfoBtn = document.getElementById('votingInfoBtn');
            const votingInfoPopup = document.getElementById('votingInfoPopup');
            const closeVotingInfo = document.getElementById('closeVotingInfo');

            if (votingInfoBtn && votingInfoPopup) {
                votingInfoBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    votingInfoPopup.classList.toggle('show');
                });

                if (closeVotingInfo) {
                    closeVotingInfo.addEventListener('click', function(e) {
                        e.stopPropagation();
                        votingInfoPopup.classList.remove('show');
                    });
                }

                // Close when clicking outside
                document.addEventListener('click', function(e) {
                    if (!votingInfoBtn.contains(e.target) && !votingInfoPopup.contains(e.target)) {
                        votingInfoPopup.classList.remove('show');
                    }
                });
            }
        });
    </script>
</x-app-layout>
