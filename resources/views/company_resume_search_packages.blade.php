@extends('layouts.app')
@section('content')
<!-- Header start -->
@include('includes.header')
<!-- Header end -->
<!-- Inner Page Title start -->
@include('includes.inner_page_title', ['page_title'=>__('Cvs Search Packages')])
<!-- Inner Page Title end -->
<?php $company = Auth::guard('company')->user(); ?>
<div class="listpgWraper">
    <div class="container">@include('flash::message')
        <div class="row"> @include('includes.company_dashboard_menu')
            <div class="col-md-9 col-sm-8">
                {{-- PERBAIKAN SYNTAX UTAMA --}}
                @if(null !== $success_package && !empty($success_package))
                <!-- Paket Aktif -->
                <div class="instoretxt mb-4">
                    <div class="credit">{{__('Your Package is')}}: <strong>{{$success_package->package_title}} - {{ $siteSetting->default_currency_code }}{{$success_package->package_price}}</strong></div>
                    <div class="credit">{{__('Package Duration')}} : <strong>{{Carbon\Carbon::parse($company->cvs_package_start_date)->format('d M, Y')}}</strong> - <strong>{{Carbon\Carbon::parse($company->cvs_package_end_date)->format('d M, Y')}}</strong></div>
                    <div class="credit">{{__('Availed quota')}} : <strong>{{$company->availed_cvs_quota}}</strong> - <strong>{{$company->cvs_quota}}</strong></div>
                </div>

                <!-- AI Search Section -->
                <div class="ai-search-section card mb-4">
                    <div class="card-body">
                        <h4 class="card-title mb-3">{{__('AI-Powered Smart Search')}}</h4>
                        <form id="aiSearchForm" action="{{ route('ai.search') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label>{{__('Describe your ideal candidate (e.g. "Python developer with 5+ years experience in machine learning projects")')}}</label>
                                <textarea 
                                    name="ai_query" 
                                    class="form-control" 
                                    rows="3"
                                    placeholder="{{__('Enter your description...')}}"
                                    required></textarea>
                                <div class="invalid-feedback" id="aiQueryError"></div>
                            </div>
                            <br>
                            <button type="submit" class="btn btn-ai" id="submitBtn">
                                <i class="fa fa-magic"></i> 
                                <span class="btn-text">{{__('Search with AI')}}</span>
                                <div class="spinner-border spinner-border-sm d-none" id="spinner"></div>
                            </button>
                        </form>
                        <div class="alert alert-danger d-none mt-3" id="errorAlert"></div>
                    </div>
                </div>

                <!-- AI Search Results -->
                @if(isset($aiResults))
