<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Response\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use App\Services\AuthService;
use Illuminate\Http\Request;
use App\Models\Participant;
use App\Models\TweetLike;
use App\Models\Tweet;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $authService = new AuthService();
        $validator = $authService->validateLogin($request);

        if ($validator->fails()) {
            return app(ApiResponse::class)->validationError($validator->errors()->toArray());
        }

        try {
            $credentials = $request->only(['email', 'password']);

            if (Auth::attempt($credentials)) {
                $user = Auth::user();

                $data['name'] = data_get($user, 'name');
                $data['email'] = data_get($user, 'email');
                $data['access_token'] = $user->createToken('AuthToken')->accessToken;

                Log::info('Login Success');

                return app(ApiResponse::class)
                            ->success($data, 'Login success.')
                            ->cookie('jwt_token', $data['access_token']);
            }


            return app(ApiResponse::class)->error('Your email or password does not match our record!');
        } catch (\Exception $e) {
            Log::error($e);

            return app(ApiResponse::class)->error('Something went wrong.' . $e->getMessage());
        }
    }

    public function register(Request $request)
    {
        $authService = new AuthService();

        $validator = $authService->validateRegister($request);

        if ($validator->fails()) {
            return app(ApiResponse::class)->validationError($validator->errors()->toArray());
        }

        try {
            $user = $authService->doRegister($request);


            Log::info('Registration Success');

            return app(ApiResponse::class)->success($user, 'You have successfully registration.');
        } catch (\Exception $e) {
            Log::error($e);

            return app(ApiResponse::class)->error('Registration Failed.' . $e->getMessage());
        }

    }


    public function profile()
    {
        try {
            $user = auth()->user();

            return app(ApiResponse::class)->success($user, 'Success');
        } catch (\Exception $e) {
            Log::error($e);

            return app(ApiResponse::class)->error('Something went wrong, Please try again.');
        }
    }


    public function getTotalInfo()
    {
        try {
            $totalAdmin = User::count();
            $totalUser = Participant::count();
            $totalTweet = Tweet::count();
            $totalLike = TweetLike::count();

            $data = [
                'totalAdmin' => $totalAdmin,
                'totalUser' => $totalUser,
                'totalTweet' => $totalTweet,
                'totalLike' => $totalLike,
            ];

            return app(ApiResponse::class)->success($data, 'Success');
        } catch (\Exception $e) {
            Log::error($e);

            return app(ApiResponse::class)->error('Something went wrong, Please try again.');
        }
    }

    public function getParticipant()
    {
        try {
            $data = Participant::withCount(['tweets', 'likes', 'followers', 'following'])
                            ->latest()
                            ->get();

            if (! blank($data)) {
                $data->map(function ($item) {
                   $item->join_date = Carbon::parse(data_get($item, 'created_at'))->format('d M, Y H:i:s A');

                   return $item;
                });
            }

            return app(ApiResponse::class)->success($data, 'Success');
        } catch (\Exception $e) {
            Log::error($e);

            return app(ApiResponse::class)->error('Something went wrong, Please try again.');
        }
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            $user = $request->user();

            $delete = $user->token()->revoke();

            return app(ApiResponse::class)
                    ->success($delete, 'Successfully logout')
                    ->cookie('jwt_token', null, -1, '/');
        } catch (\Exception $e) {
            Log::error($e);

            return app(ApiResponse::class)->error('Something went wrong, Please try again.');
        }
    }
}
