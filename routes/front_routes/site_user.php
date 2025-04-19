<?php

use App\Http\Controllers\UserController;

/* * ******** UserController ************ */
Route::get('my-profile', 'UserController@myProfile')->name('my.profile');
Route::put('my-profile', 'UserController@updateMyProfile')->name('my.profile');
Route::get('view-public-profile/{id}', 'UserController@viewPublicProfile')->name('view.public.profile');
Route::post('update-front-profile-summary/{id}', 'UserController@updateProfileSummary')->name('update.front.profile.summary');
Route::post('update-immediate-available-status', 'UserController@updateImmediateAvailableStatus')->name('update.immediate.available.status');
Route::get('add-to-favourite-company/{company_slug}', 'UserController@addToFavouriteCompany')->name('add.to.favourite.company');
Route::get('remove-from-favourite-company/{company_slug}', 'UserController@removeFromFavouriteCompany')->name('remove.from.favourite.company');
Route::get('my-followings', 'UserController@myFollowings')->name('my.followings');
/* Route::get('my-messages', 'UserController@myMessages')->name('my.messages'); */
Route::get('my-messages', 'Job\SeekerSendController@all_messages')->name('my.messages');
Route::get('seeker-append-messages', 'Job\SeekerSendController@append_messages')->name('seeker-append-messages');
Route::get('seeker-append-only-messages', 'Job\SeekerSendController@appendonly_messages')->name('seeker-append-only-message');
Route::post('seeker-submit-messages', 'Job\SeekerSendController@submitnew_message')->name('seeker.submit-message');
Route::get('resume/{id}', 'UserController@ResumeFetch')->name('resume');
Route::get('applicant-message-detail/{id}', 'UserController@applicantMessageDetail')->name('applicant.message.detail');
/* * *********************************** */
Route::post('show-front-profile-cvs/{id}', 'UserController@showProfileCvs')->name('show.front.profile.cvs');
Route::post('get-front-profile-cv-form/{id}', 'UserController@getFrontProfileCvForm')->name('get.front.profile.cv.form');

Route::post('store-front-profile-cv/{id}', 'UserController@storeProfileCv')->name('store.front.profile.cv');
Route::post('store-front-profile-cv/{id}', 'UserController@ProfileCvstore')->name('store.front.profile.cv.new');

Route::post('get-front-profile-cv-edit-form/{user_id}', 'UserController@getFrontProfileCvEditForm')->name('get.front.profile.cv.edit.form');
Route::post('update-front-profile-cv/{id}/{user_id}', 'UserController@updateFrontProfileCv')->name('update.front.profile.cv');

Route::delete('delete-front-profile-cv', 'UserController@deleteProfileCv')->name('delete.front.profile.cv');

Route::get('edit-get-front-profile-cv/{user_id}', 'UserController@EditGetFrontProfileCv')->name('get.front.profile.cv.edit');
Route::delete('delete-front-profile-cv-persion/{id}', 'UserController@deleteProfileCvPersion')->name('delete.front.profile.cv.persion');
/* * *********************************** */

/* * ******** Start Routing User ************ */
Route::get('user/cvs', [UserController::class,'showCvProfile'])->name('user.cvs');
/* * ******** End Routing User ************ */

Route::post('show-front-profile-projects/{id}', 'UserController@showFrontProfileProjects')->name('show.front.profile.projects');
Route::post('show-applicant-profile-projects/{id}', 'UserController@showApplicantProfileProjects')->name('show.applicant.profile.projects');
Route::post('upload-front-project-temp-image', 'UserController@uploadProjectTempImage')->name('upload.front.project.temp.image');
Route::post('get-front-profile-project-form/{id}', 'UserController@getFrontProfileProjectForm')->name('get.front.profile.project.form');

Route::post('front-profile-project-store/{id}', 'UserController@FrontProfileProjectStore')->name('store.front.profile.project.new');

Route::post('get-front-profile-project-edit-form/{user_id}', 'UserController@getFrontProfileProjectEditForm')->name('get.front.profile.project.edit.form');

Route::get('edit-front-profile-project/{id}', 'UserController@getProjectDetails')->name('edit.front.profile.project');
Route::put('update-front-profile-project/{id}/{user_id}', 'UserController@updateFrontProfileProject')->name('update.front.profile.project');

Route::delete('delete-front-profile-project', 'UserController@deleteProfileProject')->name('delete.front.profile.project');
Route::delete('delete-front-profile-project-persion/{id}', 'UserController@deleteProfileProjectPersion')->name('delete.front.profile.project.persion');

/* * ***************** Experience ****************** */
Route::post('show-front-profile-experience/{id}', 'UserController@showFrontProfileExperience')->name('show.front.profile.experience');
Route::get('states-experience', 'UserController@getStatesByCountry')->name('front.profile.getStates.ByCountry');
Route::get('cities-experience', 'UserController@getCitiesByState')->name('front.profile.getCities.ByState');

