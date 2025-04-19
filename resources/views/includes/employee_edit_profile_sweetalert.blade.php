<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).on('click', '.delete-btn', function(e) {
            e.preventDefault();

            var uuid = $(this).data('cv-id');
            console.log(uuid);

            Swal.fire({
                title: 'Confirm Deletion'
                , html: '<p>Are you sure you want to delete this Curriculum Vitae?</p>'
                , icon: 'warning'
                , showCloseButton: true
                , showCancelButton: true
                , focusConfirm: false
                , confirmButtonText: 'Continued'
                , cancelButtonText: 'Cancelled'
                , buttonsStyling: false
                , customClass: {
                    confirmButton: 'btn btn-primary btn-sm mx-2'
                    , cancelButton: 'btn btn-danger mx-2'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "delete-front-profile-cv-persion/" + uuid
                        , type: 'DELETE'
                        , dataType: 'json'
                        , success: function(data) {
                            Swal.fire({
                                title: 'Successfully'
                                , text: data.message
                                , icon: 'success'
                                , timer: 2000
                                , timerProgressBar: true
                                , showConfirmButton: false
                            });
                            setTimeout(function() {
                                loadCvs();
                            }, 2000);
                        }
                        , error: function(xhr, ajaxOptions, thrownError) {
                            Swal.fire(
                                'Error!'
                                , 'Something went wrong, please try again.'
                                , 'error'
                            );
                        }
                    });
                }
            });
        });
    });
</script>

<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).on('click', '.delete-btn-projects', function(e) {
            e.preventDefault();

            var uuid = $(this).data('uuid');
            console.log(uuid);

            Swal.fire({
                title: 'Confirm Deletion'
                , html: '<p>Are you sure you want to delete this Project Protfolio?</p>'
                , icon: 'warning'
                , showCloseButton: true
                , showCancelButton: true
                , focusConfirm: false
                , confirmButtonText: 'Continued'
                , cancelButtonText: 'Cancelled'
                , buttonsStyling: false
                , customClass: {
                    confirmButton: 'btn btn-primary btn-sm mx-2'
                    , cancelButton: 'btn btn-danger mx-2'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "delete-front-profile-project-persion/" + uuid
                        , type: 'DELETE'
                        , dataType: 'json'
                        , success: function(data) {
                            Swal.fire({
                                title: 'Successfully'
                                , text: data.message
                                , icon: 'success'
                                , timer: 2000
                                , timerProgressBar: true
                                , showConfirmButton: false
                            });
                            setTimeout(function() {
                                showProjects();
                            }, 2000);
                        }
                        , error: function(xhr, ajaxOptions, thrownError) {
                            Swal.fire(
                                'Error!'
                                , 'Something went wrong, please try again.'
                                , 'error'
                            );
                        }
                    });
                }
            });
        });
    });
</script>

<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).on('click', '.delete-btn-experiences', function(e) {
            e.preventDefault();

            var uuid = $(this).data('uuid');

            Swal.fire({
                title: 'Confirm Deletion'
                , html: '<p>Are you sure you want to delete this Experience?</p>'
                , icon: 'warning'
                , showCloseButton: true
                , showCancelButton: true
                , focusConfirm: false
                , confirmButtonText: 'Continued'
                , cancelButtonText: 'Cancelled'
                , buttonsStyling: false
                , customClass: {
                    confirmButton: 'btn btn-primary btn-sm mx-2'
                    , cancelButton: 'btn btn-danger mx-2'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "delete-front-profile-experience-persion/" + uuid
                        , type: 'DELETE'
                        , dataType: 'json'
                        , success: function(data) {
                            Swal.fire({
                                title: 'Successfully'
                                , text: data.message
                                , icon: 'success'
                                , timer: 2000
                                , timerProgressBar: true
                                , showConfirmButton: false
                            });
                            setTimeout(function() {
                                showExperience();
                            }, 2000);
                        }
                        , error: function(xhr, ajaxOptions, thrownError) {
                            Swal.fire(
                                'Error!'
                                , 'Something went wrong, please try again.'
                                , 'error'
                            );
                        }
                    });
                }
            });
        });
    });
