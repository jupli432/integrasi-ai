@extends('layouts.app')

@section('content')
@include('includes.header')
@include('includes.inner_page_title', ['page_title'=>__('CV Analysis')])

<div class="listpgWraper">
    <div class="container">
        <div class="row"> 
            @include('includes.user_dashboard_menu')

            <div class="col-lg-9 col-sm-8">
                <div class="userdashbox">
                    <h3>{{__('CV Analysis')}}</h3>
                    
                    <!-- Form untuk memilih CV dari daftar yang ada -->
                    <form id="cvAnalyzeForm" method="POST" action="{{ route('cv.analyze') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="cv_id">{{__('Choose Your CV to Analyze:')}}</label>
                            <select class="form-control" id="cv_id" name="cv_id" required>
                                <option value="" disabled selected>{{__('Select CV')}}</option>
                                @foreach($user->profileCvs as $cv)
                                    <option value="{{ $cv->id }}">{{ $cv->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <br>
                        <button type="submit" class="btn btn-primary" id="submitBtn">{{__('AI Analyze CV')}}</button>
                    </form>

                    <!-- Hasil Analisis -->
                    <div id="analysisResults" style="display: none;">
                        <h3>{{__('Analysis Results')}}</h3>
                        <div class="analysis-section">
                            <h4 class="section-title">Potential Weaknesses</h4>
                            <ol id="weaknessesList" class="analysis-list"></ol>
                        </div>
                        <div class="analysis-section">
                            <h4 class="section-title">Suggestions for Improvement</h4>
                            <ul id="suggestionsList" class="analysis-list"></ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('includes.footer')

@endsection

@push('styles')
<style>
    .analysis-section {
        margin-bottom: 30px;
        background: #f9f9f9;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    
    .section-title {
        color: #2c3e50;
        border-bottom: 2px solid #eee;
        padding-bottom: 10px;
        margin-bottom: 15px;
    }
    
    .analysis-list {
        padding-left: 20px;
    }
    
    .analysis-list li {
        margin-bottom: 12px;
        line-height: 1.6;
        padding-left: 10px;
        border-left: 3px solid #3498db;
    }
    
    #weaknessesList li {
        color: #e74c3c;
    }
    
    #suggestionsList li {
        color: #27ae60;
    }
    
    strong {
        font-weight: 600;
    }
</style>
@push('scripts')
<script>
document.getElementById('cvAnalyzeForm').addEventListener('submit', async function(event) {
    event.preventDefault();

    const submitBtn = document.getElementById('submitBtn');
    const analysisResults = document.getElementById('analysisResults');
    const weaknessesList = document.getElementById('weaknessesList');
    const suggestionsList = document.getElementById('suggestionsList');
    
    // Reset UI
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Analyzing...';
    weaknessesList.innerHTML = '<li class="text-muted">Analyzing weaknesses...</li>';
    suggestionsList.innerHTML = '<li class="text-muted">Analyzing suggestions...</li>';
    analysisResults.style.display = 'block';

    try {
        const formData = new FormData(this);
        
        const response = await fetch("{{ route('cv.analyze') }}", {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        console.log('Full API Response:', data);

        // Clear loading messages
        weaknessesList.innerHTML = '';
        suggestionsList.innerHTML = '';

        // Process Weaknesses (data.results[0])
        if (data.results && data.results[0]) {
            const weaknessesText = data.results[0];
            // Extract numbered points (1., 2., etc.)
            const weaknesses = weaknessesText.split(/\d+\.\s+/).filter(item => item.trim().length > 0);
            
            if (weaknesses.length > 1) { // First item is empty
                weaknesses.slice(1).forEach(weakness => {
                    const listItem = document.createElement('li');
                    listItem.innerHTML = weakness
                        .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
                        .replace(/\n/g, '<br>');
                    weaknessesList.appendChild(listItem);
                });
            } else {
                weaknessesList.innerHTML = '<li class="text-muted">No weaknesses identified</li>';
            }
        }

        // Process Suggestions (data.results[1])
        if (data.results && data.results[1]) {
            const suggestionsText = data.results[1];
            console.log('Raw suggestions text:', suggestionsText);
            
            // Improved suggestion parsing - handles both * and ** formats
            const suggestions = suggestionsText.split(/(?:\*\s+|\*\*\s+)/).filter(item => item.trim().length > 0);
            console.log('Parsed suggestions:', suggestions);
            
            if (suggestions.length > 1) { // First item is typically empty
                suggestions.slice(1).forEach(suggestion => {
                    const listItem = document.createElement('li');
                    listItem.innerHTML = suggestion
                        .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
                        .replace(/\*(.*?)\*/g, '<em>$1</em>')
                        .replace(/\n/g, '<br>')
                        .trim();
                    suggestionsList.appendChild(listItem);
                });
            } else {
                suggestionsList.innerHTML = '<li class="text-muted">No suggestions available</li>';
            }
        }

    } catch (error) {
        console.error('Analysis error:', error);
        
        weaknessesList.innerHTML = '<li class="text-danger">Error during analysis</li>';
        suggestionsList.innerHTML = `<li class="text-danger">${error.message}</li>`;
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = '{{__("AI Analyze CV")}}';
    }
});
</script>
@endpush