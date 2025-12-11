@extends('layouts.app')

@section('title', 'Search Results')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Input Prompt Display -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="mb-2">
                            <i class="fas fa-question-circle"></i> Your Question:
                        </h5>
                        <p class="lead mb-0">{{ $inputPrompt }}</p>
                    </div>
                </div>

                @if($found)
                    <!-- Matches Found -->
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <strong>Matches Found!</strong> Found {{ count($results) }} similar question(s) in the knowledge base.
                    </div>

                    @foreach($results as $index => $result)
                        <div class="card shadow-sm mb-3 result-card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                        <span>
                            <strong>Match #{{ $index + 1 }}</strong>
                        </span>
                                <span class="badge score-badge {{ $result['score'] >= 0.75 ? 'score-high' : ($result['score'] >= 0.55 ? 'score-medium' : 'score-low') }}">
                            {{ round($result['score'] * 100, 1) }}% Match
                        </span>
                            </div>
                            <div class="card-body">
                                <h6 class="mb-2">
                                    <i class="fas fa-question"></i> Question:
                                </h6>
                                <p class="mb-3">{{ $result['question'] }}</p>

                                <h6 class="mb-2">
                                    <i class="fas fa-comment-dots"></i> Answer:
                                </h6>
                                <div class="alert alert-info mb-3">
                                    {{ $result['answer'] }}
                                </div>

                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <i class="fas fa-eye"></i> Used {{ $result['usage_count'] }} times
                                    </small>
                                    <div>
                                        <form action="{{ route('answer.helpful', $result['id']) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-success">
                                                <i class="fas fa-thumbs-up"></i> Helpful
                                            </button>
                                        </form>
                                        <form action="{{ route('answer.unhelpful', $result['id']) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-thumbs-down"></i> Not Helpful
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                @else
                    <!-- No Match Found -->
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>NOT_FOUND</strong> - No similar questions found (threshold: {{ round($threshold * 100, 1) }}%).
                    </div>

                    <div class="card shadow-sm">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-brain fa-4x text-muted mb-3"></i>
                            <h5>Help the AI Learn!</h5>
                            <p class="text-muted">This question is new to the system. Add an answer to teach the AI.</p>
                            <a href="{{ route('answer.create', ['prompt' => $inputPrompt]) }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-plus-circle"></i> Add Answer Now
                            </a>
                        </div>
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="mt-4 text-center">
                    <a href="{{ route('home') }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left"></i> Ask Another Question
                    </a>
                    <a href="{{ route('history.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-history"></i> View History
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
