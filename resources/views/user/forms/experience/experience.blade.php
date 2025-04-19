
<div class="resumebuildwrap">
        <h5>{{__('Experience')}}</h5>
        <div class="" id="experience_div"></div>
        <!-- <a type="button" class="prolinkadd" data-bs-toggle="modal" data-bs-target="#add_experience_modal_new">
                {{__('Add Experience')}}
        </a> -->
        <span class="d-flex justify-content-center" style="color:#8B96A4; font-family:Poppins; font-aize:16px;">Your experience will appear here</span>
        <div class="d-flex justify-content-center">
            <a type="button" class="prolinkadd" data-bs-toggle="modal" data-bs-target="#add_experience_modal_new">
                    {{__('Add Your Experience')}}
            </a>
        </div>
</div>

<div class="resumebuildwrap">
        <h5>{{__('Experience')}}</h5>
        <div class="" id="experience_list"></div>

</div>

<!-- Modal -->
<div class="modal fade" id="add_experience_modal_new" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="add_experience_modal_newLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <form class="form" id="add_edit_profile_experience" method="POST" action="{{ route('front.store.profile.experience', [$user->id]) }}">
            @csrf
                <div class="modal-header">
                    <h4 class="modal-title">Experience</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                   <div class="modal-body">
                        <div class="form-body">

                                <div class="formrow" id="div_title">
                                    <label for="title" class="bold">Experience Title</label>
                                    <input class="form-control" id="title" placeholder="Experience Title" name="title" type="text" value="{{(isset($profileExperience)? $profileExperience->title:'')}}" autocomplete="off">
                                    <span class="help-block title-error"></span>
                                </div>
                
                                <div class="formrow" id="div_company">
                                    <label for="company" class="bold">Company</label>
                                    <input class="form-control" id="company" placeholder="Company" name="company" type="text" value="{{(isset($profileExperience)? $profileExperience->company:'')}}" autocomplete="off">
                                    <span class="help-block company-error"></span>
                                </div>
                
                                <div class="formrow" id="div_country_id">
                                    <label for="country_id" class="bold">Country</label>
                                    <select name="country_id" class="form-control" id="experience_country_id">
                                        <option value="">Select Country</option>
                                        @foreach($countries as $id => $name)
                                           <option value="{{ $id }}" 
                                             {{ (isset($profileExperience) && $profileExperience->country_id == $id) || (!isset($profileExperience) && $siteSetting->default_country_id == $id) ? 'selected' : '' }}>
                                             {{ $name }}
                                           </option>
                                        @endforeach
                                        </select>
                                        <span class="help-block country_id-error"></span>
                                </div>
                                    
                                <div class="formrow" id="div_state_id">
                                  <label for="state_id" class="bold">State</label>
                                  <select name="state_id" id="experience_state_id" class="form-control">
                                    <option value="">Select State</option>
                                  </select>
                                  <span class="help-block state_id-error"></span>
                                </div>
                
                                <div class="formrow" id="div_city_id">
                                   <label for="city_id" class="bold">City</label>
                                     <select name="city_id" id="city_id_new" class="form-control">
                                        <option value="">Select City</option>
                                     </select>
                                   <span class="help-block city_id-error"></span>
                                </div>

                                <div class="formrow" id="div_date_start">
                                    <label for="date_start" class="bold">Experience Start Date</label>
                                    <input class="form-control" autocomplete="off" id="date_start_new" placeholder="Experience Start Date" name="date_start" type="text" value="{{(isset($profileExperience)? $profileExperience->date_start->format('Y-m-d'):'')}}">
                                    <span class="help-block date_start-error"></span>
                                </div>
                                
                                <div class="formrow" id="div_date_end">
                                    <label for="date_end" class="bold">Experience End Date</label>
                                    <input class="form-control datepicker" autocomplete="off" id="date_end_new" placeholder="Experience End Date" name="date_end" type="text" value="{{(isset($profileExperience)? $profileExperience->date_end->format('Y-m-d'):'')}}">
                                    <span class="help-block date_end-error"></span>
                                </div>

                                <div class="formrow" id="div_is_currently_working">
                                        <label for="is_currently_working" class="bold">Currently Working?</label>
                                        <div class="radio-list" style="margin-left:22px;">
                                                @php
                                                   $val_1_checked = '';
                                                   $val_2_checked = 'checked="checked"';
                                        
                                                   if (isset($profileExperience) && $profileExperience->is_currently_working == 1) {
                                                     $val_1_checked = 'checked="checked"';
                                                     $val_2_checked = '';
                                                   }
                                                @endphp
                                                <label class="radio-inline"><input id="currently_working" name="is_currently_working" type="radio" value="1" {{$val_1_checked}}> Yes </label>
                                                <label class="radio-inline"><input id="not_currently_working" name="is_currently_working" type="radio" value="0" {{$val_2_checked}}> No </label>
                                        </div>
                                        <span class="help-block is_currently_working-error"></span>
                                </div>

                                <div class="formrow" id="div_description">
                                        <label for="name" class="bold">Experience Description</label>
                                        <textarea name="description" class="form-control" id="description" placeholder="Experience description">{{(isset($profileExperience)? $profileExperience->description:'')}}</textarea>
                                        <span class="help-block description-error"></span>
                                </div>
                        </div>
                   </div>
                <div class="modal-footer">
                   <button type="button" class="btn btn-large btn-primary" onclick="submitProfileExperienceForm();">
                     Submit
                     <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                   </button>
                </div>
        </form>
    </div>
  </div>
