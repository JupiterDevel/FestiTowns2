<x-app-layout>
    <!-- Compact Header Section -->
    <div class="header-festivities">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="header-content">
                        <!-- Integrated Search Bar -->
                        <div class="search-box-main">
                            <div class="d-flex align-items-center">
                                <div class="search-icon">
                                    <i class="bi bi-search"></i>
                                </div>
                                <input 
                                    type="text" 
                                    id="searchInput" 
                                    class="form-control-search" 
                                    placeholder="Buscar festividad, localidad..."
                                >
                                <button 
                                    class="btn-filter-toggle" 
                                    type="button" 
                                    data-bs-toggle="collapse" 
                                    data-bs-target="#filterCollapse"
                                    title="Filtros"
                                >
                                    <i class="bi bi-sliders"></i>
                                </button>
                            </div>
                            
                            <!-- Collapsible Filter -->
                            <div class="collapse mt-3" id="filterCollapse">
                                <div class="filter-content">
                                    <div class="row g-2">
                                        <div class="col-md-6">
                                            <label for="provinceFilter" class="form-label small fw-semibold">Provincia</label>
                                            <select id="provinceFilter" class="form-select">
                                                <option value="">Todas las provincias</option>
                                                @foreach($provinces as $province)
                                                    <option value="{{ $province }}">{{ $province }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="dateFromFilter" class="form-label small fw-semibold">Desde</label>
                                            <input type="date" id="dateFromFilter" class="form-select">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="dateToFilter" class="form-label small fw-semibold">Hasta</label>
                                            <input type="date" id="dateToFilter" class="form-select">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Context Text -->
                        <p class="context-info mt-2 mb-0" id="contextText">
                            <i class="bi bi-calendar-check me-1"></i>Festividades activas ahora.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container" style="padding-top: 2rem; padding-bottom: 0;">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Loading Spinner -->
        <div id="loadingSpinner" class="text-center py-5 d-none">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
        </div>

        <!-- Festivities Grid -->
        <div id="festivitiesGrid" class="row g-3 mb-4">
            @foreach($festivities as $festivity)
                @include('festivities.partials.compact-card', ['festivity' => $festivity])
            @endforeach
        </div>

        <!-- Empty State -->
        <div id="emptyState" class="text-center py-5 d-none empty-state-centered">
            <i class="bi bi-search display-4 text-muted"></i>
            <p class="text-muted mt-3">No se encontraron festividades</p>
        </div>

        <!-- Pagination -->
        <div id="paginationInfo" class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 gap-3">
            <div id="paginationText" class="text-muted small">
                Mostrando {{ $festivities->firstItem() ?? 0 }} - {{ $festivities->lastItem() ?? 0 }} de {{ $festivities->total() }} resultados
            </div>
            <div id="paginationContainer">
                {{ $festivities->links() }}
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const provinceFilter = document.getElementById('provinceFilter');
            const dateFromFilter = document.getElementById('dateFromFilter');
            const dateToFilter = document.getElementById('dateToFilter');
            const festivitiesGrid = document.getElementById('festivitiesGrid');
            const contextText = document.getElementById('contextText');
            const loadingSpinner = document.getElementById('loadingSpinner');
            const emptyState = document.getElementById('emptyState');
            const paginationContainer = document.getElementById('paginationContainer');
            const paginationInfo = document.getElementById('paginationInfo');
            const paginationText = document.getElementById('paginationText');
            
            let searchTimeout;
            let currentPage = 1;
            let isSearching = false;
            let lastPage = 1;
            
            // Track if a request is in progress to prevent double-clicks
            let isLoading = false;
            
            // Set up event delegation for pagination (once, using document to catch all clicks)
            document.addEventListener('click', function(e) {
                // Find the closest link with data-page attribute (handles clicks on text nodes too)
                const link = e.target.closest('a[data-page]');
                
                // Check if the link is inside the pagination container
                if (link && paginationContainer && paginationContainer.contains(link)) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Prevent clicks while loading
                    if (isLoading) {
                        return;
                    }
                    
                    const pageStr = link.getAttribute('data-page');
                    const page = parseInt(pageStr);
                    
                    // Validate page number - allow navigation if page is valid number
                    if (pageStr && !isNaN(page) && page >= 1) {
                        // Don't block navigation - let backend handle validation
                        fetchFestivities(page);
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    }
                }
            });
            
            const isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};
            @auth
            const canUpdate = {{ auth()->user()->isAdmin() ? 'true' : 'false' }};
            const canDelete = {{ auth()->user()->isAdmin() ? 'true' : 'false' }};
            @else
            const canUpdate = false;
            const canDelete = false;
            @endauth
            
            // Debounced search function
            function performSearch() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    currentPage = 1;
                    fetchFestivities();
                }, 400);
            }
            
            // Get parameters from URL
            const urlParams = new URLSearchParams(window.location.search);
            const localityParam = urlParams.get('locality');
            const provinceParam = urlParams.get('province');
            
            // Set province filter if parameter exists
            if (provinceParam && provinceFilter) {
                provinceFilter.value = provinceParam;
            }
            
            // Clear initial Laravel pagination immediately
            if (paginationContainer) {
                paginationContainer.innerHTML = '';
            }
            
            // Initial fetch on page load (will use province/locality from URL if present)
            fetchFestivities();
            
            // Fetch festivities via AJAX
            async function fetchFestivities(pageNum = 1) {
                // Prevent multiple simultaneous requests
                if (isLoading) {
                    return;
                }
                
                isLoading = true;
                currentPage = pageNum;
                const searchTerm = searchInput.value.trim();
                const province = provinceFilter.value || provinceParam || '';
                const dateFrom = dateFromFilter.value;
                const dateTo = dateToFilter.value;
                
                // Update context text
                if (searchTerm || province || dateFrom || dateTo || localityParam) {
                    isSearching = true;
                    contextText.innerHTML = '<i class="bi bi-search me-1"></i>Resultados de búsqueda.';
                } else {
                    isSearching = false;
                    contextText.innerHTML = '<i class="bi bi-calendar-check me-1"></i>Festividades activas ahora.';
                }
                
                loadingSpinner.classList.remove('d-none');
                festivitiesGrid.classList.add('d-none');
                emptyState.classList.add('d-none');
                if (paginationInfo) paginationInfo.classList.remove('d-none');
                
                const params = new URLSearchParams({
                    search: searchTerm,
                    province: province,
                    date_from: dateFrom,
                    date_to: dateTo,
                    locality: localityParam || '',
                    page: currentPage
                });
                
                try {
                    const response = await fetch(`{{ route('festivities.search') }}?${params.toString()}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        updateFestivitiesGrid(data.festivities);
                        
                        // Always update pagination if it exists in response
                        if (data.pagination) {
                            updatePagination(data.pagination);
                        }
                        
                        if (data.festivities.length === 0) {
                            festivitiesGrid.classList.add('d-none');
                            emptyState.classList.remove('d-none');
                            paginationInfo.classList.add('d-none');
                        } else {
                            festivitiesGrid.classList.remove('d-none');
                            emptyState.classList.add('d-none');
                            paginationInfo.classList.remove('d-none');
                        }
                    }
                } catch (error) {
                    console.error('Error fetching festivities:', error);
                } finally {
                    isLoading = false;
                    loadingSpinner.classList.add('d-none');
                }
            }
            
            // Update the festivities grid
            function updateFestivitiesGrid(festivities) {
                festivitiesGrid.innerHTML = '';
                
                festivities.forEach(festivity => {
                    const col = document.createElement('div');
                    col.className = 'col-md-4 festivity-fade-in';
                    
                    const localityDetailUrl = festivity.locality.slug 
                        ? `{{ url('localidades') }}/${festivity.locality.slug}`
                        : '#';
                    
                    const photoHtml = festivity.photos && festivity.photos.length > 0
                        ? `<a href="{{ url('festividades') }}/${festivity.slug}" class="text-decoration-none">
                               <img src="${festivity.photos[0]}" class="card-img-top compact-card-img" alt="${festivity.name}">
                           </a>`
                        : `<a href="{{ url('festividades') }}/${festivity.slug}" class="text-decoration-none">
                               <div class="card-img-top compact-card-img bg-gradient d-flex align-items-center justify-content-center">
                                   <i class="bi bi-calendar-event text-white display-4"></i>
                               </div>
                           </a>`;
                    
                    const provinceBadge = festivity.province 
                        ? `<span class="badge bg-secondary compact-badge">${festivity.province}</span>`
                        : '';
                    
                    const dateRange = festivity.end_date
                        ? `${festivity.start_date_formatted} - ${festivity.end_date_formatted}`
                        : festivity.start_date_formatted;
                    
                    const votesBadge = `<span class="badge" style="background: linear-gradient(135deg, #FEB101 0%, #F59E0B 100%); color: #FFFFFF; font-weight: 600; font-size: 0.75rem; padding: 0.3rem 0.6rem; border-radius: 6px; box-shadow: 0 2px 6px rgba(254, 177, 1, 0.3);">
                        <i class="bi bi-heart-fill me-1" style="font-size: 0.7rem;"></i>${festivity.votes_count} ${festivity.votes_count === 1 ? 'Voto' : 'Votos'}
                    </span>`;
                    
                    const statusInfo = festivity.is_active
                        ? `<div class="mt-auto mb-2">
                               <div class="d-flex align-items-center justify-content-between">
                                   <span class="badge compact-badge fw-semibold"
                                         style="background-color: #198754; color: #FFFFFF; font-size: 0.78rem; padding: 0.35rem 0.9rem; border-radius: 999px;">
                                         Activa ahora
                                   </span>
                                   ${votesBadge}
                               </div>
                           </div>`
                        : `<div class="mt-auto mb-2">
                               <div class="d-flex align-items-center justify-content-end">
                                   ${votesBadge}
                               </div>
                           </div>`;
                    
                    const description = festivity.description 
                        ? `<p class="card-text text-muted small mb-3" style="line-height: 1.4;">
                               ${festivity.description.length > 160 ? festivity.description.substring(0, 160) + '...' : festivity.description}
                           </p>`
                        : '';
                    
                    const adminButtons = (canUpdate || canDelete) 
                        ? `<div>
                               <div class="d-flex gap-1">
                                   ${canUpdate ? `<a href="${festivity.edit_url}" class="btn btn-sm btn-outline-secondary flex-fill" title="Editar">
                                       <i class="bi bi-pencil"></i>
                                   </a>` : ''}
                                   ${canDelete ? `<form method="POST" action="${festivity.delete_url}" class="d-inline flex-fill" onsubmit="return confirm('¿Eliminar esta festividad?')">
                                       <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                       <input type="hidden" name="_method" value="DELETE">
                                       <button type="submit" class="btn btn-sm btn-outline-danger w-100" title="Eliminar">
                                           <i class="bi bi-trash"></i>
                                       </button>
                                   </form>` : ''}
                               </div>
                           </div>`
                        : '';
                    
                    col.innerHTML = `
                        <div class="card compact-festivity-card h-100">
                            ${photoHtml}
                            <div class="card-body compact-card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <a href="{{ url('festividades') }}/${festivity.slug}" class="text-decoration-none">
                                        <h5 class="card-title compact-title mb-0">${festivity.name}</h5>
                                    </a>
                                    ${provinceBadge}
                                </div>
                                
                                <div class="mb-2">
                                    <p class="text-muted small mb-1">
                                        <i class="bi bi-geo-alt me-1"></i>
                                        ${festivity.locality.name || 'Sin localidad'}
                                    </p>
                                    <p class="text-muted small mb-0">
                                        <i class="bi bi-calendar me-1"></i>${dateRange}
                                    </p>
                                </div>
                                
                                ${description}
                                ${statusInfo}
                                ${adminButtons}
                            </div>
                        </div>
                    `;
                    
                    festivitiesGrid.appendChild(col);
                });
            }
            
            // Update pagination with smart pagination logic
            function updatePagination(pagination) {
                if (!pagination || !pagination.last_page || pagination.last_page <= 1) {
                    if (paginationContainer) {
                        paginationContainer.innerHTML = '';
                    }
                    return;
                }
                
                // Ensure we have valid numbers
                currentPage = parseInt(pagination.current_page) || 1;
                lastPage = parseInt(pagination.last_page) || 1;
                const total = parseInt(pagination.total) || 0;
                const perPage = parseInt(pagination.per_page) || 6;
                
                // Safety check - ensure currentPage is within valid range
                if (currentPage < 1) currentPage = 1;
                if (currentPage > lastPage) currentPage = lastPage;
                
                // Update pagination text
                if (paginationText && total > 0) {
                    const firstItem = (currentPage - 1) * perPage + 1;
                    const lastItem = Math.min(currentPage * perPage, total);
                    paginationText.textContent = `Mostrando ${firstItem} - ${lastItem} de ${total} resultados`;
                }
                
                let paginationHTML = '<nav><ul class="pagination justify-content-center">';
                
                // Previous button
                if (currentPage > 1) {
                    paginationHTML += `<li class="page-item">
                        <a class="page-link" href="#" data-page="${currentPage - 1}">Anterior</a>
                    </li>`;
                } else {
                    paginationHTML += `<li class="page-item disabled">
                        <span class="page-link">Anterior</span>
                    </li>`;
                }
                
                // Smart page number display - show max 7 page numbers total
                const pagesToShow = [];
                
                if (lastPage <= 7) {
                    // Show all pages if total pages is small (7 or less)
                    for (let i = 1; i <= lastPage; i++) {
                        pagesToShow.push(i);
                    }
                } else {
                    // Always show first page
                    pagesToShow.push(1);
                    
                    // Determine what pages to show around current
                    if (currentPage <= 4) {
                        // Near start: show 1, 2, 3, 4, 5, ..., last
                        for (let i = 2; i <= 5 && i <= lastPage; i++) {
                            pagesToShow.push(i);
                        }
                        if (lastPage > 5) {
                            pagesToShow.push('ellipsis');
                            pagesToShow.push(lastPage);
                        }
                    } else if (currentPage >= lastPage - 3) {
                        // Near end: show 1, ..., last-4, last-3, last-2, last-1, last
                        if (lastPage > 5) {
                            pagesToShow.push('ellipsis');
                        }
                        const start = Math.max(2, lastPage - 4);
                        for (let i = start; i <= lastPage; i++) {
                            pagesToShow.push(i);
                        }
                    } else {
                        // Middle: show 1, ..., current-1, current, current+1, ..., last
                        pagesToShow.push('ellipsis');
                        pagesToShow.push(currentPage - 1);
                        pagesToShow.push(currentPage);
                        pagesToShow.push(currentPage + 1);
                        pagesToShow.push('ellipsis');
                        pagesToShow.push(lastPage);
                    }
                }
                
                // Render page numbers - filter out any invalid pages
                pagesToShow.forEach(page => {
                    if (page === 'ellipsis') {
                        paginationHTML += `<li class="page-item disabled">
                            <span class="page-link">...</span>
                        </li>`;
                    } else {
                        const pageNum = parseInt(page);
                        // Only render if it's a valid page number within range
                        if (!isNaN(pageNum) && pageNum >= 1 && pageNum <= lastPage) {
                            const isActive = pageNum === currentPage;
                            paginationHTML += `<li class="page-item ${isActive ? 'active' : ''}">
                                <a class="page-link" href="#" data-page="${pageNum}">${pageNum}</a>
                            </li>`;
                        }
                    }
                });
                
                // Next button - simplified like Previous
                if (currentPage < lastPage) {
                    paginationHTML += `<li class="page-item">
                        <a class="page-link" href="#" data-page="${currentPage + 1}">Siguiente</a>
                    </li>`;
                } else {
                    paginationHTML += `<li class="page-item disabled">
                        <span class="page-link">Siguiente</span>
                    </li>`;
                }
                
                paginationHTML += '</ul></nav>';
                
                // Set pagination HTML - event delegation already handles clicks, so no need for direct listeners
                if (paginationContainer) {
                    paginationContainer.innerHTML = paginationHTML;
                }
            }
            
            // Event listeners
            searchInput.addEventListener('input', performSearch);
            provinceFilter.addEventListener('change', () => {
                currentPage = 1;
                fetchFestivities();
            });
            dateFromFilter.addEventListener('change', () => {
                currentPage = 1;
                fetchFestivities();
            });
            dateToFilter.addEventListener('change', () => {
                currentPage = 1;
                fetchFestivities();
            });
        });
    </script>
    
    <style>
        body {
            background-image: url('/storage/background.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
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
            flex: 1;
        }
        /* Remove only top padding for this page */
        main.py-4 {
            padding-top: 0 !important;
        }
        
        /* Ensure footer stays at bottom */
        footer {
            margin-top: auto;
        }
        
        /* Empty State Centering */
        .empty-state-centered {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 60vh;
        }
        
        /* Compact Header Section with Background Image */
        .header-festivities {
            position: relative;
            padding: 3rem 0 2rem;
            margin: 0;
            overflow: hidden;
            background-color: #0f172a;
        }
        
        .header-festivities::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: url('/storage/hero-2.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 0.9;
            z-index: 0;
        }
        
        .header-festivities::after {
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
        
        /* Search Box */
        .search-box-main {
            background: white;
            border-radius: 16px;
            padding: 0.75rem 1.25rem;
            max-width: 700px;
            margin: 0 auto;
            box-shadow: 0 3px 10px rgba(0,0,0,0.3);
            position: relative;
            z-index: 1;
        }
        
        .search-box-main .d-flex {
            gap: 0.5rem;
        }
        
        .search-icon {
            display: flex;
            align-items: center;
            color: #FEB101;
            font-size: 1.2rem;
            padding: 0 0.25rem;
        }
        
        .form-control-search {
            border: none;
            outline: none;
            flex: 1;
            font-size: 0.95rem;
            padding: 0.4rem;
            background: transparent;
        }
        
        .form-control-search:focus {
            outline: none;
            box-shadow: none;
        }
        
        .form-control-search::placeholder {
            color: #9ca3af;
        }
        
        .btn-filter-toggle {
            background: #F8F9FA;
            border: none;
            border-radius: 8px;
            padding: 0.4rem 0.9rem;
            color: #FEB101;
            transition: all 0.2s ease;
            cursor: pointer;
            font-size: 1.1rem;
        }
        
        .btn-filter-toggle:hover {
            background: #FEB101;
            color: white;
        }
        
        .filter-content {
            padding: 0.5rem 0 0;
        }
        
        .filter-content .form-select,
        .filter-content input[type="date"] {
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            font-size: 0.9rem;
        }
        
        .filter-content .form-select:focus,
        .filter-content input[type="date"]:focus {
            border-color: #FEB101;
            box-shadow: 0 0 0 3px rgba(254, 177, 1, 0.15);
        }
        
        /* Context Text */
        .context-info {
            text-align: center;
            color: rgba(255,255,255,0.95);
            font-size: 0.9375rem;
            font-weight: 500;
            position: relative;
            z-index: 1;
        }
        
        /* Add Button */
        .btn-add-festivity {
            position: absolute;
            top: 1.5rem;
            right: 1rem;
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: white;
            color: #667eea;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 1px 4px rgba(0,0,0,0.3);
            transition: all 0.2s ease;
            text-decoration: none;
            font-size: 1.3rem;
            z-index: 10;
        }
        
        .btn-add-festivity:hover {
            background: #667eea;
            color: white;
            transform: rotate(90deg);
            box-shadow: 0 2px 6px rgba(102, 126, 234, 0.4);
        }
        
        /* Compact Festivity Cards */
        .compact-festivity-card {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.25) !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .compact-festivity-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.3) !important;
        }
        
        /* Override Bootstrap card shadows */
        .card {
            box-shadow: 0 2px 8px rgba(0,0,0,0.25) !important;
        }
        
        .card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.3) !important;
        }
        
        .compact-card-img {
            height: 240px;
            object-fit: cover;
            background: linear-gradient(135deg, #FEB101 0%, #F59E0B 100%);
            cursor: pointer;
            transition: transform 0.4s ease;
        }
        
        .compact-festivity-card:hover .compact-card-img {
            transform: scale(1.1);
        }
        
        .compact-card-body {
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
        }
        
        .compact-title {
            font-size: 1.375rem;
            font-weight: 700;
            color: #1F2937;
            transition: color 0.2s ease;
        }
        
        a:hover .compact-title {
            color: #FEB101;
        }
        
        .compact-badge {
            font-size: 0.7rem;
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
        }
        
        .festivity-fade-in {
            animation: fadeInUp 0.4s ease-out;
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
        
        /* Pagination Styling */
        #paginationContainer .pagination {
            gap: 0.375rem;
            margin: 0;
            flex-wrap: wrap;
        }
        
        #paginationContainer .page-item {
            margin: 0;
        }
        
        #paginationContainer .page-link {
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            color: #FEB101;
            padding: 0.375rem 0.75rem;
            font-weight: 600;
            font-size: 0.875rem;
            line-height: 1.25;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
        }
        
        #paginationContainer .page-link:hover {
            background: #FEB101;
            color: white;
            border-color: #FEB101;
            transform: translateY(-1px);
            box-shadow: 0 1px 4px rgba(254, 177, 1, 0.4);
        }
        
        #paginationContainer .page-item.active .page-link {
            background: linear-gradient(135deg, #FEB101 0%, #FF9500 100%);
            border-color: #FEB101;
            color: white;
            font-weight: 700;
            font-size: 1.05rem;
            box-shadow: 0 2px 8px rgba(254, 177, 1, 0.5);
            transform: scale(1.1);
            z-index: 1;
            position: relative;
        }
        
        #paginationContainer .page-item.active .page-link:hover {
            background: linear-gradient(135deg, #FF9500 0%, #FEB101 100%);
            transform: scale(1.15);
            box-shadow: 0 3px 12px rgba(254, 177, 1, 0.6);
        }
        
        #paginationContainer .page-item.disabled .page-link {
            color: #9ca3af;
            background: #f9fafb;
            border-color: #e5e7eb;
            cursor: not-allowed;
        }
        
        #paginationContainer .page-item.disabled .page-link:hover {
            transform: none;
            box-shadow: none;
        }
        
        /* Hide default Laravel pagination text */
        #paginationContainer p,
        #paginationContainer .hidden {
            display: none !important;
        }
        
        /* Responsive */
        @media (max-width: 767px) {
            .header-festivities {
                padding: 1.5rem 0 1rem;
            }
            
            .search-box-main {
                padding: 0.5rem 0.75rem;
                max-width: 100%;
            }
            
            .btn-add-festivity {
                width: 38px;
                height: 38px;
                font-size: 1.1rem;
                top: 1rem;
            }
            
            .context-info {
                font-size: 0.8rem;
            }
            
            #paginationContainer .page-link {
                padding: 0.3rem 0.6rem;
                font-size: 0.8rem;
                min-width: 32px;
                height: 32px;
            }
            
            #paginationContainer .pagination {
                gap: 0.25rem;
            }
        }
    </style>
    @endpush
</x-app-layout>
