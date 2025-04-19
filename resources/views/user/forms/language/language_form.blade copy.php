<div class="modal" id="add_language_modal" tabindex="-1" aria-labelledby="addlangModalLabel" aria-hidden="true"
    role="dialog">
    <div class="modal-body">
        <div class="form-body">
            <div class="formrow" id="div_language_id">
                <?php
                $language_id = isset($profileLanguage) ? $profileLanguage->language_id : null;
                ?>
                {!! Form::select('language_id', ['' => __('Select language')] + ($languages ?? []), $language_id, [
                    'class' => 'form-control select2',
                    'id' => 'language_id',
                ]) !!} <span class="help-block language_id-error"></span>
            </div>
            <div class="formrow" id="div_language_level_id">
                <?php
                $language_level_id = isset($profileLanguage) ? $profileLanguage->language_level_id : null;
                ?>
                {!! Form::select(
                    'language_level_id',
                    ['' => __('Select Language Level')] + ($languageLevels ?? []),
                    $language_level_id,
                    ['class' => 'form-control', 'id' => 'language_level_id'],
                ) !!} <span class="help-block language_level_id-error"></span> </div>
        </div>
    </div>
</div>
@push('scripts')
    <script type="text/javascript">
        $('#language_ids').select2({
            placeholder: "{{ __('Select language') }}",
            allowClear: true
        });
    </script>
@endpush
