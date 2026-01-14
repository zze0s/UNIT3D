<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\VerifyIrckeyRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class IrckeyAuthController extends Controller
{
    public function __invoke(VerifyIrckeyRequest $request): JsonResponse
    {
        if (!config('other.irckeys.is-enabled')) {
            return response()->json([
                'valid'  => false,
                'reason' => 'feature_disabled',
            ], 503);
        }

        $submittedKey = (string) $request->input('irckey');
        $username = (string) $request->input('username');

        $user = User::query()
            ->with(['group:id,slug,name'])
            ->where('username', $username)
            ->first();

        if ($user === null) {
            return response()->json([
                'valid'  => false,
                'reason' => 'invalid_credentials',
            ], 404);
        }

        if ($user->irckey === null || !hash_equals($user->irckey, $submittedKey)) {
            return response()->json([
                'valid'  => false,
                'reason' => 'invalid_key',
            ], 401);
        }

        return response()->json([
            'valid' => true,
            'user'  => [
                'id'       => $user->id,
                'username' => $user->username,
                'group'    => [
                    'id'   => $user->group->id,
                    'slug' => $user->group->slug,
                    'name' => $user->group->name,
                ],
                'disabled_at' => $user->disabled_at?->toIso8601String(),
                'deleted_at'  => $user->deleted_at?->toIso8601String(),
            ],
        ]);
    }
}
