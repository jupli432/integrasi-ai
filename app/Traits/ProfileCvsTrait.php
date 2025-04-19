<?php

namespace App\Traits;

use DB;
use Auth;
use File;
use Input;
use App\User;
use Redirect;
use ImgUploader;
use App\ProfileCv;
use Carbon\Carbon;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\ProfileCvFormRequest;
use App\Http\Requests\ProfileCvFileFormRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

trait ProfileCvsTrait
{

    public function showProfileCvs(Request $request, $user_id)
    {
        $user = User::find($user_id);
        $html = '<div class="col-mid-12"><table class="table table-bordered table-striped"><thead><tr>
        <th><strong>CV Title</strong></th>
        <th><strong>Default CV</strong></th>
        <th><strong>Added Date</strong></th>
        <th><strong>Action</strong></th>
        </tr></thead>';
        if (isset($user) && count($user->profileCvs)):
            $cvCounter = 0;
            foreach ($user->profileCvs as $cv):
                $default = 'Not Default';
                if ($cv->is_default == 1)
                    $default = 'Default';
                    $addedDate = $cv->created_at->format('Y-m-d H:i:s'); 

                $html .= '
                <tr id="cv_' . $cv->id . '">
									<td>' . ImgUploader::get_doc("cvs/$cv->cv_file", $cv->title, $cv->title) . '</td>
									<td><span class="text text-success">' . $default . '</span></td>
                                    <td><span class="text text-success">' . $addedDate . '</span></td>
									<td><a href="javascript:;" onclick="ModalShowProfileCvEdit('.$cv->id.');" class="text text-warning">' . __('Edit') . '</a>&nbsp;|&nbsp;<a href="javascript:;" class="delete-btn text text-danger" data-uuid="'.$cv->id.'">Delete</a>'.'</td>
								</tr>';
            endforeach;
        endif;

        echo $html . '</table></div>';
    }

    public function showCvProfile()
    {
        $user = User::with('profileCvs')->findOrFail(auth()->user()->id);
            return response()->json($user->profileCvs);
    }

    public function uploadCvFile($request)
    {
        $fileName = '';
        if ($request->hasFile('cv_file')) {
            $cv_file = $request->file('cv_file');
            $fileName = ImgUploader::UploadDoc('cvs', $cv_file, $request->input('title'));
        }
        return $fileName;
    }

    public function getFrontProfileCvForm(Request $request, $user_id)
    {
        $user = User::find($user_id);
        $returnHTML = view('user.forms.cv.cv_modal')->with('user', $user)->render();
        return response()->json(array('success' => true, 'html' => $returnHTML));
    }

    public function getProfileCvForm(Request $request, $user_id)
    {
        $user = User::find($user_id);
        $returnHTML = view('admin.user.forms.cv.cv_modal')->with('user', $user)->render();
        return response()->json(array('success' => true, 'html' => $returnHTML));
    }

    public function storeProfileCv(Request $request, $user_id)
    {
        // $returnHTML = view('admin.user.forms.cv.cv_thanks')->render();
        // return response()->json(array('success' => true, 'status' => 200, 'html' => $returnHTML), 200);
        try {
            $profileCv = new ProfileCv();
            $profileCv = $this->assignValues($profileCv, $request, $user_id);
            $profileCv->save();
            
            $notification = [ 'message'     => 'CV added successfully.',
                              'alert-type'  => 'primary',
                              'gravity'     => 'bottom',
                              'position'    => 'right'];
                return redirect()->route('my.profile')->with($notification);
        } catch (\Throwable $th) {
            $notification = [ 'message'     => 'Failed to Add CV.',
                              'alert-type'  => 'danger',
                              'gravity'     => 'bottom',
                              'position'    => 'right'];
                return redirect()->route('my.profile')->with($notification);
        }
    }

    public function ProfileCvstore(Request $request, $user_id)
    {
        $request->validate([
            'title'   => 'required|string|max:255',
            'cv_file' => 'nullable|mimes:pdf,doc,docx|max:2048',
        ]);
        
        $profileCvModel = new ProfileCv();
        $profileCvData = $this->assignValues($profileCvModel, $request, $user_id)->getAttributes();
        
        if (!$request->hasFile('cv_file')) {
            unset($profileCvData['cv_file']);
        }
        
        $profileCv = ProfileCv::updateOrCreate(
            ['id' => $request->id_cv],
            $profileCvData
        );
        
        return response()->json([
            'success'   => true,
            'message'   => 'CV added or updated successfully.',
            'alertType' => 'primary',
            'gravity'   => 'bottom',
            'position'  => 'right'
        ]);        
    }

