<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserInfoRequest;
use App\Models\User;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    use Dispatchable;

    // public function update_user_info(UpdateUserInfoRequest $request)
    // {
    //     // dd($request->all());
    //     try {
    //         $user = Auth::user();
    //         $user_id = $user->id;

    //         $validatedData = $request->validated();

    //         $user = User::findOrFail($user_id);
    //         // dd($user);

    //         if ($request->is_votted_2020 == 'yes') {
    //             $user->voted_candidates()->attach($validatedData['voter_candidate_id'], [
    //                 'source' => $validatedData['source'],
    //                 // 'votting_year' => $validatedData['votting_year'],
    //             ]);
    //         }

    //         $authUser = auth()->user();

    //         if ($request->has('user_votter_party')) {
    //             $user->user_party_vottings()->create([
    //                 'user_id' => $authUser->id,
    //                 'votter_party_id' => $request->user_votter_party,
    //             ]);
    //         }

    //         $user->update([
    //             'language_id' => $request->language_id,
    //             'user_age_id' => $request->user_age_id,
    //             'user_ethnicity_id' => $request->user_ethnicity_id,
    //             'user_country_birth_id' => $request->user_country_birth_id,
    //             'user_employement_id' => $request->user_employement_id,
    //             'user_gender_id' => $request->user_gender_id,
    //             'education_id' => $request->education_id,
    //             'is_veteran' => $request->is_veteran,
    //             'user_state_id' => $request->user_state_id,
    //             'is_votted_2020' => $request->is_votted_2020,
    //             'is_subscription_newsletter' => $request->is_subscription_newsletter,
    //         ]);

    //         // If user subscribes to the newsletter, send welcome email
    //         if ($request->is_subscription_newsletter == 'yes') {
    //             $this->sendWelcomeEmail($user);

    //             // dispatch(new SendWelcomeEmailJob($user));
    //         }

    //         $response = [
    //             'message' => 'User information updated successfully',
    //             'user' => $user,
    //         ];
    //         return $this->sendSuccessResponse($response);
    //     } catch (Exception $e) {
    //         return $this->sendErrorResponse('Internal server error', 500);
    //     }catch (ModelNotFoundException $e) {
    //         return $this->sendErrorResponse('User not found', 404);
    //     }catch (QueryException $e) {
    //         return $this->sendErrorResponse('Database error', 500);
    //     }
    // }

    public function update_user_info(UpdateUserInfoRequest $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return $this->sendErrorResponse('User not authenticated', 401);
            }

            $user_id = $user->id;
            $validatedData = $request->validated();

            $user = User::findOrFail($user_id);

            if ($request->is_votted_2020 == 'yes') {
                // Detach existing voted candidates to update with new data
                $user->voted_candidates()->detach();

                // Attach new voted candidates with the provided data
                $user->voted_candidates()->attach($validatedData['voter_candidate_id'], [
                    'source' => $validatedData['source'],
                    // 'votting_year' => $validatedData['votting_year'],
                ]);
            }

            if ($request->has('user_votter_party')) {
                // Remove old user party vottings and add new one
                $user->user_party_vottings()->delete();
                $user->user_party_vottings()->create([
                    'user_id' => $user_id,
                    'votter_party_id' => $request->user_votter_party,
                ]);
            }

            $user->update([
                'language_id' => $request->language_id,
                'user_age_id' => $request->user_age_id,
                'user_ethnicity_id' => $request->user_ethnicity_id,
                'user_country_birth_id' => $request->user_country_birth_id,
                'user_employement_id' => $request->user_employement_id,
                'user_gender_id' => $request->user_gender_id,
                'education_id' => $request->education_id,
                'is_veteran' => $request->is_veteran,
                'user_state_id' => $request->user_state_id,
                'is_votted_2020' => $request->is_votted_2020,
                'is_subscription_newsletter' => $request->is_subscription_newsletter,
            ]);

            if ($request->is_subscription_newsletter == 'yes') {
                $this->sendWelcomeEmail($user);
            }

            $response = [
                'message' => 'User information updated successfully',
                'user' => $user,
            ];
            return $this->sendSuccessResponse($response);
        } catch (ModelNotFoundException $e) {
            return $this->sendErrorResponse('User not found', 404);
        } catch (QueryException $e) {
            return $this->sendErrorResponse('Database error', 500);
        } catch (Exception $e) {
            return $this->sendErrorResponse('Internal server error', 500);
        }
    }

    private function sendWelcomeEmail($user)
    {
        Mail::to($user->email)->send(new \App\Mail\WelcomeNewsletter());
    }

    // public function getUserInfo(Request $request, $id)
    // {
    //     // dd($id);
    //     try {
    //         $user = User::with('language','age','state','ethnicity','user_country_birth','employement','gender','education')->findOrFail($id);
    //         // return $user;
    //         // dd($user->age->range);

    //             $response = [
    //                 'message'=>'success',
    //                 'id' => $user->id,
    //                 'name' => $user->name,
    //                 'email' => $user->email,
    //                 'dob' => $user->dob,
    //                 'language' => $user->language->name,
    //                 'age' => $user->age->range,
    //                 'state' => $user->state->name,
    //                 'ethnicity' => $user->ethnicity->name,
    //                 'user_country_birth' => $user->user_country_birth->name,
    //                 'employment' => $user->employement->employement_status,
    //                 'gender' => $user->gender->name,
    //                 'education' => $user->education->name,
    //             ];
    //         return $this->sendSuccessResponse($response);
    //     } catch (ModelNotFoundException $e) {
    //         return $this->sendErrorResponse('User not found', 404);
    //     } catch (\Exception $e) {
    //         return $this->sendErrorResponse('Internal server error', 500);
    //     }
    // }

    public function getUserInfo(Request $request)
    {
        try {
            $user = User::with(
                'language',
                'age',
                'state',
                'ethnicity',
                'user_country_birth',
                'employement',
                'gender',
                'education',
                'users_voted_candidates',
                'user_party_vottings'
            )->where('id', auth()->id())->first();
            $response = [
                'message' => 'success',
                'data' => $this->formatUserResponse($user),
            ];
            return $this->sendSuccessResponse($response);
        } catch (ModelNotFoundException $e) {
            return $this->sendErrorResponse('User not found', 404);
        } catch (Exception $e) {
            return $this->sendErrorResponse('Internal server error', 500);
        }
    }

    /**
     * Format user data for response.
     *
     * @param User $user
     * @return array
     */

    private function formatUserResponse(User $user): array
    {
        $voter_candidate_id = $user->voted_candidates->map(function ($candidate) {
            return $candidate->id;
        })->first() ?? 'No Candidate Provided';

        $voter_party_id = $user->user_party_vottings->map(function ($votting) {
            return $votting->votter_party_id;
        })->first() ?? 'No Party Provided';

        $source = $user->users_voted_candidates->map(function ($candidate) {
            return $candidate->pivot->source;
        })->first() ?? 'No source Provided';

        return [
            'message' => 'success',
            'id' => $user->id,
            'name' => $user->name ?? 'No Name Provided',
            'email' => $user->email ?? 'No Email Provided',
            'dob' => $user->dob ?? 'No Dob Provided',
            'language' => $user->language->name ?? 'No Language Provided',
            'age' => $user->age->id ?? 'No Age Provided',
            'state' => $user->state->name ?? 'No State Provided',
            'ethnicity' => $user->ethnicity->name ?? 'No Race Provided',
            'user_country_birth' => $user->user_country_birth->name ?? 'No Country of Birth Provided',
            'employment' => $user->employement->employement_status ?? 'No Employment Status Provided',
            'gender' => $user->gender->id ?? 'No Gender Provided',
            'education' => $user->education->name ?? 'No Education Provided',
            'is_veteran' => $user->is_veteran ?? '',
            'is_votted_2020' => $user->is_votted_2020 ?? '',
            'is_subscription_newsletter' => $user->is_subscription_newsletter ?? '',
            'voter_candidate_id' => $voter_candidate_id,
            'voter_party_id' => $voter_party_id,
            'source' => $source,
        ];
    }

    public function changeUserProfileInfo(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->update([
                "name" => $request->name,
                "email" => $request->email,
                "dob" => $request->dob,
            ]);
            $response = [
                "message" => 'user profile info update successfully',
                'userinfo' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'dob' => $user->dob,
                ],
            ];
            return $this->sendSuccessResponse($response);
        } catch (ModelNotFoundException $e) {
            return $this->sendErrorResponse('User not found', 404);
        } catch (\Exception $e) {
            return $this->sendErrorResponse('Internal server error', 500);
        }

    }

    public function getUserLanguage(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            $user_language = $user->language;
            $response = [
                'message' => 'success',
                'user_language' => $user_language,
            ];
            return $this->sendSuccessResponse($response);
        } catch (ModelNotFoundException $e) {
            return $this->sendErrorResponse('User not found', 404);
        } catch (\Exception $e) {
            return $this->sendErrorResponse('Internal server error', 500);
        }
    }

    public function uploadProfileImage(Request $request)
    {
        try {
            // $user = User::where('id',auth()->id())->first();
            $user = auth()->user();
            $request->validate([
                'profile_image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if ($request->hasFile('profile_image')) {
                $imagePath = $request->file('profile_image')->store('profile_images', 'public');
                $user->profile_image = 'storage/' . $imagePath;
                $user->save();
            }

            $response = [
                'message' => 'User profile image uploaded successfully',
                'user' => $user->only(['id', 'name', 'email', 'profile_image']), // Returning only necessary fields
            ];
            return $this->sendSuccessResponse($response);
        } catch (ModelNotFoundException $e) {
            return $this->sendErrorResponse('User not found', 404);
        } catch (\Exception $e) {
            // Catching all exceptions
            return $this->sendErrorResponse('Internal server error', 500);
        }
    }

}
