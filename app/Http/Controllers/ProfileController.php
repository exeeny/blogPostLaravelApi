<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Resources\UserResource;

class ProfileController extends Controller
{
    public function update(ProfileUpdateRequest $request)
    {
        $request->user()->update($request->validated());
        return new UserResource($request->user());
    }
}
