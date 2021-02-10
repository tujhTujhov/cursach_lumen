<?php


namespace App\Http\Controllers;


use App\Repositories\SchedulesRepository;
use App\Repositories\TimeIntervalRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SchedulesController extends Controller
{

    /**
     * @var TimeIntervalRepository
     */
    private TimeIntervalRepository $intervalRepo;
    /**
     * @var \App\Repositories\SchedulesRepository
     */
    private SchedulesRepository $schedulesRepo;

    public function __construct(TimeIntervalRepository $intervalRepo, SchedulesRepository $schedulesRepo)
    {
        $this->intervalRepo = $intervalRepo;
        $this->schedulesRepo = $schedulesRepo;
    }

    /**
     * Получение временных интервалов
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTimeIntervals(): JsonResponse
    {
        return response()->json([
            'status' => true,
            'data' => $this->intervalRepo->getAll()
        ]);
    }

    /**
     * Получение доктора по ID
     *
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function getByDoctorId(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'doctor_id' => 'int|required',
            'dates' => 'array|required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ], 400);
        }

        return response()->json([
            'status' => true,
            'data' => $this->schedulesRepo->getScheduleByDoctorId(
                $validator->validated()['doctor_id'],
                $validator->validated()['dates']
            )
        ]);
    }

    /**
     * Получение доступных дат для записи
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function getAvailableDates(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'doctor_id' => 'int|required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ], 400);
        }

        return response()->json([
            'status' => true,
            'data' => $this->schedulesRepo->getAvailableDatesDoctorId(
                $validator->validated()['doctor_id']
            )
        ]);
    }
}
