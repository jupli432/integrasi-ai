<div class="resumebuildwrap">
    <h5 onclick="showLanguages();">{{ __('Languages') }}</h5>


    <div class="row-language-edit-profile">
        <div class="language-placeholder">{{ __('Your language will appear here') }}</div>
        <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#add_language_modal"
            onclick="showProfileLanguageModal();" class="language-btn-add-custom">{{ __('Add Your Language') }}</a>
    </div>

</div>
<div class="resumebuildwrap">
    <h5 onclick="showLanguages();">{{ __('Languages') }}</h5>

    <div class="" id="language_div" style="padding: 15px 0px 10px 2px; gap: 16px;"></div>

</div>

@include('user.forms.language.language_form')

@push('scripts')
    <script type="text/javascript">
        function showProfileLanguageModal() {
            $("#add_language_modal").modal('show');
            loadProfileLanguageForm();
        }

        function loadProfileLanguageForm() {
            $.ajax({
                type: "POST",
                url: "{{ route('get.front.profile.language.form', $user->id) }}",
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                datatype: 'json',
                success: function(json) {
                    $("#language_id, #language_level_id").empty();
                    // Append langguage
                    $("#language_id").append('<option value="">{{ __('Select Language') }}</option>');
                    Object.values(json.languages).forEach(val => {
                        $("#language_id").append('<option value="' + val.id +
                            '">' + val.lang +
                            '</option>');
                    });
                    // Append  Lang Level
                    $("#language_level_id").append(
                        '<option value="">{{ __('Select Language Level') }}</option>');
                    Object.values(json.languageLevels).forEach(val => {
                        $("#language_level_id").append('<option value="' + val.language_level_id +
                            '">' + val.language_level +
                            '</option>');
                    });
                }
            });
        }

        function showProfileLanguageEditModal(profile_language_id) {
            $('#add_language_modal').css('display', 'block');
            var myclosemodal = $('<div></div>');
            myclosemodal.addClass('modal-backdrop fade show');
            $('body').append(myclosemodal);
            $("#add_language_modal").modal();

            loadProfileLanguageEditForm(profile_language_id);

        }

        function loadProfileLanguageEditForm(profile_language_id) {
            $.ajax({
                type: "POST",
                url: "{{ route('get.front.profile.language.edit.form', $user->id) }}",
                data: {
                    "profile_language_id": profile_language_id,
                    "_token": "{{ csrf_token() }}"
                },
                datatype: 'json',
                success: function(json) {
                    $("#add_language_modal").html(json.html);

                }

            });

        }

        function submitProfileLanguageForm() {

            var form = $('#add_edit_profile_language');

            $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                data: form.serialize(),
                dataType: 'json',
                success: function(response) {
                    $('#add_language_modal').modal('hide');
                    showToast(response.message, response.alertType, response.gravity, response.position);
                    showLanguages();
                },
                error: function(json) {
                    showToast("Failed to save language. Try again.", "danger", "bottom", "right");
                }
            });
        }

        function delete_profile_language(id) {
            var msg = "{{ __('Are you sure! you want to delete?') }}";

            if (confirm(msg)) {
                $.post("{{ route('delete.front.profile.language') }}", {
                        id: id,
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}'
                    })
                    .done(function(response) {
                        if (response == 'ok') {
                            $('#language_' + id).remove();
                        } else {
                            alert('Request Failed!');
                        }
                    });
            }
        }

        $(document).ready(function() {
            showLanguages();
        });

        function showLanguages() {
            $.post("{{ route('show.front.profile.languages', $user->id) }}", {
                    user_id: {{ $user->id }},
                    _method: 'POST',
                    _token: '{{ csrf_token() }}'
                })
                .done(function(response) {

                    $('#language_div').html(response);

                });
        }
    </script>
@endpush
