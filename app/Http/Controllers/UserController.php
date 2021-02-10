<?php


namespace App\Http\Controllers;


use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Class UserController
 *
 * Класс отвечает за работу с пользователями системы
 *
 * @package App\Http\Controllers
 */
class UserController extends Controller
{

    /**
     * @var UserRepository
     */
    private $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Метод отвечает за регистрацию пользователя
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'user' => "required|array|min:4",
            'user.login' => 'string|required',
            'user.password' => 'string|required',
            'user.email' => 'string|email|required',
            'user.role_id' => 'integer|required',
            'profile' => "required|array|min:3",
            'profile.name' => 'string|required',
            'profile.surname' => 'string|required',
            'profile.middlename' => 'string|nullable',
            'profile.speciality_id' => 'integer|nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ], 400);
        }

        return response()->json([
            'status' => true,
            'data' => [
                'token' => $this->repository->create($validator->validated())
            ]
        ], 201);
    }

    /**
     * Авторизация клиента
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'login' => 'string|required',
            'password' => 'string|required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ], 400);
        }

        if ($user = $this->repository->auth($validator->validated())) {
            return response()->json([
                'status' => true,
                'data' => [
                    'token' => $user->api_token,
                    'role_id' => $user->role_id
                ]
            ], 201);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized'
            ], 401);
        }
    }

}
