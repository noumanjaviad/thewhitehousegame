<?php

namespace App\Http\Controllers\Api;

use App\Models\UserState;
use Illuminate\Http\Request;
use App\Services\UserStateService;
use App\Http\Controllers\Controller;

class UserStateController extends Controller
{

    public function get_user_state()
    {
        try {

            $user_state = UserStateService::getAllStates();

            return $this->sendSuccessResponse([
                'message' => 'Sucess',
                'user_state' => $user_state,
            ]);
        } catch (Exception $e) {
            return $this->sendErrorResponse('internal server error', 500);
        }
    }


    
    public function update(Request $request, $id)
    {
        $state = UserState::findOrFail($id);
        $request->validate([
            // 'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            // 'map_url' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'state_flag_url' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        // if ($request->hasFile('image')) {
        //     $imagePath = $request->file('image')->store('state_images', 'public'); // Adjust storage path as needed
        //     $state->image_url = 'storage/' . $imagePath;
        // }
        // if ($request->hasFile('map_url')) {
        //     $imagePath = $request->file('map_url')->store('state_images', 'public'); // Adjust storage path as needed
        //     $state->map_url = 'storage/' . $imagePath;
        // }

        // if ($request->hasFile('state_image_url')) {
        //     $imagePath = $request->file('state_image_url')->store('state_images', 'public'); // Adjust storage path as needed
        //     $state->state_image_url = 'storage/' . $imagePath;
        // }
        if ($request->hasFile('state_flag_url')) {
            $imagePath = $request->file('state_flag_url')->store('state_flag_images_noumi', 'public'); // Adjust storage path as needed
            $state->image_url = 'storage/' . $imagePath;
        }

        // $state->name = $request->input('name'); // Update name field directly

        $state->save();

        return response()->json(['message' => 'State updated successfully', 'data' => $state]);
    }

}
