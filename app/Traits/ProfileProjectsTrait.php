<?php



namespace App\Traits;

use DB;
use Auth;
use File;
use Input;
use App\User;
use Redirect;
use ImgUploader;
use Carbon\Carbon;
use App\Http\Requests;
use App\ProfileProject;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\ProfileProjectFormRequest;
use App\Http\Requests\ProfileProjectImageFormRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;



trait ProfileProjectsTrait

{
    public function showProfileProjects(Request $request, $user_id)
    {
        $user = User::find($user_id);

        $html = '';

        if (isset($user) && count($user->profileProjects)):

            $projectCounter = 0;

            foreach ($user->profileProjects as $project):

                if ($project->is_on_going == 1)

                    $date_end = 'Currently ongoing';

                else

                $date_end = \Carbon\Carbon::parse($project->date_end)->format('d M, Y');



                $image = ImgUploader::get_image("project_images/thumb/$project->image");

                $html .= '<!--Project Start-->

              <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12" id="project_' . $project->id . '">

                <div class="mt-card-item">

                  <div class="mt-card-avatar mt-overlay-1">' . $image . '</div>

                  <div class="mt-card-content">

                    <h3 class="mt-card-name">' . $project->name . '</h3>

                    <p class="mt-card-desc font-grey-mint">

                    ' . \Carbon\Carbon::parse($project->date_start)->format('d M, Y') . ' - ' . $date_end . '<br />

                    ' . \Illuminate\Support\Str::limit(strip_tags($project->description), 25, '...') . '</p>

                    <div class="mt-card-social">

                      <ul>

					  <li> <a href="javascript:void(0);" onclick="showProfileProjectEditModal(' . $project->id . ');"><i class="icon-pencil"></i></a> </li>

                        <li> <a href="javascript:void(0);" onclick="delete_profile_project(' . $project->id . ');"><i class="icon-close"></i></a> </li>

                      </ul>

                    </div>

                  </div>

                </div>

              </div>

              <!--Project End-->';

                $projectCounter++;

                if ($projectCounter == 4) {

                    $projectCounter = 0;

                    $html .= '<div style="clear:both;"></div>';

                }

            endforeach;

        endif;



        echo $html;

    }



    public function showFrontProfileProjects(Request $request, $user_id)
    {
        $user = User::find($user_id);
        $html = '';
        if (isset($user) && count($user->profileProjects)):
            $projectCounter = 0;
            foreach ($user->profileProjects as $project):
                if ($project->is_on_going == 1)
                    $date_end = 'Currently ongoing';
                else
                if(!empty($project->date_end)){
                    $date_end = \Carbon\Carbon::parse($project->date_end)->format('d M, Y');
					}else{
						$date_end ='';
					}

                    if(!empty($project->date_start)){
                        $date_start = \Carbon\Carbon::parse($project->date_start)->format('d M, Y');
                        }else{
                            $date_start ='';
                        }
                $image = ImgUploader::get_image("project_images/thumb/$project->image");

                $html .= '<!--Project Start-->
				  <div class="col-md-3" id="project_' . $project->id . '">
					<div class="thumbnail">
						' . $image . '
						<div class="caption">
						  <h3>' . $project->name . '</h3>
						  <p class="mb-3">' . $date_start . ' - ' . $date_end . '<br />
						  ' . \Illuminate\Support\Str::limit(strip_tags($project->description), 45, '...') . '</p>
						  <a class="text text-default" href="javascript:void(0);" onclick="showProfileProjectEditModal(' . $project->id . ');">' . __('Edit') . '</a>&nbsp;|&nbsp;<a href="javascript:;" class="delete-btn-projects text text-danger" data-uuid="'.$project->id.'">' . __('Delete') . '</a>
						  </ul>
						</div>
					</div>
				  </div>
				  <!--Project End-->';
                $projectCounter++;
                if ($projectCounter == 3) {
                    $projectCounter = 0;
                    $html .= '<div style="clear:both;"></div>';
                }
            endforeach;
        endif;
        echo $html;
    }

    public function FrontProfileProjectStore(Request $request, $user_id)
    {
        try {
            $data = $this->assignProjectValues(new ProfileProject(), $request, $user_id)->getAttributes();
        
            $profileProject = ProfileProject::updateOrCreate(
                ['id' => $request->id_project],
                $data
            );
            
            if ($request->hasFile('project_images')) {
                if (!empty($profileProject->project_images)) {
                    $oldImagePath = public_path('uploads/project_images/' . $profileProject->project_images);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }

                $uuid = (string) Str::uuid();
                $this->addProfileProjectImage($request, $profileProject, $uuid);
            }
        
            if (!$request->hasFile('project_images')) {
                unset($data['project_images']);
            }

            return response()->json([
                'success'   => true,
                'message'   => 'Profile Project added or updated successfully.',
                'alertType' => 'primary',
                'gravity'   => 'bottom',
                'position'  => 'right'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success'   => true,
                'message'   => 'Failed to add or update Profile Project.',
                'alertType' => 'danger',
                'gravity'   => 'bottom',
                'position'  => 'right'
            ]);
        }
    }

