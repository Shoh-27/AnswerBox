@extends('layouts.app')

@section('title', 'Home - Offline AI Journal')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Main Prompt Input -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h2 class="card-title mb-3">
                            <i class="fas fa-comments"></i> Ask Your Question
                        </h2>
                        <p class="text-muted">Enter your question and the AI will find the best matching answers from its knowledge base.</p>

                        <form action="{{ route('prompt.search') }}" method="POST" id="promptForm">
                            @csrf
                            <div class="mb-3">
                            <textarea
                                name="prompt_text"
                                id="promptText"
                                class="form-control form-control-lg"
                                rows="4"
                                placeholder="Type your question here..."
                                required
                                autofocus
                            >{{ old('prompt_text') }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                <i class="fas fa-search"></i> Search Knowledge Base
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="card stat-card">
                            <div class="stat-value">{{ $recentPrompts->count() }}</div>
                            <div class="stat-label">Recent Questions</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card stat-card">
                            <div class="stat-value">{{ $favoritePrompts->count() }}</div>
                            <div class="stat-label">Favorites</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card stat-card">
                            <div class="stat-value">{{ \App\Models\Prompt::count() }}</div>
                            <div class="stat-label">Total Q&A Pairs</div>
                        </div>
                    </div>
                </div>

                <!-- Favorites Section -->
                @if($favoritePrompts->count() > 0)
                    <div class="card shadow-sm mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-star text-warning"></i> Your Favorites
                            </h5>
                        </div>
                        <div class="card-body">
                            @foreach($favoritePrompts as $prompt)
                                <div class="mb-3 pb-3 border-bottom">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">{{ $prompt->question }}</h6>
                                            <p class="text-muted small mb-1">
                                                {{ $prompt->primaryAnswer ? Str::limit($prompt->primaryAnswer->answer, 100) : 'No answer' }}
                                            </p>
                                            <small class="text-muted">
                                                <i class="fas fa-clock"></i> {{ $prompt->updated_at->diffForHumans() }}
                                                <span class="ms-2"><i class="fas fa-eye"></i> {{ $prompt->usage_count }} uses</span>
                                            </small>
                                        </div>
                                        <i class="fas fa-star favorite-icon active ms-2" onclick="toggleFavorite({{ $prompt->id }})"></i>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Recent Questions -->
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-history"></i> Recent Questions
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($recentPrompts->count() > 0)
                            @foreach($recentPrompts as $prompt)
                                <div class="mb-3 pb-3 border-bottom">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">{{ $prompt->question }}</h6>
                                            <p class="text-muted small mb-1">
                                                {{ $prompt->primaryAnswer ? Str::limit($prompt->primaryAnswer->answer, 100) : 'No answer' }}
                                            </p>
                                            <small class="text-muted">
                                                <i class="fas fa-clock"></i> {{ $prompt->updated_at->diffForHumans() }}
                                                <span class="ms-2"><i class="fas fa-eye"></i> {{ $prompt->usage_count }} uses</span>
                                            </small>
                                        </div>
                                        <i class="fas fa-star favorite-icon {{ $prompt->is_favorite ? 'active' : '' }} ms-2"
                                           onclick="toggleFavorite({{ $prompt->id }})"></i>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted text-center py-4">
                                <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                No questions yet. Start by asking something!
                            </p>
                        @endif
                    </div>
                    @if($recentPrompts->count() > 0)
                        <div class="card-footer text-center">
                            <a href="{{ route('history.index') }}" class="btn btn-sm btn-outline-primary">
                                View All History
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