    public function EditGetFrontProfileCv($cv_id)
    {
        $profileCv = ProfileCv::find($cv_id);
        if (!$profileCv) {
            return response()->json([
                'success' => false,
                'message' => 'CV not found.'
            ]);
        }
    
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $profileCv->id,
                'title' => $profileCv->title,
                'cv_file' => $profileCv->cv_file ?? '',
                'is_default' => $profileCv->is_default
            ]
        ]);
    }
    
    public function storeFrontProfileCv(ProfileCvFormRequest $request, $user_id)
    {

        $profileCv = new ProfileCv();
        $profileCv = $this->assignValues($profileCv, $request, $user_id);
        $profileCv->save();

        $returnHTML = view('user.forms.cv.cv_thanks')->render();
        return response()->json(array('success' => true, 'status' => 200, 'html' => $returnHTML), 200);
    }

    private function assignValues($profileCv, $request, $user_id)
    {
        $profileCv->user_id = $user_id;
        $profileCv->title = $request->input('title');
        $profileCv->is_default = $request->input('is_default');

        /*         * ************************************ */
        if ((int) $request->input('is_default') == 1) {
            $this->updateDefaultCv($profileCv->id);
        }
        /*         * ************************************ */

        if ($request->hasFile('cv_file') && $profileCv->id > 0) {
            $this->deleteCv($profileCv->id);
        }
        $profileCv->cv_file = $this->uploadCvFile($request);

        return $profileCv;
    }

    public function getProfileCvEditForm(Request $request, $user_id)
    {
        $cv_id = $request->input('cv_id');
        $profileCv = ProfileCv::find($cv_id);
        $user = User::find($user_id);
        $returnHTML = view('admin.user.forms.cv.cv_edit_modal')
                ->with('user', $user)
                ->with('profileCv', $profileCv)
                ->render();
        return response()->json(array('success' => true, 'html' => $returnHTML));
    }

    public function getFrontProfileCvEditForm(Request $request, $user_id)
    {
        $cv_id = $request->input('cv_id');
        $profileCv = ProfileCv::find($cv_id);
        $user = User::find($user_id);
        $returnHTML = view('user.forms.cv.cv_edit_modal')
                ->with('user', $user)
                ->with('profileCv', $profileCv)
                ->render();
        return response()->json(array('success' => true, 'html' => $returnHTML));
    }

    public function updateProfileCv(ProfileCvFormRequest $request, $cv_id, $user_id)
    {

        $profileCv = ProfileCv::find($cv_id);
        $profileCv = $this->assignValues($profileCv, $request, $user_id);
        $profileCv->update();

        $returnHTML = view('admin.user.forms.cv.cv_edit_thanks')->render();
        return response()->json(array('success' => true, 'status' => 200, 'html' => $returnHTML), 200);
    }

    public function updateFrontProfileCv(ProfileCvFormRequest $request, $cv_id, $user_id)
    {

        $profileCv = ProfileCv::find($cv_id);
        $profileCv = $this->assignValues($profileCv, $request, $user_id);
        $profileCv->update();

        $returnHTML = view('user.forms.cv.cv_edit_thanks')->render();
        return response()->json(array('success' => true, 'status' => 200, 'html' => $returnHTML), 200);
    }

    public function makeDefaultCv(Request $request)
    {
        $id = $request->input('id');
        try {
            $profileCv = ProfileCv::findOrFail($id);
            $profileCv->is_default = 1;
            $profileCv->update();
            $this->updateDefaultCv($id);
            echo 'ok';
        } catch (ModelNotFoundException $e) {
            echo 'notok';
        }
    }

    private function updateDefaultCv($cv_id)
    {
        ProfileCv::where('is_default', '=', 1)->where('id', '<>', $cv_id)->update(['is_default' => 0]);
    }

    public function deleteAllProfileCvs($user_id)
    {
        $profileCvs = ProfileCv::where('user_id', '=', $user_id)->get();
        foreach ($profileCvs as $profileCv) {
            echo $this->removeProfileCv($profileCv->id);
        }
    }

    public function deleteProfileCv(Request $request)
    {
        $id = $request->input('id');
        echo $this->removeProfileCv($id);
    }

    private function removeProfileCv($id)
    {
        try {
            $this->deleteCv($id);
            $profileCv = ProfileCv::findOrFail($id);
            $profileCv->delete();
            return 'ok';
        } catch (ModelNotFoundException $e) {
            return 'notok';
        }
    }

    public function deleteProfileCvPersion($id)
    {
        try {
            $this->deleteCv($id);
            $profileCv = ProfileCv::findOrFail($id);
            $profileCv->delete();
            $success = true;
            $message = "Successfully to Delete this Curriculum Vitae.";
        } catch (\Exception $e) {
            $message = "Failed to Delete this Curriculum Vitae.";
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

    private function deleteCv($id)
    {
        try {
            $profileCv = ProfileCv::findOrFail($id);
            $file = $profileCv->cv_file;
            if (!empty($file)) {
                File::delete(ImgUploader::real_public_path() . 'cvs/' . $file);
            }
            return 'ok';
        } catch (ModelNotFoundException $e) {
            return 'notok';
        }
    }

}
