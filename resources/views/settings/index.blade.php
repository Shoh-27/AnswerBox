@extends('layouts.app')

@section('title', 'Settings')

@section('content')
    <div class="container">
        <div class="row">
        <div class="col-lg-8">
            <!-- Algorithm Settings -->
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-sliders-h"></i> Algorithm Settings
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('settings.update') }}" method="POST">
    @csrf
    @method('PUT')
                        <div class="mb-4">
                            <label for="similarity_threshold" class="form-label">
                                <strong>Similarity Threshold</strong>
                            </label>
                            <input
                                type="number"
                                name="similarity_threshold"
                                id="similarity_threshold"
                                class="form-control @error('similarity_threshold') is-invalid @enderror"
                                value="{{ old('similarity_threshold', $settings['similarity_threshold']->value) }}"
                                min="0"
                                max="1"
                                step="0.01"
                                required
                            >
                            @error('similarity_threshold')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                Minimum score (0-1) to consider a match. Default: 0.45
                            </small>
                        </div>

                        <div class="mb-4">
                            <label for="max_results" class="form-label">
                                <strong>Maximum Results</strong>
                            </label>
                            <input
                                type="number"
                                name="max_results"
                                id="max_results"
                                class="form-control @error('max_results') is-invalid @enderror"
                                value="{{ old('max_results', $settings['max_results']->value) }}"
                                min="1"
                                max="10"
                                required
                            >
                            @error('max_results')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                Maximum number of matches to return (1-10)
                            </small>
                        </div>

                        <div class="mb-4">
                            <label for="algorithm_weight_levenshtein" class="form-label">
                                <strong>Levenshtein Weight</strong>
                            </label>
                            <input
                                type="number"
                                name="algorithm_weight_levenshtein"
                                id="algorithm_weight_levenshtein"
                                class="form-control @error('algorithm_weight_levenshtein') is-invalid @enderror"
                                value="{{ old('algorithm_weight_levenshtein', $settings['algorithm_weight_levenshtein']->value) }}"
                                min="0"
                                max="1"
                                step="0.1"
                                required
                            >
                            @error('algorithm_weight_levenshtein')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                Weight for Levenshtein algorithm (0-1). Default: 0.5
                            </small>
                        </div>

                        <div class="mb-4">
                            <label for="algorithm_weight_similar_text" class="form-label">
                                <strong>Similar Text Weight</strong>
                            </label>
                            <input
                                type="number"
                                name="algorithm_weight_similar_text"
                                id="algorithm_weight_similar_text"
                                class="form-control @error('algorithm_weight_similar_text') is-invalid @enderror"
                                value="{{ old('algorithm_weight_similar_text', $settings['algorithm_weight_similar_text']->value) }}"
                                min="0"
                                max="1"
                                step="0.1"
                                required
                            >
                            @error('algorithm_weight_similar_text')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                Weight for similar_text algorithm (0-1). Default: 0.5
                            </small>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Settings
                        </button>
                    </form>
                </div>
            </div>

            <!-- Data Management -->
            <div class="card shadow-sm">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-exclamation-triangle"></i> Data Management
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-circle"></i>
                        <strong>Warning:</strong> These actions cannot be undone!
                    </div>

                    <form action="{{ route('settings.clear') }}" method="POST" onsubmit="return confirm('Are you sure? This action cannot be undone!')">
                        @csrf
                        <input type="hidden" name="type" value="logs">
                        <button type="submit" class="btn btn-warning me-2">
                            <i class="fas fa-broom"></i> Clear Search Logs
                        </button>
                    </form>

                    <form action="{{ route('settings.clear') }}" method="POST" class="mt-2" onsubmit="return confirm('This will delete ALL data including Q&A pairs! Are you absolutely sure?')">
                        @csrf
                        <input type="hidden" name="type" value="all">
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash-alt"></i> Clear All Data
                        </button>
                    </form>
                </div>
            </div>
        </div>

            <div class="col-lg-4">
                <!-- System Info -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-info-circle"></i> System Info</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>Total Q&A Pairs:</strong><br>
                            <span class="h4">{{ $stats['total_prompts'] }}</span>
                        </div>
                        <div class="mb-3">
                            <strong>Total Searches:</strong><br>
                            <span class="h4">{{ $stats['total_searches'] }}</span>
                        </div>
                        <div class="mb-3">
                            <strong>Answers Available:</strong><br>
                            <span class="h4">{{ $stats['total_answers'] }}</span>
                        </div>
                        <div>
                            <strong>Storage Used:</strong><br>
                            <span class="h4">{{ $stats['storage_used'] }}</span>
                        </div>
                    </div>
                </div>

                <!-- Algorithm Info -->
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-brain"></i> How It Works</h6>
                    </div>
                    <div class="card-body">
                        <p class="small mb-2">
                            <strong>Levenshtein Distance:</strong> Measures the minimum number of single-character edits needed to transform one string into another.
                        </p>
                        <p class="small mb-0">
                            <strong>Similar Text:</strong> Calculates the similarity of two strings using the longest common subsequence.
                        </p>
                        <hr>
                        <p class="small text-muted mb-0">
                            Final score is a weighted average of both algorithms. Adjust weights to fine-tune matching behavior.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
