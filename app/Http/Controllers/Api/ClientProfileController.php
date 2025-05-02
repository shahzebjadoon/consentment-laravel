<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClientProfile;


class ClientProfileController extends Controller
{
    public function index()
    {
        return response()->json(ClientProfile::all());
    }

    // Store a new profile
    public function store(Request $request)
    {
        // return "profile created";
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'signup_as' => 'required|in:client,myself',
            'industry_name' => 'nullable|string',
            'number_of_people' => 'nullable|string',
            'number_of_domain' => 'nullable|string',
            'work_type' => 'nullable|string',
            'visitors_pm' => 'nullable|string',
            'looking_for' => 'nullable|string',
            'first_hear' => 'nullable|string',
        ]);

        $profile = ClientProfile::create($validated);

        return response()->json($profile, 201);
    }

    // Show a specific profile
    public function show($id)
    {
        $profile = ClientProfile::findOrFail($id);
        return response()->json($profile);
    }

    // Update an existing profile
    public function update(Request $request, $id)
    {
        $profile = ClientProfile::findOrFail($id);

        $validated = $request->validate([
            'signup_as' => 'in:client,myself',
            'industry_name' => 'nullable|string',
            'number_of_people' => 'nullable|string',
            'number_of_domain' => 'nullable|string',
            'work_type' => 'nullable|string',
            'visitors_pm' => 'nullable|string',
            'looking_for' => 'nullable|string',
            'first_hear' => 'nullable|string',
        ]);

        $profile->update($validated);

        return response()->json($profile);
    }

    // Delete a profile
    public function destroy($id)
    {
        $profile = ClientProfile::findOrFail($id);
        $profile->delete();

        return response()->json(['message' => 'Profile deleted']);
    }
}