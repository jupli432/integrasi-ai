<div class="modal fade" id="add_language_modal" tabindex="-1" aria-labelledby="addlangModalLabel" aria-hidden="true"
    role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">

                <h4 class="modal-title">{{ __('Add Language') }}</h4>
                {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
            </div>
            <form class="form" id="add_edit_profile_language" method="POST"
                action="{{ route('store.front.profile.language', [$user->id]) }}">{{ csrf_field() }}

                <div class="modal-body">

                    <div class="form-body">
                        <div class="formrow" id="div_language_id">
                            <select name="language_id" id="language_id" class="form-select select2 form-control"
                                placeholder="{{ __('Select Language') }}">
                                <option value="">{{ __('Select Language') }}</option>

                            </select>
                            <span class="help-block language_id-error"></span>
                        </div>
                        <div class="formrow" id="div_language_level_id">
                            <select name="language_level_id" id="language_level_id" class="form-control">
                                <option value="">{{ __('Select Language Level') }}</option>

                            </select><span class="help-block language_level_id-error"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-large btn-primary"
                        onClick="submitProfileLanguageForm();">{{ __('Add Language') }} <i
                            class="fa fa-arrow-circle-right" aria-hidden="true"></i></button>
                </div>
            </form>
        </div>
    </div>
</div>
