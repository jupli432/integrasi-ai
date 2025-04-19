<div class="resumebuildwrap">
<h5 id="cvs" onclick="showCvs();">{{__('Curriculum vitae')}}</h5>
<div class="row">
    <div class="col-md-12">
        <div class="" id="cvs_div"></div>
    </div>
</div>
<a type="button" class="prolinkadd" data-bs-toggle="modal" data-bs-target="#add_cv_modal_new">
    Add CV
</a>

<div class="modal fade" id="add_cv_modal_new" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="add_cv_modal_newLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">{{__('Submit CV')}}</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
            <form class="form" id="add_edit_profile_cv" method="POST" action="{{ route('store.front.profile.cv.new', [$user->id]) }}" enctype="multipart/form-data">
                @csrf

                <div class="modal-body">
                    <div class="form-body">
                        <input type="hidden" name="id_cv" id="id_cv">
                        <div class="formrow" id="div_title">
                            <input class="form-control" id="title" placeholder="{{__('CV Title')}}" name="title" type="text" value="{{(isset($profileCv)? $profileCv->title:'')}}">
                            <span class="help-block title-error"></span> </div>
                
                        @if(isset($profileCv))
                            <div class="formrow">
                                <i class="fas fa-file"></i>
                                {{ImgUploader::print_doc("cvs/$profileCv->cv_file", $profileCv->title, $profileCv->title)}}
                            </div>
                        @endif
                
                        <div class="formrow" id="div_cv_file">
                            <input name="cv_file" id="cv_file" type="file" />
                            <span class="help-block cv_file-error"></span>
                        </div>
                
                        <div class="formrow" id="div_is_default">
                            <label for="is_default" class="bold">{{__('Is default?')}}</label>
                            <div class="radio-list">
                                    @php
                                        $val_1_checked = '';
                                        $val_2_checked = 'checked="checked"';
                        
                                        if (isset($profileCv) && $profileCv->is_default == 1) {
                                            $val_1_checked = 'checked="checked"';
                                            $val_2_checked = '';
                                        }                                    
                                    @endphp
                                <label class="radio-inline"><input id="default" name="is_default" type="radio" value="1" {{$val_1_checked}}> {{__('Yes')}} </label>
                                <label class="radio-inline"><input id="not_default" name="is_default" type="radio" value="0" {{$val_2_checked}}> {{__('No')}} </label>
                            </div>
                            <span class="help-block is_default-error"></span>
                        </div>
                    </div>  
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">{{__('Submit CV')}} <i class="fas fa-arrow-circle-right" aria-hidden="true"></i></button>
                </div>
            </form>
      </div>
    </div>
</div>

@push('css')
<style type="text/css">
    .datepicker>div {
        display: block;
    }
</style>
@endpush
@push('scripts') 

<script>
$(document).ready(function () {
    $("#add_edit_profile_cv").on("submit", function (e) {
        e.preventDefault();

        var formData = new FormData(this);

        $.ajax({
            url: $(this).attr("action"),
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function () {

            },
            success: function (response) {
                if (response.success) {
                    showCvs();
                    $("#add_cv_modal_new").modal("hide");
                    $("#add_edit_profile_cv")[0].reset();
                    showToast(response.message, response.alertType, response.gravity, response.position);
                } else {
                    showToast("Something went wrong.", "danger", "bottom", "right");
                }
            },
            error: function (xhr) {
                showToast("Failed to save CV. Try again.", "danger", "bottom", "right");
            }
        });
    });
});

function showToast(message, type, gravity, position) {
        let backgroundColor = "#056CB7";

        if (type === "success") backgroundColor = "#28A745";
        else if (type === "warning") backgroundColor = "#FFC107";
        else if (type === "danger") backgroundColor = "#DC3545";

        Toastify({
            text: message,
            duration: 5000,
            avatar: "{{ asset('images/info.svg') }}",
            close: true,
            gravity: gravity,
            position: position,
            backgroundColor: backgroundColor
        }).showToast();
}

function ModalShowProfileCvEdit(cv_id) {
    $('#add_edit_profile_cv')[0].reset();
    $('#cv_id').val(cv_id);  

    $.ajax({
        url: '/edit-get-front-profile-cv/' + cv_id,
        type: 'GET',
        success: function(response) {
            if (response.success) {
                let data = response.data;
                
                $('#title').val(data.title);
                $('#id_cv').val(data.id);

                let filePreview = '';
                if (data.cv_file) {
                    filePreview = `<i class="fas fa-file"></i> 
                        <a href="/cvs/${data.cv_file}" target="_blank">${data.title}</a>`;
                }

                $('#div_cv_file').html(filePreview + 
                    '<br><input name="cv_file" id="cv_file" type="file" class="form-control" />'
                );

                if (data.is_default == 1) {
                    $('#default').prop('checked', true);
                } else {
                    $('#not_default').prop('checked', true);
                }

                $('#add_cv_modal_new').modal('show'); 
            } else {
                showToast("Something went wrong.", "danger", "bottom", "right");
            }
        },
        error: function() {
            showToast("Failed to Get CV. Try again.", "danger", "bottom", "right");
        }
    });
}

$(document).ready(function(){
    var originalDivCvFile = $('#div_cv_file').html();

    $('.prolinkadd').on('click', function(){
        var modal = $('#add_cv_modal_new');
        modal.find('form')[0].reset();
        $('#div_cv_file').html(originalDivCvFile);
    });
});


/********************First Load CVs************************************/
    $(document).ready(function(){
        showCvs();
    });
    function showProfileCvModal(){
        $('#add_cv_modal').css('display','block');
        var myclosemodal = $('<div></div>');
        myclosemodal.addClass('modal-backdrop fade show');
        $('body').append(myclosemodal);
        $("#add_cv_modal").modal('show');
        loadProfileCvForm();
    }

    function loadProfileCvForm(){
        $.ajax({
            type: "POST",
            url: "{{ route('get.front.profile.cv.form', $user->id) }}",
            data: {"_token": "{{ csrf_token() }}"},
            datatype: 'json',
            success: function (json) {
                $("#add_cv_modal").html(json.html);
            }
        });
    }
/*****************************************/
    function showCvs() {
        $.post("{{ route('show.front.profile.cvs', $user->id) }}", {user_id: {{$user->id}}, _method: 'POST', _token: '{{ csrf_token() }}'})
            .done(function (response) {
                $('#cvs_div').html(response);
            });
    }
</script>
@endpush