</div>

@push('styles')
<style type="text/css">
    .datepicker>div {
        display: block;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function(){
    $('#experience_country_id').on('change', function(){
        var countryId = $(this).val();

        if(countryId) {
            $.ajax({
                url: '{{ route("front.profile.getStates.ByCountry") }}',
                type: "GET",
                data: { country_id: countryId },
                dataType: "json",
                success: function(data) {
                    $('#experience_state_id').empty();
                    $('#experience_state_id').append('<option value="">Select State</option>');

                    $.each(data, function(index, state) {
                        $('#experience_state_id').append(
                            '<option value="'+ state.state_id +'">'+ state.state +'</option>'
                        );
                    });
                },
                error: function(xhr, status, error) {
                    console.error("Terjadi kesalahan: " + error);
                }
            });
        } else {
            $('#experience_state_id').empty();
            $('#experience_state_id').append('<option value="">Select State</option>');
        }
    });
});

$(document).ready(function() {
    $('#experience_state_id').on('change', function(){
        var stateId = $(this).val();
        if(stateId) {
            $.ajax({
                url: '{{ route("front.profile.getCities.ByState") }}',
                type: "GET",
                data: { state_id: stateId },
                dataType: "json",
                success: function(data) {
                    $('#city_id_new').empty();
                    $('#city_id_new').append('<option value="">Select City</option>');
                    $.each(data, function(index, city) {
                        $('#city_id_new').append(
                            '<option value="'+ city.city_id +'">'+ city.city +'</option>'
                        );
                    });
                },
                error: function(xhr, status, error) {
                    console.error("Terjadi kesalahan saat mengambil city: " + error);
                }
            });
        } else {
            $('#city_id_new').empty();
            $('#city_id_new').append('<option value="">Select City</option>');
        }
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

function submitProfileExperienceForm() {
    let form = $("#add_edit_profile_experience");
    let formData = new FormData(form[0]);

    formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

    $.ajax({
        url: form.attr("action"),
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        beforeSend: function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        },
        success: function (response) {
            if (response.success) {
                showExperience();
                $("#add_experience_modal_new").modal("hide");
                form[0].reset();
                showToast(response.message, response.alertType, response.gravity, response.position);
            } else {
                showToast("Something went wrong.", "danger", "bottom", "right");
            }
        }
    });
}
</script>


<script type="text/javascript">

    /**************************************************/

    function showProfileExperienceModal(){
    $('#add_experience_modal').css('display','block');
    var myclosemodal = $('<div></div>');
    myclosemodal.addClass('modal-backdrop fade show');
    $('body').append(myclosemodal);

    $("#add_experience_modal").modal();

    loadProfileExperienceForm();

    }

    function loadProfileExperienceForm(){

    $.ajax({

    type: "POST",

            url: "{{ route('get.front.profile.experience.form', $user->id) }}",

            data: {"_token": "{{ csrf_token() }}"},

            datatype: 'json',

            success: function (json) {

                $("#add_experience_modal").html(json.html);

                initdatepicker();

                filterDefaultStatesExperience(0, 0);

            }

    });

    }

    function showProfileExperienceEditModal(profile_experience_id, state_id, city_id){
    $('#add_experience_modal').css('display','block');
    var myclosemodal = $('<div></div>');
    myclosemodal.addClass('modal-backdrop fade show');
    $('body').append(myclosemodal);



    $("#add_experience_modal").modal();

    loadProfileExperienceEditForm(profile_experience_id, state_id, city_id);

    }

    function loadProfileExperienceEditForm(profile_experience_id, state_id, city_id){

    $.ajax({

    type: "POST",

            url: "{{ route('get.front.profile.experience.edit.form', $user->id) }}",

            data: {"profile_experience_id": profile_experience_id, "_token": "{{ csrf_token() }}"},

            datatype: 'json',

            success: function (json) {

            $("#add_experience_modal").html(json.html);

            initdatepicker();

            filterDefaultStatesExperience(state_id, city_id);

            }

    });

    }

    
    function delete_profile_experience(id) {

    var msg = "{{__('Are you sure! you want to delete?')}}";

    if (confirm(msg)) {

    $.post("{{ route('delete.front.profile.experience') }}", {id: id, _method: 'DELETE', _token: '{{ csrf_token() }}'})

            .done(function (response) {

            if (response == 'ok')

            {

            $('#experience_' + id).remove();

            } else

            {

            alert('Request Failed!');

            }

            });

    }

    }

    function initdatepicker(){

    $(".datepicker").datepicker({

    autoclose: true,

            format:'yyyy-m-d'

    });

    }

    $(document).ready(function(){

    showExperience();

    initdatepicker();

    $(document).on('change', '#experience_country_id', function (e) {

    e.preventDefault();

    filterDefaultStatesExperience(0, 0);

    });

    $(document).on('change', '#experience_state_id', function (e) {

    e.preventDefault();

    filterDefaultCitiesExperience(0);

    });

    });

    function showExperience()

    {

    $.post("{{ route('show.front.profile.experience', $user->id) }}", {user_id: {{$user->id}}, _method: 'POST', _token: '{{ csrf_token() }}'})

            .done(function (response) {

            $('#experience_list').html(response);

            });

    }











    function filterDefaultStatesExperience(state_id, city_id)

    {

    var country_id = $('#experience_country_id').val();

    if (country_id != ''){

    $.post("{{ route('filter.lang.states.dropdown') }}", {country_id: country_id, state_id: state_id, new_state_id: 'experience_state_id', _method: 'POST', _token: '{{ csrf_token() }}'})

            .done(function (response) {

            $('#default_state_experience_dd').html(response);

            filterDefaultCitiesExperience(city_id);

            });

    }

    }

    function filterDefaultCitiesExperience(city_id)

    {

    var state_id = $('#experience_state_id').val();

    if (state_id != ''){

    $.post("{{ route('filter.lang.cities.dropdown') }}", {state_id: state_id, city_id: city_id, _method: 'POST', _token: '{{ csrf_token() }}'})

            .done(function (response) {

            $('#default_city_experience_dd').html(response);

            });

    }

    }

</script> 

@endpush