    private function addProfileProjectImage($request, $profileProject, $uuid)
    {
        $image      = $request->file('project_images');
        $extension  = $image->getClientOriginalExtension();
        $imageName  = empty($request->id_project) ? $uuid . '.' . $extension : $profileProject->image;
        $destinationPath = public_path('project_images/thumb/');
        $image->move($destinationPath, $imageName);
        $profileProject->image = $imageName;
        $profileProject->save();
    }

    public function getProjectDetails($id)
    {
        $project = ProfileProject::find($id);
        if (!$project) {
            return response()->json(['error' => 'Project not found'], 404);
        }
        
        return response()->json([
            'id'           => $project->id,
            'name'         => $project->name,
            'url'          => $project->url,
            'date_start'   => $project->date_start,
            'date_end'     => $project->date_end,
            'description'  => $project->description,
            'is_on_going'  => $project->is_on_going,
        ]);
    }

    public function storeFrontProfileProject(ProfileProjectFormRequest $request, $user_id)
    {
        $profileProject = new ProfileProject();
        $profileProject = $this->assignProjectValues($profileProject, $request, $user_id);
        $profileProject->save();
        $this->addProfileProjectImage($request, $profileProject);
        $returnHTML = view('user.forms.project.project_thanks')->render();
        return response()->json(array('success' => true, 'status' => 200, 'html' => $returnHTML), 200);
    }


    public function showApplicantProfileProjects(Request $request, $user_id)

    {

        $user = User::find($user_id);

        $html = '<ul class="row userPortfolio">';

        if (isset($user) && count($user->profileProjects)):

            $projectCounter = 0;

            foreach ($user->profileProjects as $project):

                if ($project->is_on_going == 1)

                    $date_end = __('Currently ongoing');

                else

                $date_end = \Carbon\Carbon::parse($project->date_end)->format('d M, Y');



                $image = ImgUploader::get_image("project_images/$project->image");

                $html .= '<li class="col-md-3">

                <div class="imgbox">' . $image . '

                  <div class="itemHover">

                    <div class="zoombox"><a href="' . $project->url . '" title="' . $project->name . '" target="_blank" class="item-zoom fancybox-effects-a"><i class="fa fa-search" aria-hidden="true"></i></a> </div>

                    <div class="infoItem">

                      <div class="itemtitle">

                        <h5>' . $project->name . '</h5>

                        <p>' . \Carbon\Carbon::parse($project->date_start)->format('d M, Y') . ' - ' . $date_end . '<br />
						  ' . \Illuminate\Support\Str::limit(strip_tags($project->description), 25, '...') . '</p>

                      </div>

                    </div>

                  </div>

                </div>

              </li>';

            endforeach;

        endif;



        echo $html . '</ul>';

    }



    public function uploadProjectTempImage(ProfileProjectImageFormRequest $request)

    {

        if ($request->hasFile('image')) {

            $image = $request->file('image');

            if (is_array($image)) {

                foreach ($image as $img) {

                    $fileName = ImgUploader::UploadImage('temp_project_images', $img);

                    $this->putImageInSession($fileName);

                }

            } else {

                $fileName = ImgUploader::UploadImage('temp_project_images', $image);

                $this->putImageInSession($fileName);

            }

            echo $fileName;

        } else {

            echo 'No Image';

        }

    }



    private function putImageInSession($fileName)

    {

        $session_id = session()->getId();

        $images = session()->get($session_id . 'temp.project_images', []);

        $images[] = $fileName;

        session()->put($session_id . 'temp.project_images', $images);

    }



    public function getProfileProjectForm(Request $request, $user_id)

    {

        $session_id = session()->getId();

        session()->forget($session_id . 'temp.project_images');



        $user = User::find($user_id);

        $returnHTML = view('admin.user.forms.project.project_modal')->with('user', $user)->render();

        return response()->json(array('success' => true, 'html' => $returnHTML));

    }



    public function getFrontProfileProjectForm(Request $request, $user_id)

    {

        $session_id = session()->getId();

        session()->forget($session_id . 'temp.project_images');



        $user = User::find($user_id);

        $returnHTML = view('user.forms.project.project_modal')->with('user', $user)->render();

        return response()->json(array('success' => true, 'html' => $returnHTML));

    }



    public function storeProfileProject(ProfileProjectFormRequest $request, $user_id)

