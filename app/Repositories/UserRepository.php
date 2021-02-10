<?php


namespace App\Repositories;


use App\Models\Roles;
use App\Models\User;
use Illuminate\Support\Str;

class UserRepository
{

    /**
     * @var \App\Repositories\DoctorRepository
     */
    public $doctorRepo;

    /**
     * @var \App\Repositories\PatientRepository
     */
    public $patientRepo;

    public function __construct()
    {
        $this->doctorRepo = new DoctorRepository();
        $this->patientRepo = new PatientRepository();
    }

    /**
     * Создание нового юзера
     *
     * @param array $fields
     *          [
     *          'user' => [
     *          'login', 'password', 'email', 'role_id'
     *          ],
     *          'profile' => [
     *          'name', 'surname', 'middlename', 'speciality_id'
     *          ]
     *          ]
     *
     * @return string
     */
    public function create(array $fields): string
    {
        $fields['user']['api_token'] = Str::random();
        $user = User::create($fields['user']);
        $fields['profile']['user_id'] = $user->id;
        if ($fields['user']['role_id'] == Roles::DOCTOR_ROLE_ID) {
            $this->doctorRepo->create($fields['profile']);
        } elseif ($fields['user']['role_id'] == Roles::PATIENT_ROLE_ID) {
            $this->patientRepo->create($fields['profile']);
        }
        return $user->api_token;
    }

    /**
     * Авторизация пользователя
     *
     * @param array $validated
     *          [ 'login', 'password' ]
     *
     * @return User | false
     */
    public function auth(array $validated)
    {
        $user = User::with([])
            ->where('login', $validated['login'])
            ->orWhere('email', $validated['login'])
            ->where('password', $validated['password'])
            ->first();

        if ($user) {
            return $user;
        }

        return false;
    }

}