Route::post('store-profile-experience/{id}', 'UserController@storeProfileExperience')->name('front.store.profile.experience');

Route::post('show-applicant-profile-experience/{id}', 'UserController@showApplicantProfileExperience')->name('show.applicant.profile.experience');
Route::post('get-front-profile-experience-form/{id}', 'UserController@getFrontProfileExperienceForm')->name('get.front.profile.experience.form');
Route::post('store-front-profile-experience/{id}', 'UserController@storeFrontProfileExperience')->name('store.front.profile.experience');
Route::post('get-front-profile-experience-edit-form/{id}', 'UserController@getFrontProfileExperienceEditForm')->name('get.front.profile.experience.edit.form');
Route::put('update-front-profile-experience/{profile_experience_id}/{user_id}', 'UserController@updateFrontProfileExperience')->name('update.front.profile.experience');

Route::delete('delete-front-profile-experience', 'UserController@deleteProfileExperience')->name('delete.front.profile.experience');
Route::delete('delete-front-profile-experience-persion/{id}', 'UserController@deleteProfileExperiencePersion')->name('delete.front.profile.experience.persion');

/* * *********************************** */
Route::post('show-front-profile-education/{id}', 'UserController@showFrontProfileEducation')->name('show.front.profile.education');
Route::post('show-applicant-profile-education/{id}', 'UserController@showApplicantProfileEducation')->name('show.applicant.profile.education');
Route::post('get-front-profile-education-form/{id}', 'UserController@getFrontProfileEducationForm')->name('get.front.profile.education.form');
Route::post('store-front-profile-education/{id}', 'UserController@storeFrontProfileEducation')->name('store.front.profile.education');
Route::post('get-front-profile-education-edit-form/{id}', 'UserController@getFrontProfileEducationEditForm')->name('get.front.profile.education.edit.form');
Route::put('update-front-profile-education/{education_id}/{user_id}', 'UserController@updateFrontProfileEducation')->name('update.front.profile.education');

Route::delete('delete-front-profile-education', 'UserController@deleteProfileEducation')->name('delete.front.profile.education');
Route::delete('delete-front-profile-education-persion/{id}', 'UserController@deleteProfileEducationPersion')->name('delete.front.profile.education.persion');
/* * *********************************** */
Route::post('show-front-profile-skills/{id}', 'UserController@showProfileSkills')->name('show.front.profile.skills');
Route::post('show-applicant-profile-skills/{id}', 'UserController@showApplicantProfileSkills')->name('show.applicant.profile.skills');
Route::post('get-front-profile-skill-form/{id}', 'UserController@getFrontProfileSkillForm')->name('get.front.profile.skill.form');
Route::post('store-front-profile-skill/{id}', 'UserController@storeFrontProfileSkill')->name('store.front.profile.skill');
Route::post('get-front-profile-skill-edit-form/{id}', 'UserController@getFrontProfileSkillEditForm')->name('get.front.profile.skill.edit.form');
Route::put('update-front-profile-skill/{skill_id}/{user_id}', 'UserController@updateFrontProfileSkill')->name('update.front.profile.skill');

Route::delete('delete-front-profile-skill', 'UserController@deleteProfileSkill')->name('delete.front.profile.skill');
Route::delete('delete-front-profile-skill-persion/{id}', 'UserController@deleteProfileSkillPersion')->name('delete.front.profile.skill.persion');
/* * *********************************** */
Route::post('show-front-profile-languages/{id}', 'UserController@showProfileLanguages')->name('show.front.profile.languages');
Route::post('show-applicant-profile-languages/{id}', 'UserController@showApplicantProfileLanguages')->name('show.applicant.profile.languages');
Route::post('get-front-profile-language-form/{id}', 'UserController@getFrontProfileLanguageForm')->name('get.front.profile.language.form');
Route::post('store-front-profile-language/{id}', 'UserController@storeFrontProfileLanguage')->name('store.front.profile.language');
Route::post('get-front-profile-language-edit-form/{id}', 'UserController@getFrontProfileLanguageEditForm')->name('get.front.profile.language.edit.form');
Route::put('update-front-profile-language/{language_id}/{user_id}', 'UserController@updateFrontProfileLanguage')->name('update.front.profile.language');

Route::delete('delete-front.profile-language', 'UserController@deleteProfileLanguage')->name('delete.front.profile.language');
Route::delete('delete-front.profile-language-persion/{id}', 'UserController@deleteProfileLanguagePersion')->name('delete.front.profile.language.persion');
/*************************************/


Route::get('my-alerts', 'UserController@myAlerts')->name('my-alerts');
Route::get('delete-alert/{id}', 'UserController@delete_alert')->name('delete-alert');

use App\Http\Controllers\CvController;

Route::get('aianalyze', 'UserController@aianalyze')->name('aianalyze');
Route::post('/analyze-cv', [CvController::class, 'analyzeCv'])->name('cv.analyze');