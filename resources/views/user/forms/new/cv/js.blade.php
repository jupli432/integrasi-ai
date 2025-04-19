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
                    loadCvs();
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

$(document).ready(function() {
loadCvs();
});

function loadCvs() {
         $.ajax({
             url: 'user/cvs', 
             method: 'GET',
             success: function(response) {
                 $('#cvContainer').empty();
 
                 $.each(response, function(index, cv) {
                     let fileName = cv.cv_file || 'CV-Application.pdf'; 
                     let title = cv.title || 'Untitled CV';
                     let sizeInfo = '0.07 MB';
 
                     let dateObj = new Date(cv.created_at);
                     let uploadDate = ('0' + dateObj.getDate()).slice(-2) + '-' +
                                     ('0' + (dateObj.getMonth() + 1)).slice(-2) + '-' +
                                     dateObj.getFullYear();
 
                     let cardHtml = `
                     <div class="col-sm-6 mb-1 mt-1">
                         <div class="card-wrapper-candidate-curriculum-vitae">
                             <div class="card-candidate-curriculum-vitae">
                                 <div class="frame-parent-candidate-curriculum-vitae">
                                     <div class="frame-group-candidate-curriculum-vitae">
                                         <div class="cv-applicationpdf-wrapper-candidate-curriculum-vitae">
                                             <a href="/cvs/${cv.cv_file}" target="_blank" class="cv-applicationpdf-candidate-curriculum-vitae">${fileName}</a>
                                         </div>
                                         <div class="mb-uploaded-candidate-curriculum-vitae">Uploaded on ${uploadDate}</div>
                                     </div>
                                     <div class="button-candidate-list-curriculum-vitae">
                                         <img class="iconuitrash-can-candidate-curriculum-vitae" alt="" src="{{ asset('images/candidate_dashboard/icon/trash.png') }}">
                                         <button type="button" class="remove-file-candidate-curriculum-vitae delete-btn" data-cv-id="${cv.id}">
                                             Remove File
                                         </button>
                                     </div>
                                 </div>
                             </div>
                         </div>
                     </div>
                 `;
                 $('#cvContainer').append(cardHtml);
                 });
             },
             error: function(xhr) {
                 console.error(xhr.responseText);
             }
         });
}
</script>