    {



        $profileProject = new ProfileProject();

        $profileProject = $this->assignProjectValues($profileProject, $request, $user_id);

        $profileProject->save();



        $this->addProfileProjectImage($request, $profileProject);



        $returnHTML = view('admin.user.forms.project.project_thanks')->render();

        return response()->json(array('success' => true, 'status' => 200, 'html' => $returnHTML), 200);

    }

    private function assignProjectValues($profileProject, $request, $user_id)

    {

        $profileProject->user_id = $user_id;

        $profileProject->name = $request->input('name');

        $profileProject->url = $request->input('url');

        $profileProject->date_start = $request->input('date_start');

        $profileProject->date_end = $request->input('date_end');

        $profileProject->is_on_going = $request->input('is_on_going');

        $profileProject->description = $request->input('description');

        return $profileProject;

    }



    public function getProfileProjectEditForm(Request $request, $user_id)

    {

        $session_id = session()->getId();

        session()->forget($session_id . 'temp.project_images');



        $project_id = $request->input('project_id');

        $profileProject = ProfileProject::find($project_id);

        $user = User::find($user_id);

        $returnHTML = view('admin.user.forms.project.project_edit_modal')

                ->with('user', $user)

                ->with('profileProject', $profileProject)

                ->render();

        return response()->json(array('success' => true, 'html' => $returnHTML));

    }



    public function getFrontProfileProjectEditForm(Request $request, $user_id)

    {

        $session_id = session()->getId();

        session()->forget($session_id . 'temp.project_images');



        $project_id = $request->input('project_id');

        $profileProject = ProfileProject::find($project_id);

        $user = User::find($user_id);

        $returnHTML = view('user.forms.project.project_edit_modal')

                ->with('user', $user)

                ->with('profileProject', $profileProject)

                ->render();

        return response()->json(array('success' => true, 'html' => $returnHTML));

    }



    public function updateProfileProject(ProfileProjectFormRequest $request, $project_id, $user_id)

    {



        $profileProject = ProfileProject::find($project_id);

        $profileProject = $this->assignProjectValues($profileProject, $request, $user_id);

        $profileProject->update();



        $this->addProfileProjectImage($request, $profileProject);



        $returnHTML = view('admin.user.forms.project.project_edit_thanks')->render();

        return response()->json(array('success' => true, 'status' => 200, 'html' => $returnHTML), 200);

    }



    public function updateFrontProfileProject(ProfileProjectFormRequest $request, $project_id, $user_id)

    {



        $profileProject = ProfileProject::find($project_id);

        $profileProject = $this->assignProjectValues($profileProject, $request, $user_id);

        $profileProject->update();



        $this->addProfileProjectImage($request, $profileProject);



        $returnHTML = view('user.forms.project.project_edit_thanks')->render();

        return response()->json(array('success' => true, 'status' => 200, 'html' => $returnHTML), 200);

    }

    public function saveImage($fileName, $profileProject)
    {

        $this->deleteProfileProjectImage($profileProject->id);

        $profileProject->image = $fileName;

        $profileProject->update();

    }



    public function deleteAllProfileProjects($user_id)

    {

        $profileProjects = ProfileProject::where('user_id', '=', $user_id)->get();

        foreach ($profileProjects as $profileProject) {

            echo $this->removeProfileProject($profileProject->id);

        }

    }



    public function deleteProfileProject(Request $request)

    {

        $id = $request->input('id');

        echo $this->removeProfileProject($id);

    }

    public function deleteProfileProjectPersion($id)
    {
        try {
            $this->deleteProfileProjectImage($id);
            $profileProject = ProfileProject::findOrFail($id);
            $profileProject->delete();

            $success = true;
            $message = "Successfully to Delete this Portfolio Candidate.";
        } catch (\Exception $e) {
            $message = "Failed to Delete this Portfolio Candidate.";
            $success = false;
        }
            if($success == true) {
                return response()->json([
                    'success' => $success,
                    'message' => $message,
                ]);
            } elseif ($success == false) {
                return response()->json([
                    'success' => $success,
                    'message' => $message,
                ]);
            }
    }


    private function removeProfileProject($id)

    {

        try {

            $this->deleteProfileProjectImage($id);

            $profileProject = ProfileProject::findOrFail($id);

            $profileProject->delete();

            return 'ok';

        } catch (ModelNotFoundException $e) {

            return 'notok';

        }

    }



    private function deleteProfileProjectImage($id)

    {

        try {

            $profileProject = ProfileProject::findOrFail($id);

            $image = $profileProject->image;

            if (!empty($image)) {

                File::delete(ImgUploader::real_public_path() . 'project_images/thumb/' . $image);

                File::delete(ImgUploader::real_public_path() . 'project_images/mid/' . $image);

                File::delete(ImgUploader::real_public_path() . 'project_images/' . $image);

            }

            return 'ok';

        } catch (ModelNotFoundException $e) {

            return 'notok';

        }

    }



}

