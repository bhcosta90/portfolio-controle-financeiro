<?php

namespace App\Http\Controllers;

use App\Forms\UserForm;
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
        return view('user.profile', ['user' => $request->user(), 'form' => $form]);
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

    protected function form(): string
    {
        return UserForm::class;
    }
}
