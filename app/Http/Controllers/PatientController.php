<?php


namespace App\Http\Controllers;


use App\Repositories\PatientRepository;
use Illuminate\Http\JsonResponse;

class PatientController extends Controller
{

    /**
     * @var \App\Repositories\PatientRepository
     */
    private $patientRepo;

    public function __construct(PatientRepository $patientRepo)
    {
        $this->patientRepo = $patientRepo;
    }

    /**
     * Получение профиля пациента
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProfile(): JsonResponse
    {
        /** @var \App\Models\Patient $patient */
        $patient = $this->patientRepo->getByUserId(Auth()->user()->id);

        return response()->json([
            'status' => true,
            'data' => $this->patientRepo->getProfileById($patient->id)
        ]);
    }

}
