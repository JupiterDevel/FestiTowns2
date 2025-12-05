<x-app-layout>
    <!-- Compact Header Section -->
    <div class="header-localities">
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
                                    placeholder="Buscar localidad, provincia..."
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
                                    <select id="provinceFilter" class="form-select">
                                        <option value="">Todas las provincias</option>
                                        @foreach($provinces as $province)
                                            <option value="{{ $province }}">{{ $province }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Context Text -->
                        <p class="context-info mt-2 mb-0" id="contextText">
                            <i class="bi bi-calendar-check me-1"></i>Localidades con festividades activas ahora.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        @auth
            @can('create', App\Models\Locality::class)
                <a href="{{ route('localities.create') }}" class="btn-add-locality" title="Añadir Localidad">
                    <i class="bi bi-plus-lg"></i>
                </a>
            @endcan
        @endauth
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

        <!-- Localities Grid -->
        <div id="localitiesGrid" class="row g-3 mb-4">
            @foreach($localities as $locality)
                @include('localities.partials.compact-card', ['locality' => $locality])
            @endforeach
        </div>

        <!-- Empty State -->
        <div id="emptyState" class="text-center py-5 d-none">
            <i class="bi bi-search display-4 text-muted"></i>
            <p class="text-muted mt-3">No se encontraron localidades</p>
        </div>

        <!-- Pagination -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 gap-3">
            <div class="text-muted small">
                Mostrando {{ $localities->firstItem() ?? 0 }} - {{ $localities->lastItem() ?? 0 }} de {{ $localities->total() }} resultados
            </div>
            <div id="paginationContainer">
                {{ $localities->links() }}
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const provinceFilter = document.getElementById('provinceFilter');
            const localitiesGrid = document.getElementById('localitiesGrid');
            const contextText = document.getElementById('contextText');
            const loadingSpinner = document.getElementById('loadingSpinner');
            const emptyState = document.getElementById('emptyState');
            const paginationContainer = document.getElementById('paginationContainer');
            
            let searchTimeout;
            let currentPage = 1;
            let isSearching = false;
            
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
                    fetchLocalities();
                }, 400);
            }
            
            // Fetch localities via AJAX
            async function fetchLocalities(page = 1) {
                const searchTerm = searchInput.value.trim();
                const province = provinceFilter.value;
                
                // Update context text
                if (searchTerm || province) {
                    isSearching = true;
                    contextText.innerHTML = '<i class="bi bi-search me-1"></i>Resultados de búsqueda.';
                } else {
                    isSearching = false;
                    contextText.innerHTML = '<i class="bi bi-calendar-check me-1"></i>Localidades con festividades activas ahora.';
                }
                
                loadingSpinner.classList.remove('d-none');
                localitiesGrid.classList.add('d-none');
                emptyState.classList.add('d-none');
                
                const params = new URLSearchParams({
                    search: searchTerm,
                    province: province,
                    page: page
                });
                
                try {
                    const response = await fetch(`{{ route('localities.search') }}?${params.toString()}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        updateLocalitiesGrid(data.localities);
                        updatePagination(data.pagination);
                        
                        if (data.localities.length === 0) {
                            localitiesGrid.classList.add('d-none');
                            emptyState.classList.remove('d-none');
                        } else {
                            localitiesGrid.classList.remove('d-none');
                            emptyState.classList.add('d-none');
                        }
                    }
                } catch (error) {
                    console.error('Error fetching localities:', error);
                } finally {
                    loadingSpinner.classList.add('d-none');
                }
            }
            
            // Update the localities grid
            function updateLocalitiesGrid(localities) {
                localitiesGrid.innerHTML = '';
                
                localities.forEach(locality => {
                    const col = document.createElement('div');
                    col.className = 'col-md-4 locality-fade-in';
                    
                    const localityDetailUrl = `{{ url('localidades') }}/${locality.slug}`;
                    
                    const photoHtml = locality.photos && locality.photos.length > 0
                        ? `<a href="${localityDetailUrl}" class="text-decoration-none">
                               <img src="${locality.photos[0]}" class="card-img-top compact-card-img" alt="${locality.name}">
                           </a>`
                        : `<a href="${localityDetailUrl}" class="text-decoration-none">
                               <div class="card-img-top compact-card-img bg-gradient d-flex align-items-center justify-content-center">
                                   <i class="bi bi-geo-alt text-white display-4"></i>
                               </div>
                           </a>`;
                    
                    const provinceBadge = locality.province 
                        ? `<span class="badge bg-secondary compact-badge">${locality.province}</span>`
                        : '';
                    
                    const activeInfo = locality.active_festivities_count > 0
                        ? `<div class="mb-2">
                               <span class="badge bg-success compact-badge me-1">¡De Fiesta!</span>
                               <small class="text-muted">${locality.active_festivities_count} ${locality.active_festivities_count === 1 ? 'festividad' : 'festividades'}</small>
                           </div>`
                        : locality.next_festivity 
                            ? `<div class="mb-2">
                                   <small class="text-muted">Próxima: ${locality.next_festivity.name} - ${locality.next_festivity.start_date}</small>
                               </div>`
                            : '';
                    
                    const description = locality.description 
                        ? `<p class="card-text text-muted small mb-3" style="line-height: 1.4;">
                               ${locality.description.length > 160 ? locality.description.substring(0, 160) + '...' : locality.description}
                           </p>`
                        : '';
                    
                    const statusInfo = locality.active_festivities_count > 0
                        ? `<div class="mt-auto mb-2">
                               <div>
                                   <span class="badge bg-success compact-badge me-1">¡De Fiesta!</span>
                                   <small class="text-muted">${locality.active_festivities_count} ${locality.active_festivities_count === 1 ? 'festividad' : 'festividades'}</small>
                               </div>
                           </div>`
                        : locality.next_festivity 
                            ? `<div class="mt-auto mb-2">
                                   <div>
                                       <small class="text-muted">Próxima: ${locality.next_festivity.name} - ${locality.next_festivity.start_date}</small>
                                   </div>
                               </div>`
                            : '';
                    
                    const adminButtons = (canUpdate || canDelete) 
                        ? `<div>
                               <div class="d-flex gap-1">
                                   ${canUpdate ? `<a href="${locality.edit_url}" class="btn btn-sm btn-outline-secondary flex-fill" title="Editar">
                                       <i class="bi bi-pencil"></i>
                                   </a>` : ''}
                                   ${canDelete ? `<form method="POST" action="${locality.delete_url}" class="d-inline flex-fill" onsubmit="return confirm('¿Eliminar esta localidad?')">
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
                        <div class="card compact-locality-card h-100">
                            ${photoHtml}
                            <div class="card-body compact-card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <a href="${localityDetailUrl}" class="text-decoration-none">
                                        <h5 class="card-title compact-title mb-0">${locality.name}</h5>
                                    </a>
                                    ${provinceBadge}
                                </div>
                                ${description}
                                ${statusInfo}
                                ${adminButtons}
                            </div>
                        </div>
                    `;
                    
                    localitiesGrid.appendChild(col);
                });
            }
            
            // Update pagination
            function updatePagination(pagination) {
                if (pagination.last_page <= 1) {
                    paginationContainer.innerHTML = '';
                    return;
                }
                
                let paginationHTML = '<nav><ul class="pagination justify-content-center">';
                
                // Previous button
                if (pagination.current_page > 1) {
                    paginationHTML += `<li class="page-item">
                        <a class="page-link" href="#" data-page="${pagination.current_page - 1}">Anterior</a>
                    </li>`;
                }
                
                // Page numbers
                for (let i = 1; i <= pagination.last_page; i++) {
                    const active = i === pagination.current_page ? 'active' : '';
                    paginationHTML += `<li class="page-item ${active}">
                        <a class="page-link" href="#" data-page="${i}">${i}</a>
                    </li>`;
                }
                
                // Next button
                if (pagination.current_page < pagination.last_page) {
                    paginationHTML += `<li class="page-item">
                        <a class="page-link" href="#" data-page="${pagination.current_page + 1}">Siguiente</a>
                    </li>`;
                }
                
                paginationHTML += '</ul></nav>';
                paginationContainer.innerHTML = paginationHTML;
                
                // Add click handlers
                paginationContainer.querySelectorAll('a[data-page]').forEach(link => {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        const page = parseInt(this.dataset.page);
                        fetchLocalities(page);
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    });
                });
            }
            
            // Event listeners
            searchInput.addEventListener('input', performSearch);
            provinceFilter.addEventListener('change', () => {
                currentPage = 1;
                fetchLocalities();
            });
        });
    </script>
    
    <style>
        /* Remove only top padding for this page */
        main.py-4 {
            padding-top: 0 !important;
        }
        
        /* Compact Header Section */
        .header-localities {
            position: relative;
            background: linear-gradient(to right, rgba(102, 126, 234, 0.85) 0%, rgba(118, 75, 162, 0.85) 100%),
                        url('/storage/hero-localities.jpg') center/cover;
            background-blend-mode: overlay;
            padding: 2rem 0 1.5rem;
            margin: 0;
        }
        
        .header-localities::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(102, 126, 234, 0.4);
            backdrop-filter: blur(2px);
            z-index: 0;
        }
        
        .header-content {
            position: relative;
            z-index: 1;
        }
        
        /* Search Box */
        .search-box-main {
            background: white;
            border-radius: 12px;
            padding: 0.65rem 1rem;
            max-width: 650px;
            margin: 0 auto;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        .search-box-main .d-flex {
            gap: 0.5rem;
        }
        
        .search-icon {
            display: flex;
            align-items: center;
            color: #667eea;
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
            background: #f3f4f6;
            border: none;
            border-radius: 8px;
            padding: 0.4rem 0.9rem;
            color: #667eea;
            transition: all 0.2s ease;
            cursor: pointer;
            font-size: 1.1rem;
        }
        
        .btn-filter-toggle:hover {
            background: #e5e7eb;
        }
        
        .filter-content {
            padding: 0.5rem 0 0;
        }
        
        .filter-content .form-select {
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            font-size: 0.9rem;
        }
        
        .filter-content .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        /* Context Text */
        .context-info {
            text-align: center;
            color: rgba(255,255,255,0.95);
            font-size: 0.85rem;
            font-weight: 400;
        }
        
        /* Add Button */
        .btn-add-locality {
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
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            transition: all 0.2s ease;
            text-decoration: none;
            font-size: 1.3rem;
            z-index: 10;
        }
        
        .btn-add-locality:hover {
            background: #667eea;
            color: white;
            transform: rotate(90deg);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }
        
        /* Compact Cards */
        .compact-locality-card {
            border: none;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }
        
        .compact-locality-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.12);
        }
        
        .compact-card-img {
            height: 180px;
            object-fit: cover;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            cursor: pointer;
            transition: opacity 0.2s ease;
        }
        
        a:hover .compact-card-img {
            opacity: 0.95;
        }
        
        .compact-card-body {
            padding: 1rem;
            display: flex;
            flex-direction: column;
        }
        
        .compact-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #2d3748;
            transition: color 0.2s ease;
        }
        
        a:hover .compact-title {
            color: #667eea;
        }
        
        .compact-badge {
            font-size: 0.7rem;
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
        }
        
        .locality-fade-in {
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
        
        /* Hide default Laravel pagination text */
        #paginationContainer p,
        #paginationContainer .hidden {
            display: none !important;
        }
        
        #paginationContainer .page-item {
            margin: 0;
        }
        
        #paginationContainer .page-link {
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            color: #667eea;
            padding: 0.375rem 0.75rem;
            font-weight: 500;
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
            background: #667eea;
            color: white;
            border-color: #667eea;
            transform: translateY(-1px);
            box-shadow: 0 2px 6px rgba(102, 126, 234, 0.25);
        }
        
        #paginationContainer .page-item.active .page-link {
            background: #667eea;
            border-color: #667eea;
            color: white;
            font-weight: 600;
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
        
        /* Responsive */
        @media (max-width: 767px) {
            .header-localities {
                padding: 1.5rem 0 1rem;
            }
            
            .search-box-main {
                padding: 0.5rem 0.75rem;
                max-width: 100%;
            }
            
            .btn-add-locality {
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
