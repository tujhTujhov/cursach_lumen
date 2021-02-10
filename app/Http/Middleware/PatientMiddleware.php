<?php


namespace App\Http\Middleware;


use App\Models\User;
use Closure;

class PatientMiddleware
{

    /**
     * Миддлвер для проверки пользователя на соответсвие Пациенту.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        /** @var \App\Models\User $user */
        $user = auth()->user();
        if ($user->role_id != User::PATIENT_ROLE) {
            return response('Forbidden', 403);
        }
        return $next($request);
    }

}
