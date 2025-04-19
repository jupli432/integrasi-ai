@extends('layouts.app')
@section('content')
<!-- Header start -->
@include('includes.header')
<!-- Header end -->
<!-- Inner Page Title start -->
@include('includes.inner_top_search')
<!-- Inner Page Title end -->
<div class="listpgWraper">
    <div class="container">
        @include('flash::message')
        <div class="row-candidate-dashboard"> @include('includes.user_dashboard_menu')
			<div class="profile-parent-candidate-dashboard m-2">
				<div class="profile-candidate-dashboard">
					<img class="cover-icon-candidate-dashboard" src="{{ asset("user_images/-1614188466-122.jpg") }}">
					<img class="photo-profile-icon-candidate-dashboard" 
     						src="{{ auth()->user() && auth()->user()->image ? asset('user_images/' . auth()->user()->image) : asset('/images/candidate_dashboard/user.png') }}">

					<div class="information-candidate-dashboard">
						<div class="frame-parent-candidate-dashboard">
							<div class="name-profile-parent-candidate-dashboard">
								<div class="name-profile-candidate-dashboard">{{ auth()->user()->name }}</div>
								<div class="location-parent-candidate-dashboard">
									<img class="location-icon-candidate-dashboard" src="{{ asset("/images/candidate_dashboard/location.png") }}">
									<div class="location-name-candidate-dashboard">{{ auth()->user()->getLocation() }}</div>
								</div>
							</div>
							<div class="button-edit-profile-candidate-dashboard">
								<a href="{{ route('my.profile') }}" class="button-user-edit-profile-candidate-dashboard">Edit Profile</a>
							</div>
						</div>
						<div class="frame-group-candidate-dashboard">
							<div class="mobilephone-parent-candidate-dashboard">
								<img class="mobilephone-icon-candidate-dashboard" src="{{ asset("/images/candidate_dashboard/call.png") }}">
								<div class="mobilephone-candidate-dashboard">{{ auth()->user()->phone }}</div>
							</div>
							<div class="call-parent-candidate-dashboard">
								<img class="email-icon-candidate-dashboard" alt="" src="{{ asset("/images/candidate_dashboard/message.png") }}">
								<div class="email-candidate-dashboard">{{ auth()->user()->email }}</div>
							</div>
						</div>
					</div>
				</div>
				<div class="main-content-candidate-dashboard">
					<div class="card-information-candidate-dashboard">
						<div class="new-candidates-candidate-dashboard">
							{{ auth()->user()->num_profile_views }}
							<div class="open-jobs-candidate-dashboard">Profile Views</div>
						</div>
						<div class="schedule-candidate-dashboard">
							<div class="parent-candidate-dashboard">
								{{ auth()->user()->countFollowings() }}
								<div class="follower-candidate-dashboard">Following</div>
							</div>
						</div>
						<div class="messages-candidate-dashboard">
							<div class="group-candidate-dashboard">
								{{ auth()->user()->countUserMessages() }}
								<div class="follower-candidate-dashboard">Messages received</div>
							</div>
						</div>
					</div>
					<div class="custom-card-candidate-dashboard">
						<div class="job-recommendation-wrapper-candidate-dashboard">
							<b class="job-recommendation-candidate-dashboard">Job Recommendation</b>
						</div>
						<div class="row-candidate-dashboard">
							<div class="list-candidate-dashboard">
							    @if(null!==($matchingJobs))
									@foreach($matchingJobs as $match)
										<div class="job-list-candidate-dashboard">
											<img class="company-logo-icon-candidate-dashboard" src="{{ asset("/images/candidate_dashboard/dropbox.png") }}">
											<div class="job-title-candidate-dashboard">
												<a href="{{route('job.detail', [$match->slug])}}" class="social-media-assistant-candidate-dashboard">
													{{$match->title}}
												</a>
												<div class="company-name-location-job-candidate-dashboard">
													<div class="dropbox-candidate-dashboard">{{$match->getCompany()->name}}</div>
												</div>
												<div class="part-time-candidate-dashboard">
													<div class="label-part-time-candidate-dashboard">
														<div class="part-time-candidate-dashboard">Part-Time</div>
													</div>
												</div>
												<div class="month-ago-candidate-dashboard">1 month ago</div>
											</div>
										</div>
									@endforeach
                                @endif
							</div>
						</div>
					</div>
				</div>
			</div>
    	</div>
	</div>
</div>
@include('includes.footer')
@endsection
@push('scripts')
@include('includes.immediate_available_btn')
@endpush
