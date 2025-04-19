<div class="modal fade" id="add_cv_modal_new" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="add_cv_modal_newLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">
                    {{__('Submit CV')}}
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="form" id="add_edit_profile_cv" method="POST" action="{{ route('store.front.profile.cv.new', [$user->id]) }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-body">
                        <input type="hidden" name="id_cv" id="id_cv">
                        <div class="formrow" id="div_title">
                            <input class="form-control" id="title" placeholder="{{__('CV Title')}}" name="title" type="text" value="{{(isset($profileCv)? $profileCv->title:'')}}">
                            <span class="help-block title-error"></span>
                        </div>
                        @if(isset($profileCv))
                        <div class="formrow">
                            <i class="fas fa-file"></i>
                            {{ImgUploader::print_doc("cvs/$profileCv->cv_file", $profileCv->title, $profileCv->title)}}
                        </div>
                        @endif
                        <div class="formrow" id="div_cv_file">
                            <input name="cv_file" id="cv_file" type="file" class="form-control form-control-sm" />
                            <span class="help-block cv_file-error"></span>
                        </div>

                        <div class="formrow" id="div_is_default">
                            <label for="is_default" class="bold">
                                {{__('Is default?')}}
                            </label>
                            <div class="radio-list">
                                @php
                                    $val_1_checked = '';
                                    $val_2_checked = 'checked="checked"';

                                    if (isset($profileCv) && $profileCv->is_default == 1) {
                                        $val_1_checked = 'checked="checked"';
                                        $val_2_checked = '';
                                    }
                                @endphp
                                <label class="radio-inline"><input class="form-check-input" id="default" name="is_default" type="radio" value="1" {{$val_1_checked}}>
                                    {{__('Yes')}}
                                </label>
                                <label class="radio-inline"><input class="form-check-input" id="not_default" name="is_default" type="radio" value="0" {{$val_2_checked}}>
                                    {{__('No')}}
                                </label>
                            </div>
                            <span class="help-block is_default-error"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">
                        {{__('Submit CV')}}
                        <i class="fas fa-arrow-circle-right" aria-hidden="true"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
