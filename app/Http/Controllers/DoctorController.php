<?php


namespace App\Http\Controllers;

use App\Repositories\DoctorRepository;
use App\Repositories\SchedulesRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DoctorController extends Controller
{

    /**
     * @var \App\Repositories\DoctorRepository
     */
    private $doctorRepository;

    /**
     * @var \App\Models\Doctor
     */
    private $doctor;

    /**
     * @var \App\Repositories\SchedulesRepository
     */
    private $schedulesRepo;

    public function __construct(DoctorRepository $doctorRepository, SchedulesRepository $schedulesRepository)
    {
        $this->doctorRepository = $doctorRepository;
        $this->schedulesRepo = $schedulesRepository;
        if (Auth()->user())
            $this->doctor = $this->doctorRepository->getByUserId(Auth()->user()->id);
    }


    /**
     * Получение докторов по специальности
     *
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBySpeciality($id): JsonResponse
    {
        return response()->json([
            'status' => true,
            'data' => $this->doctorRepository->getBySpeciality($id)
        ]);
    }

    /**
     * Получение доктора по его идентификатору
     *
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getById($id): JsonResponse
    {
        return response()->json([
            'status' => true,
            'data' => $this->doctorRepository->getById($id)
        ]);
    }

    /**
     * Получение профиля доктора
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProfile(): JsonResponse
    {
        return response()->json([
            'status' => true,
            'data' => $this->doctorRepository->getProfile($this->doctor->id)
        ]);
    }

    /**
     * Создание нового расписания
     *  Принимает массив дат и массив интервалов
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function createSchedule(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'dates' => 'array|required',
            'intervals' => 'array|required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ], 400);
        }

        $newDates = [];
        foreach ($request->get('dates') as $key => $date) {
            $newDates[$key]['date'] = $date;
        }

        $newIntervals = [];
        foreach ($request->get('intervals') as $key => $interval) {
            $newIntervals[$key]['nonwork_time_interval_id'] = $interval;
        }

        return response()->json([
            'status' => $this->schedulesRepo->create($this->doctor->id, $newDates, $newIntervals)
        ]);
    }

    /**
     * Получение расписания
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSchedule(): JsonResponse
    {
        return response()->json([
            'status' => true,
            'data' => $this->schedulesRepo->getDoctorScheduleById($this->doctor->id)
        ]);
    }
}
