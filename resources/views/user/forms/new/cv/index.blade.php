<div class="build-your-resume-parent-candidate-curriculum-vitae">
    <div class="build-your-resume-candidate-curriculum-vitae">
        <div class="job-updates-candidate-curriculum-vitae">
            <div class="title-candidate-curriculum-vitae">
                <h3>{{__('Build Your Resume')}}</h3>
            </div>
            <div class="row-candidate-curriculum-vitae">
                <div class="your-resume-will-candidate-curriculum-vitae">{{__('Your resume will appear here')}}</div>
                <button class="add-your-project-candidate-curriculum-vitae" data-bs-toggle="modal" data-bs-target="#add_cv_modal_new">
                    {{__('Add Your Resume')}}
                </button>
            </div>
        </div>
    </div>
    <div class="build-your-resume-candidate-curriculum-vitae">
        <div class="job-updates-candidate-curriculum-vitae">
            <div class="tilte-candidate-curriculum-vitae">
                <b class="build-resume-candidate-curriculum-vitae"><h3>{{__('Build Your Resume')}}</h3></b>
            </div>
                
            <div class="row-candidate-curriculum-vitae">
                 <div class="content-candidate-curriculum-vitae">
                        <div class="container">
                            <div class="row" id="cvContainer"></div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('user.forms.new.cv.modal')
@push('scripts') 
@include('user.forms.new.cv.js')
@endpush