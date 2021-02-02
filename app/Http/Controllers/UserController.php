<?php


namespace App\Http\Controllers;


use App\Repositories\UserRepository;
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'login' => 'string|required',
            'password' => 'string|required',
            'email' => 'string|email|required',
            'role_id' => 'integer|required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' =>  false,
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

    public function login(Request $request)
    {

    }

}
