<script>
    $(document).ready(function() {
        var table = $('#tableListCandidate').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            scrollCollapse: true,
            autoWidth: true,
            scrollX: true,
            ajax: {
                url: "{{ route('company.list.candidate') }}",
                data: function(d) {
                    d.jobName = $('#jobName').val();
                }
            },

            fixedColumns: true,
            columns: [{
                    data: 'full_name',
                    name: 'full_name',
                    width: '300px',
                },
                {
                    data: 'job_name',
                    name: 'job_name',
                    width: '180px',
                },
                {
                    data: 'status',
                    name: 'status',
                },
                {
                    data: 'expected_salary',
                    name: 'expected_salary',
                    className: 'dt-right',
                    width: '150px',
                },
                {
                    data: 'social',
                    name: 'social',
                    className: 'dt-center',
                },
                {
                    data: 'email',
                    name: 'email',
                },
                {
                    data: 'job_experience',
                    name: 'job_experience',
                },
                {
                    data: 'last_position',
                    name: 'last_position',
                    width: '300px',
                },
                {
                    data: 'education',
                    name: 'education',
                    width: '300px',
                },
                {
                    data: 'applied_at',
                    name: 'applied_at',
                },
                {
                    data: 'gender',
                    name: 'gender'
                },
                {
                    data: 'last_active',
                    name: 'last_active'
                },
                {
                    data: 'cv_link',
                    name: 'cv_link',
                    orderable: false,
                    searchable: false,
                    className: 'dt-center',
                },
                {
                    data: 'message',
                    name: 'message',
                    orderable: false,
                    searchable: false,
                    className: 'dt-center',
                },
            ],

        });

        // Apply job filter when selection changes
        $('#jobName').change(function() {
            table.ajax.reload();
        });


        $(document).on("click", ".change-status", function(e) {
            e.preventDefault();

            let status = $(this).data("status");
            let applicationId = $(this).data("id");
            let button = $(this).closest(".dropdown").find(".dropdown-toggle");



            $.ajax({
                url: "{{ route('company.list.update-job-application-status') }}",
                type: "POST",
                data: {
                    id: applicationId,
                    status: status,
                    _token: $("meta[name='csrf-token']").attr(
                        "content") // Ensure CSRF token is included
                },
                success: function(response) {
                    // Update the button text and class
                    button.text(status.charAt(0).toUpperCase() + status.slice(1));
                    button.removeClass().addClass(
                        "btn btn-light border rounded-pill dropdown-toggle px-3 py-1 bg-" +
                        status);

                    table.ajax.reload();
                },
                error: function(xhr) {
                    alert(`Failed to update status. Try again. ${JSON.stringify(xhr)}`);
                }
            });
        });
    });
</script>
