@if (null !== $messages)
    <?php foreach($messages as $msg){?>
    <li
        class="
      @if ($msg->type == 'message') friend-message
        @else my-message @endif
           clearfix tab{{ $seeker->id }}">
        <figure class="profile-picture">
            @if ($msg->type == 'message')
                {{ $seeker->printUserImage(100, 100) }}
            @else
                {{ $company->printCompanyImage() }}
            @endif
        </figure>
        <div class="message"> {{ $msg->message }}
            <div class="time">
                <i class="fa fa-clock-o"></i>
                @if (\Carbon\Carbon::parse($msg->updated_at)->isToday())
                    {{ \Carbon\Carbon::parse($msg->updated_at)->format('H:i') }}
                @else
                    {{ \Carbon\Carbon::parse($msg->updated_at)->format('Y-m-d H:i') }}
                @endif
            </div>
        </div>
    </li>
    <?php } ?>
@endif
