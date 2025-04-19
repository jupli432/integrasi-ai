@extends('layouts.app')
@section('content')
    <!-- Header start -->
    @include('includes.header')
    <!-- Header end -->
    <!-- Inner Page Title start -->
    @include('includes.inner_page_title', ['page_title' => __('Company Messages')])
    <div class="listpgWraper messageWrap">
        <div class="container-fluid">
            <div class="row">
                @include('includes.company_dashboard_menu')
                <div class="col-lg-9 ">
                    <h3>{{ __('Company Messages') }}</h3>
                    <div class="myads message-body">
                        <div class="row">
                            <div class="col-lg-4 col-md-4">
                                <div class="message-inbox">
                                    <div class="message-header">
                                    </div>
                                    <div class="list-wrap w-100">
                                        <ul class="message-history w-100">
                                            @if (null !== $seekers)
                                                @foreach ($seekers as $seeker)
                                                    <li class="message-grid w-100" id="adactive{{ $seeker->id }}">
                                                        <a href="javascript:;" data-gift="{{ $seeker->id }}"
                                                            id="company_id_{{ $seeker->id }}" class="d-flex gap-3 w-100"
                                                            onclick="show_messages({{ $seeker->id }})">

                                                            <img src="{{ url('/') . '/user_images/' . $seeker->image }}"
                                                                alt="{{ $seeker->name }}" class="flex-shrink-0 image"
                                                                onerror="this.onerror=null;this.src='{{ url('/') }}/admin_assets/no-image.png';">

                                                            <div class="row flex-grow-1 overflow-auto user-name">

                                                                <div class=" author">
                                                                    <span>{{ $seeker->name }}</span>
                                                                    @if ($seeker->isOnline())
                                                                        <i class="fa-solid fa-circle text-success ms-1"></i>
                                                                    @endif
                                                                </div>
                                                                <div class=" last-message-container ">
                                                                    <div class=" last-message-preview">
                                                                        {{ $seeker?->lastUserMessage?->message }}</div>
                                                                    <small
                                                                        class=" last-message-time text-end text-muted">{{ $seeker?->lastUserMessage?->created_at?->diffForHumans() }}</small>
                                                                </div>
                                                            </div>
                                                            {{-- <div class="trailing">
                                                            <div class="count-messages">
                                                                {{ $seeker->countMessages(Auth::guard('company')->user()->id) }}
                                                            </div>
                                                        </div> --}}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8 col-md-8  message-content">
                                <div class="message-details h-100">
                                    <div id="append_messages" class=" h-100">
                                        <div
                                            class="h-100 d-flex flex-column align-items-center justify-content-center text-center  gap-3">
                                            <i class="fa-regular fa-comment-dots" style="font-size: 48px"></i>
                                            <span>Message will be displayed here <br> please select one message</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </section>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script type="text/javascript">
        function show_messages(id) {
            $.ajax({
                type: 'get',
                url: "{{ route('append-message') }}",
                data: {
                    '_token': $('input[name=_token]').val(),
                    'seeker_id': id,
                },
                success: function(res) {
                    $('#append_messages').html('');
                    $('#append_messages').html(res);
                    $(".messages").scrollTop(1000000000000);
                    $('.messages').off('scroll');
                    $('.message-grid').removeClass('active');
                    $("#adactive" + id).addClass('active');


                    $.ajax({
                        type: "GET",
                        url: "{{ route('company-change-message-status') }}",
                        data: {
                            'sender_id': id,
                        },
                    });
                }
            });

        }
    </script>
@endpush
