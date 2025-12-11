@extends('layouts.app')

@section('title', 'Add New Q&A Pair')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h4 class="mb-0">
                            <i class="fas fa-plus-circle"></i> Add New Q&A Pair
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="fas fa-lightbulb"></i>
                            <strong>Teach the AI!</strong> By adding this Q&A pair, you're expanding the AI's knowledge base.
                        </div>

                        <form action="{{ route('answer.store') }}" method="POST">
                            @csrf

                            <div class="mb-4">
                                <label for="question" class="form-label">
                                    <strong>Question</strong>
                                </label>
                                <textarea
                                    name="question"
                                    id="question"
                                    class="form-control @error('question') is-invalid @enderror"
                                    rows="3"
                                    placeholder="Enter the question..."
                                    required
                                >{{ old('question', $inputPrompt) }}</textarea>
                                @error('question')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">This is the question that will be stored in the knowledge base.</small>
                            </div>

                            <div class="mb-4">
                                <label for="answer" class="form-label">
                                    <strong>Answer</strong>
                                </label>
                                <textarea
                                    name="answer"
                                    id="answer"
                                    class="form-control @error('answer') is-invalid @enderror"
                                    rows="6"
                                    placeholder="Enter the answer..."
                                    required
                                >{{ old('answer') }}</textarea>
                                @error('answer')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Provide a clear, helpful answer to the question above.</small>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-save"></i> Save Q&A Pair
                                </button>
                                <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Tips -->
                <div class="card shadow-sm mt-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-info-circle"></i> Tips for Better Q&A Pairs</h6>
                    </div>
                    <div class="card-body">
                        <ul class="mb-0">
                            <li>Write questions in a natural, conversational way</li>
                            <li>Provide complete, accurate answers</li>
                            <li>Include relevant details and context</li>
                            <li>Use clear language that's easy to understand</li>
                            <li>Similar questions will match better if phrased consistently</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
