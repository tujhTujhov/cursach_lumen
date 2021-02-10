<?php


namespace App\Http\Controllers;


use App\Repositories\VisitsRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VisitsController extends Controller
{

    /**
     * @var \App\Repositories\VisitsRepository
     */
    private VisitsRepository $repository;

    public function __construct(VisitsRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Создание визита
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'doctor_id' => 'int|required',
            'time_interval_id' => 'int|required',
            'date' => 'string|required',
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
                'is_created' => $this->repository->create($validator->validated())
            ]
        ]);
    }

}
