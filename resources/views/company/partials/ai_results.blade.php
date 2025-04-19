@extends('layouts.app')
@section('content')
<!-- Header start -->
@include('includes.header')
<!-- Header end -->
<!-- Inner Page Title start -->
@include('includes.inner_page_title', ['page_title'=>__('CV Search Results')])
<!-- Inner Page Title end -->

<div class="listpgWraper">
    <div class="container">
        <div class="row"> @include('includes.company_dashboard_menu')
          
            <div class="col-md-9 col-sm-9">
                <div class="my-3 p-3 bg-white rounded shadow-sm">
                    <h6 class="border-bottom pb-2 mb-4">{{ __('AI-Powered Candidate Matches') }}</h6>

                    @php
                        // Filter results to only show >2% matches
                        $filteredResults = array_filter($results, function($result) {
                            return isset($result['score']) && $result['score'] > 2;
                        });
                    @endphp

                    @if(count($filteredResults) > 0)
                        <div class="list">
                            @foreach ($filteredResults as $result)
                                <div class="job-item mb-4 p-3 border rounded">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <h4 class="h5 text-primary">{{ $result['name'] ?? 'Candidate' }}</h4>
                                            <div class="mb-2">
                                                @php
                                                    $scoreClass = 'badge-';
                                                    if ($result['score'] > 75) {
                                                        $scoreClass .= 'success';
                                                    } elseif ($result['score'] > 50) {
                                                        $scoreClass .= 'primary';
                                                    } elseif ($result['score'] > 25) {
                                                        $scoreClass .= 'warning';
                                                    } else {
                                                        $scoreClass .= 'info';
                                                    }
                                                @endphp
                                                <span class="badge {{ $scoreClass }}">
                                                    {{ $result['score'] }}% Match
                                                </span>
                                            </div>
                                            <a href="{{ url('job-seekers') }}?search={{ urlencode($result['name'] ?? '') }}" class="btn btn-sm btn-primary mt-2">
                                                {{ __('See Details') }}
                                            </a>
                                        </div>
                                    </div>

                                    @if(!empty($result['strengths']))
                                        <div class="strengths-section mt-3">
                                            <h6 class="text-muted">{{ __('Key Strengths:') }}</h6>
                                            <ul class="list-unstyled">
                                                @foreach ($result['strengths'] as $strength)
                                                    @if(!empty(trim($strength)))
                                                        <li class="mb-1">
                                                            <i class="fas fa-check-circle text-success mr-2"></i>
                                                            {{ $strength }}
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    @if(!empty($result['summary']))
                                        <div class="summary-section mt-3">
                                            <h6 class="text-muted">{{ __('AI Summary:') }}</h6>
                                            <p class="text-muted mb-0">{{ $result['summary'] }}</p>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-info" role="alert">
                            {{ __('No qualified candidates found (minimum 2% match required).') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@include('includes.footer')
@endsection

@push('styles')
<style>
    .job-item {
        transition: all 0.3s ease;
        background: #f8f9fa;
    }
    .job-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .strengths-section {
        background: #f8fafc;
        padding: 10px;
        border-radius: 4px;
    }
    .badge-success {
        background-color: #28a745;
    }
    .badge-primary {
        background-color: #007bff;
    }
    .badge-warning {
        background-color: #ffc107;
        color: #212529;
    }
    .badge-info {
        background-color: #17a2b8;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // Add animation when scrolling to results
        $('.job-item').each(function(i) {
            $(this).delay(i * 200).animate({
                opacity: 1,
                marginTop: 0
            }, 400);
        });
    });
</script>
@endpush