<div class="ai-results card mb-5" id="aiResultsContainer">
    <div class="card-body">
        <h4 class="card-title mb-3">{{__('AI Matching Results')}}</h4>
        <div class="table-responsive">
            <table class="table table-hover ai-table">
                <thead>
                    <tr>
                        <th>{{__('Match Score')}}</th>
                        <th>{{__('Candidate')}}</th>
                        <th>{{__('Education')}}</th>
                        <th>{{__('Experience')}}</th>
                        <th>{{__('AI Analysis')}}</th>
                        <th>{{__('Action')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($aiResults as $result)
                    <tr>
                        <td>
                            <div class="progress">
                                <div class="progress-bar bg-ai" 
                                     role="progressbar" 
                                     style="width: {{ $result['score'] }}%" 
                                     aria-valuenow="{{ $result['score'] }}" 
                                     aria-valuemin="0" 
                                     aria-valuemax="100">
                                    {{ round($result['score'], 1) }}%
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="candidate-info">
                                <h5>{{ $result['name'] }}</h5>
                                <div class="meta">
                                    <span>{{ optional($result['industry'])->industry }}</span>
                                    <span>{{ $result['experience'] }} {{__('Years Exp.')}}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            @foreach($result['educations'] as $edu)
                            <div class="education-item mb-2">
                                <div class="fw-bold">{{ $edu['degree'] }}</div>
                                <div class="text-muted small">
                                    {{ $edu['institution'] }} ({{ $edu['tahun'] }})
                                </div>
                            </div>
                            @endforeach
                        </td>
                        <td>
                            @foreach($result['experiences'] as $exp)
                            <div class="experience-item mb-2">
                                <div class="fw-bold">{{ $exp['position'] }}</div>
                                <div class="text-muted small">
                                    {{ $exp['company'] }} <br>
                                    {{ $exp['duration'] }}
                                </div>
                            </div>
                            @endforeach
                        </td>
                        <td class="ai-summary">
                            {!! $result['summary'] !!}
                            <div class="ai-tags mt-2">
                                @foreach($result['strengths'] as $strength)
                                <span class="badge bg-success">{{ $strength }}</span>
                                @endforeach
                            </div>
                        </td>
                        <td>
                            <a href="{{ route('user.profile', $result['id']) }}" 
                               class="btn btn-ai btn-sm"
                               target="_blank">
                               <i class="fa fa-user-tie"></i> {{__('Profile')}}
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

                <!-- Traditional Search Section -->
                <div class="traditional-search">
                    {{-- TEMPAT UNTUK FORM TRADISIONAL --}}
                    <!-- Tambahkan kode form pencarian tradisional di sini -->
                </div>

                @endif {{-- PENUTUP @if UTAMA --}}

                <!-- Package Upgrade Section -->
                <div class="paypackages">
                    {{-- TEMPAT UNTUK PAKET UPGRADE --}}
                    <!-- Tambahkan kode paket upgrade di sini -->
                </div>

            </div>
        </div>
    </div>
</div>
@include('includes.footer')
@endsection

@push('styles')
<style>
    #spinner {
        margin-left: 8px;
        width: 1.2rem;
        height: 1.2rem;
    }
    
    .invalid-feedback {
        display: block;
        color: #dc3545;
        margin-top: 4px;
    }
    
    #errorAlert {
        transition: opacity 0.3s ease;
    }
    
    .btn-ai {
        background: linear-gradient(45deg, #3f87a6, #ebf8e1);
        color: #fff;
        border: none;
        padding: 10px 25px;
        border-radius: 25px;
        transition: all 0.3s ease;
    }
    
    .btn-ai:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    
    .progress-bar.bg-ai {
        background-color: #3f87a6;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('aiSearchForm');
    const errorAlert = document.getElementById('errorAlert');
    const aiQueryError = document.getElementById('aiQueryError');
    const spinner = document.getElementById('spinner');
    const submitBtn = document.getElementById('submitBtn');
    const btnText = submitBtn.querySelector('.btn-text');
    const resultsContainer = document.getElementById('aiResultsContainer');

    const resultsContainer = document.getElementById('aiResultsContainer');
if (resultsContainer) {
    resultsContainer.innerHTML = "Some content"; // hanya lakukan ini jika elemen ada
} else {
    console.error("Element with id 'aiResultsContainer' not found.");
}


    // Fungsi untuk menampilkan error
    const showError = (message) => {
        errorAlert.textContent = message;
        errorAlert.classList.remove('d-none');
        setTimeout(() => {
            errorAlert.classList.add('d-none');
        }, 5000);
    };

    // Fungsi untuk menangani response error
    const handleError = (error) => {
        console.error('Error:', error);
        spinner.classList.add('d-none');
        btnText.textContent = 'Search with AI';
        
        if (error.message.toLowerCase().includes('network error')) {
            showError('Koneksi internet terputus. Periksa jaringan Anda.');
        } else if (error.message.includes('500')) {
            showError('Terjadi kesalahan server. Silakan coba lagi nanti.');
        } else {
            showError(error.message);
        }
    };

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const formData = {
            ai_query: document.querySelector('[name="ai_query"]').value.trim() // Trim the query
        };

        try {
            if (!formData.ai_query) {
                aiQueryError.textContent = 'Please enter a search query';
                return;
            }
            
            // Tampilkan loading state
            submitBtn.disabled = true;
            spinner.classList.remove('d-none');
            btnText.textContent = 'Analyzing...';
            errorAlert.classList.add('d-none');

            const response = await fetch("{{ route('ai.search') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    'Accept': 'application/json' 
                },
                body: JSON.stringify(formData)
            });

            if (!response.ok) {
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    const errorData = await response.json();
                    throw new Error(errorData.message || 'Server error');
                } else {
                    const text = await response.text();
                    throw new Error(`Server returned ${response.status}: ${text}`);
                }
            }
            
            const data = await response.json();
            
            if (data.success) {
                resultsContainer.innerHTML = data.html;
                resultsContainer.scrollIntoView({ behavior: 'smooth' });
            } else {
                throw new Error(data.message || 'Unknown error');
            }
        } catch (error) {
            handleError(error);
        } finally {
            submitBtn.disabled = false;
            spinner.classList.add('d-none');
            btnText.textContent = 'Search with AI';
        }
    });
});

</script>

@endpush
