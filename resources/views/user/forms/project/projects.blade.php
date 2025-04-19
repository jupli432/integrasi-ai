<div class="resumebuildwrap">
        <h5 onclick="showProjects();">{{__('Projects')}}</h5>
        <div class="row" id="projects_div"></div>
        <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#modal_add_project" class="prolinkadd">
            Add Project
        </a>
</div>

<div class="modal fade" id="modal_add_project" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_add_projectLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Submit Project</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="form" id="add_edit_profile_project" method="POST" action="{{ route('store.front.profile.project.new', [$user->id]) }}">
                    @csrf

                    <div class="form-body">
                        <input type="hidden" name="id_project" id="id_project">
                        <div class="formrow" id="div_name">
                            <input class="form-control" id="name" placeholder="Project Name" name="name" type="text">
                            <span class="help-block name-error"></span>
                        </div>

                        <div class="formrow" id="div_image">
                            <div class="uploadphotobx dropzone needsclick dz-clickable"  id="dropzone">
                                <div class="input-group mb-3">
                                    <label class="input-group-text" for="fileImage">Upload</label>
                                    <input name="project_images" type="file" class="form-control" id="fileImage">
                                </div>
                            </div>
                            <span class="help-block image-error"></span>
                        </div>

                        <div class="formrow" id="div_url">
                            <input class="form-control" id="url" placeholder="Project URL" name="url" type="text" value="{{(isset($profileProject)? $profileProject->url:'')}}">
                            <span class="help-block url-error"></span>
                        </div>

                        <div class="formrow" id="div_date_start">
                            <input class="form-control date_start_new" id="date_start_new" placeholder="Project Start Date" name="date_start" type="text" autocomplete="off" value="{{(isset($profileProject)? $profileProject->date_start:'')}}">
                            <span class="help-block date_start-error"></span>
                        </div>

                        <div class="formrow" id="div_date_end">
                            <input class="form-control" autocomplete="off" id="date_end_new" placeholder="Project End Date" name="date_end" type="text" value="{{(isset($profileProject)? $profileProject->date_end:'')}}">
                            <span class="help-block date_end-error"></span>
                        </div>

                        <div class="formrow" id="div_is_on_going">
                            <label for="is_on_going" class="bold">Is Currently Ongoing?</label>
                            <div class="radio-list">
                                @php
                                    $val_1_checked = '';
                                    $val_2_checked = 'checked="checked"';
                                @endphp
                                    @if (isset($profileProject) && $profileProject->is_on_going == 1)
                                        @php
                                            $val_1_checked = 'checked="checked"';
                                            $val_2_checked = '';
                                        @endphp
                                    @endif
                                <label class="radio-inline"><input id="on_going" name="is_on_going" type="radio" value="1" {{$val_1_checked}}> {{__('Yes')}} </label>
                                <label class="radio-inline"><input id="not_on_going" name="is_on_going" type="radio" value="0" {{$val_2_checked}}> {{__('No')}} </label>
                            </div>
                            <span class="help-block is_on_going-error"></span>
                        </div>

                        <div class="formrow" id="div_description">
                            <textarea name="description" class="form-control" id="description" placeholder="Project description">{{(isset($profileProject)? $profileProject->description:'')}}</textarea>
                            <span class="help-block description-error"></span>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-large btn-primary" onclick="submitProfileProjectForm();">
                            {{__('Add Project')}} <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link href="{{ asset('/') }}dropzone/dropzone.min.css" rel="stylesheet">
@endpush

@push('scripts') 
@include('includes.employee_edit_profile_datepicker')
<script src="{{ asset('/') }}dropzone/dropzone.min.js"></script> 

<script>
$(document).ready(function(){
    var originalDivImage = $('#div_image').html();
    $('.prolinkadd').on('click', function(){
        var modal = $('#modal_add_project');
        modal.find('form#add_edit_profile_project')[0].reset();
        $('#div_image').html(originalDivImage);
    });
});

function submitProfileProjectForm() {
    let form = $("#add_edit_profile_project");
    let formData = new FormData(form[0]);

    $.ajax({
        url: form.attr("action"),
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        beforeSend: function () {
            
        },
        success: function (response) {
            if (response.success) {
                showProjects();
                $("#modal_add_project").modal("hide");
                form[0].reset();
                showToast(response.message, response.alertType, response.gravity, response.position);
            } else {
                showToast("Something went wrong.", "danger", "bottom", "right");
            }
        },
        error: function (xhr) {
            showToast("Failed to save project. Try again.", "danger", "bottom", "right");
        }
    });
}
    
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

/*********************First Load Projects******************************************/
function showProfileProjectModal(){
    $('#add_project_modal').css('display','block');
    var myclosemodal = $('<div></div>');
    myclosemodal.addClass('modal-backdrop fade show');
    $('body').append(myclosemodal);
    $("#add_project_modal").modal();
    loadProfileProjectForm();
}

function loadProfileProjectForm(){
    $.ajax({
    type: "POST",
            url: "{{ route('get.front.profile.project.form', $user->id) }}",
            data: {"_token": "{{ csrf_token() }}"},
            datatype: 'json',
            success: function (json) {
            $("#add_project_modal").html(json.html);
            createDropZone();
            initdatepicker();
            }
    });
}

/*****************************************/
function showProjects()
{
    $.post("{{ route('show.front.profile.projects', $user->id) }}", {user_id: {{$user->id}}, _method: 'POST', _token: '{{ csrf_token() }}'})
            .done(function (response) {
            $('#projects_div').html(response);
    });

}

function showProfileProjectEditModal(projectId) {
    $.ajax({
        url: '/edit-front-profile-project/' + projectId,
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            $('#id_project').val(data.id);
            $('#name').val(data.name);
            $('#url').val(data.url);
            $('#date_start_new').val(data.date_start);
            $('#date_end_new').val(data.date_end);
            $('#description').val(data.description);
            
            if(data.is_on_going == 1){
                $('#on_going').prop('checked', true);
            } else {
                $('#not_on_going').prop('checked', true);
            }
            $('#modal_add_project').modal('show');
        },
        error: function(err) {
            showToast("An error occurred while retrieving project data.", "danger", "bottom", "right");
        }
    });
}
</script>

<script type="text/javascript">
$(document).ready(function(){
        showProjects();
        initdatepicker();
});

// function createDropZone(){
// var myDropzone = new Dropzone("div#dropzone", {
// url: "{{ route('upload.front.project.temp.image') }}",
//         paramName: "image",
//         uploadMultiple: false,
//         ignoreHiddenFiles: true,
//         maxFilesize: <?php echo $upload_max_filesize; ?>,
//         acceptedFiles: 'image/*'
// });

// myDropzone.on("complete", function (file) {
//     imageUploadedFlag = false;
//     });
// }
 
// function showProfileProjectEditModal(project_id){
//     $('#add_project_modal').css('display','block');
//     var myclosemodal = $('<div></div>');
//     myclosemodal.addClass('modal-backdrop fade show');
//     $('body').append(myclosemodal);

//     $("#add_project_modal").modal();

//     loadProfileProjectEditForm(project_id);

//     }

    function loadProfileProjectEditForm(project_id){

    $.ajax({

    type: "POST",

            url: "{{ route('get.front.profile.project.edit.form', $user->id) }}",

            data: {"project_id": project_id, "_token": "{{ csrf_token() }}"},

            datatype: 'json',

            success: function (json) {

            $("#add_project_modal").html(json.html);

            createDropZone();

            initdatepicker();

            }

    });

    }

</script>

@endpush