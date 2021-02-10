<?php


namespace App\Http\Controllers;


use App\Repositories\SpecialitiesRepository;
use Illuminate\Http\JsonResponse;

class AdditionalController extends Controller
{

    /**
     * @var \App\Repositories\SpecialitiesRepository
     */
    private $specialitiesRepo;

    public function __construct(SpecialitiesRepository $specialitiesRepo)
    {
        $this->specialitiesRepo = $specialitiesRepo;
    }

    /**
     * Метод получения всех специальностей
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSpecialities(): JsonResponse
    {
        return response()->json([
            'status' => true,
            'data'  => $this->specialitiesRepo->getAll()
        ]);
    }
}
