<?php

namespace App\Http\Controllers;

use App\Forms\User\ProfileForm;
use App\Forms\User\SharedForm;
use App\Services\UserSharedService;
use Costa\LaravelPackage\Traits\Support\FormTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserProfileController extends Controller
{
    use FormTrait;

    public function profile(Request $request)
    {
        $data = $request->user();
        $data->password = null;

        $form = $this->transformInFormBuilder("POST", route('user.profile.update'), $data);

        $sharedForm = $this->transformInFormBuilder("POST", route('user.shared.update'), [], null, SharedForm::class);



        return view('user.profile', [
            'user' => $request->user(),
            'form' => $form,
            'sharedForm' => $sharedForm,
            'shareds' => $data->shareds,
        ]);
    }

    public function saveProfile(Request $request)
    {
        $data = $this->getDataForm();

        if (!Hash::check($data['password'], $request->user()->password)) {
            throw ValidationException::withMessages(['password' => __('Senha incorreta')]);
        }

        unset($data['password']);

        $request->user()->update($data);

        return redirect()->back()->with('success', __('Perfil atualizado com sucesso'));
    }

    public function saveShared(Request $request)
    {
        $form = $this->getDataForm(SharedForm::class);

        $this->getUserSharedService()->shared($request->user()->id, $form['email']);

        return redirect()->back()->with('success', __('Seus dados irão ser compartilhado com :email', ['email' => $form['email']]));
    }

    public function deleteShared($id){
        $this->getUserSharedService()->delete($this->getUserSharedService()->find($id)->id);
        return redirect()->back()->with('success', __('Foi deletado com sucesso esse compartilhamento'));
    }

    protected function form(): string
    {
        return ProfileForm::class;
    }

    /**
     * @return UserSharedService
     */
    protected function getUserSharedService()
    {
        return app(UserSharedService::class);
    }
}