</script>

<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).on('click', '.delete-btn-educations', function(e) {
            e.preventDefault();

            var uuid = $(this).data('uuid');
            console.log(uuid);

            Swal.fire({
                title: 'Confirm Deletion'
                , html: '<p>Are you sure you want to delete this Education?</p>'
                , icon: 'warning'
                , showCloseButton: true
                , showCancelButton: true
                , focusConfirm: false
                , confirmButtonText: 'Continued'
                , cancelButtonText: 'Cancelled'
                , buttonsStyling: false
                , customClass: {
                    confirmButton: 'btn btn-primary btn-sm mx-2'
                    , cancelButton: 'btn btn-danger mx-2'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "delete-front-profile-education-persion/" + uuid
                        , type: 'DELETE'
                        , dataType: 'json'
                        , success: function(data) {
                            Swal.fire({
                                title: 'Successfully'
                                , text: data.message
                                , icon: 'success'
                                , timer: 2000
                                , timerProgressBar: true
                                , showConfirmButton: false
                            });
                            setTimeout(function() {
                                window.location.reload();
                            }, 2000);
                        }
                        , error: function(xhr, ajaxOptions, thrownError) {
                            Swal.fire(
                                'Error!'
                                , 'Something went wrong, please try again.'
                                , 'error'
                            );
                        }
                    });
                }
            });
        });
    });
</script>

<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).on('click', '.delete-btn-skills', function(e) {
            e.preventDefault();

            var uuid = $(this).data('uuid');
            console.log(uuid);

            Swal.fire({
                title: 'Confirm Deletion'
                , html: '<p>Are you sure you want to delete this Skill?</p>'
                , icon: 'warning'
                , showCloseButton: true
                , showCancelButton: true
                , focusConfirm: false
                , confirmButtonText: 'Continued'
                , cancelButtonText: 'Cancelled'
                , buttonsStyling: false
                , customClass: {
                    confirmButton: 'btn btn-primary btn-sm mx-2'
                    , cancelButton: 'btn btn-danger mx-2'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "delete-front-profile-skill-persion/" + uuid
                        , type: 'DELETE'
                        , dataType: 'json'
                        , success: function(data) {
                            Swal.fire({
                                title: 'Successfully'
                                , text: data.message
                                , icon: 'success'
                                , timer: 2000
                                , timerProgressBar: true
                                , showConfirmButton: false
                            });
                            setTimeout(function() {
                                window.location.reload();
                            }, 2000);
                        }
                        , error: function(xhr, ajaxOptions, thrownError) {
                            Swal.fire(
                                'Error!'
                                , 'Something went wrong, please try again.'
                                , 'error'
                            );
                        }
                    });
                }
            });
        });
    });
</script>

<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).on('click', '.delete-btn-languages', function(e) {
            e.preventDefault();

            var uuid = $(this).data('uuid');
            console.log(uuid);

            Swal.fire({
                title: 'Confirm Deletion'
                , html: '<p>Are you sure you want to delete this Language?</p>'
                , icon: 'warning'
                , showCloseButton: true
                , showCancelButton: true
                , focusConfirm: false
                , confirmButtonText: 'Continued'
                , cancelButtonText: 'Cancelled'
                , buttonsStyling: false
                , customClass: {
                    confirmButton: 'btn btn-primary btn-sm mx-2'
                    , cancelButton: 'btn btn-danger mx-2'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "delete-front.profile-language-persion/" + uuid
                        , type: 'DELETE'
                        , dataType: 'json'
                        , success: function(data) {
                            Swal.fire({
                                title: 'Successfully'
                                , text: data.message
                                , icon: 'success'
                                , timer: 2000
                                , timerProgressBar: true
                                , showConfirmButton: false
                            });
                            setTimeout(function() {
                                window.location.reload();
                            }, 2000);
                        }
                        , error: function(xhr, ajaxOptions, thrownError) {
                            Swal.fire(
                                'Error!'
                                , 'Something went wrong, please try again.'
                                , 'error'
                            );
                        }
                    });
                }
            });
        });
    });
</script>