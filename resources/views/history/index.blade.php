@extends('layouts.app')

@section('title', 'History')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-3 mb-4">
                <!-- Statistics Sidebar -->
                <div class="card shadow-sm mb-3">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-chart-bar"></i> Statistics</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="stat-label">Total Q&A Pairs</div>
                            <div class="stat-value h3">{{ $stats['total_prompts'] }}</div>
                        </div>
                        <div class="mb-3">
                            <div class="stat-label">Total Searches</div>
                            <div class="stat-value h3">{{ $stats['total_searches'] }}</div>
                        </div>
                        <div class="mb-3">
                            <div class="stat-label">Success Rate</div>
                            <div class="stat-value h3">{{ $stats['success_rate'] }}%</div>
                        </div>
                        <div>
                            <div class="stat-label">Favorites</div>
                            <div class="stat-value h3">{{ $stats['favorites'] }}</div>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-filter"></i> Filters</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('history.index', ['filter' => 'all']) }}"
                               class="btn btn-sm {{ $filter === 'all' ? 'btn-primary' : 'btn-outline-primary' }}">
                                All Items
                            </a>
                            <a href="{{ route('history.index', ['filter' => 'favorites']) }}"
                               class="btn btn-sm {{ $filter === 'favorites' ? 'btn-primary' : 'btn-outline-primary' }}">
                                <i class="fas fa-star"></i> Favorites
                            </a>
                            <a href="{{ route('history.index', ['filter' => 'most_used']) }}"
                               class="btn btn-sm {{ $filter === 'most_used' ? 'btn-primary' : 'btn-outline-primary' }}">
                                <i class="fas fa-fire"></i> Most Used
                            </a>
                            <a href="{{ route('history.index', ['filter' => 'recent']) }}"
                               class="btn btn-sm {{ $filter === 'recent' ? 'btn-primary' : 'btn-outline-primary' }}">
                                <i class="fas fa-clock"></i> Recent
                            </a>
                        </div>

                        <hr>

                        <a href="{{ route('history.logs') }}" class="btn btn-sm btn-outline-secondary w-100">
                            <i class="fas fa-list"></i> View Search Logs
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-9">
                <!-- Search Bar -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <form action="{{ route('history.index') }}" method="GET">
                            <div class="input-group">
                                <input type="text"
                                       name="search"
                                       class="form-control"
                                       placeholder="Search questions or answers..."
                                       value="{{ $search }}">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Search
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Q&A List -->
                @if($prompts->count() > 0)
                    @foreach($prompts as $prompt)
                        <div class="card shadow-sm mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="mb-0">
                                        <i class="fas fa-question-circle text-primary"></i>
                                        {{ $prompt->question }}
                                    </h5>
                                    <div>
                                        <i class="fas fa-star favorite-icon {{ $prompt->is_favorite ? 'active' : '' }}"
                                           onclick="toggleFavorite({{ $prompt->id }})"
                                           title="Toggle favorite"></i>
                                        <form action="{{ route('prompt.destroy', $prompt->id) }}"
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('Delete this Q&A pair?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link text-danger p-0 ms-2" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                @if($prompt->primaryAnswer)
                                    <div class="alert alert-light mb-2">
                                        <strong><i class="fas fa-comment-dots"></i> Answer:</strong><br>
                                        {{ $prompt->primaryAnswer->answer }}
                                    </div>
                                @else
                                    <div class="alert alert-warning mb-2">
                                        <i class="fas fa-exclamation-triangle"></i> No answer provided yet.
                                    </div>
                                @endif

                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <i class="fas fa-clock"></i> {{ $prompt->created_at->format('M d, Y H:i') }}
                                        <span class="ms-3"><i class="fas fa-eye"></i> {{ $prompt->usage_count }} uses</span>
                                        @if($prompt->primaryAnswer)
                                            <span class="ms-3">
                                    <i class="fas fa-thumbs-up text-success"></i> {{ $prompt->primaryAnswer->helpfulness_score }}
                                </span>
                                        @endif
                                    </small>
                                    @if($prompt->primaryAnswer)
                                        <a href="{{ route('answer.edit', $prompt->primaryAnswer->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit"></i> Edit Answer
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $prompts->links() }}
                    </div>
                @else
                    <div class="card shadow-sm">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                            <h5>No Q&A Pairs Found</h5>
                            <p class="text-muted">
                                @if($search)
                                    No results match your search criteria.
                                @else
                                    Start by asking a question on the home page.
                                @endif
                            </p>
                            <a href="{{ route('home') }}" class="btn btn-primary">
                                <i class="fas fa-home"></i> Go to Home
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
