<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Notifications\EmailChanged;
use App\Notifications\PasswordChanged;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Show the user's profile.
     */
    public function show(Request $request): View
    {
        return view('profile.show', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile.
     */
    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validated();

        $oldEmail = $user->email;
        $emailChanged = false;
        $passwordChanged = false;

        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
            $passwordChanged = true;
        } else {
            unset($data['password']);
        }

        if (isset($data['email']) && $data['email'] !== $oldEmail) {
            $emailChanged = true;
        }

        $uploadedFile = $request->file('image_path');

        if ($uploadedFile && $uploadedFile->isValid() && $uploadedFile->getSize() > 0) {
            if ($user->image_path && $user->image_path !== 'images/user_image_placeholder.webp') {
                Storage::disk('public')->delete($user->image_path);
            }

            $data['image_path'] = $uploadedFile->store('profile_images', 'public');
        } else {
            unset($data['image_path']);
        }

        $user->update($data);

        if ($emailChanged) {
            $user->notify(new EmailChanged($oldEmail, $user->email));
        }

        if ($passwordChanged) {
            $user->notify(new PasswordChanged);
        }

        return back()->with('success', 'Perfil atualizado com sucesso!');
    }
}
