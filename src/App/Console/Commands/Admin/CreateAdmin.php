<?php

namespace App\Console\Commands\Admin;

use App\Constants\RoleConstant;
use App\Dtos\UserDto;
use App\Services\UserService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class CreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command creates an admin user.';

    /**
     * @var UserService $userService
     */
    private UserService $userService;

    /**
     * Constructor params.
     * @param UserService $userService
     */
    public function __construct(
        UserService $userService
    ) {
        parent::__construct();
        $this->userService = $userService;
    }


    /**
     * Execute the console command.
     */
    public function handle()
    {
        $staticPassword = config('prostarterkit.cmd_initial_pass');

        $correctPass = false;
        while (!$correctPass) {
            $initialPassword = $this->ask("For you to proceed, what's the app initial password?");
            if ($initialPassword !== $staticPassword) {
                $this->error('Incorrect password. Exiting.');
            } else {
                $correctPass = true;
            }
        }

        $firstname = '';
        while (empty($firstname)) {
            $firstname = $this->ask('Set admin\'s firstname');
            if (empty($firstname)) {
                $this->error('Firstname is required.');
            }
        }

        $lastname = '';
        while (empty($lastname)) {
            $lastname = $this->ask('Set admin\'s lastname');
            if (empty($lastname)) {
                $this->error('Lastname is required.');
            }
        }

        $email = '';
        while (empty($email)) {
            $email = $this->ask('Set admin\'s email');
            if (empty($email)) {
                $this->error('Email is required.');
            }
        }

        $password = '';
        while (empty($password)) {
            $password = $this->ask('Set admin\'s password');
            if (empty($password)) {
                $this->error('Password is required.');
            }
        }

        $validator = Validator::make([
            'email' => $email,
            'password' => $password
        ], [
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => [
                'required',
                'min:8',
                'max:16',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*#?&]/'
            ]
        ]);

        if ($validator->fails()) {
            $this->error('Validation failed: ' . $validator->errors()->first());
            return 1;
        }

        // Store to UserDto
        $userDto = new UserDto();
        $userDto->setFirstname($firstname);
        $userDto->setLastname($lastname);
        $userDto->setEmail($email);
        $userDto->setPassword($staticPassword);
        $userDto->setRoleId(RoleConstant::ADMIN);

        $serviceResponse = $this->userService->create($userDto);

        if ($serviceResponse->isError()) {
            $this->error($serviceResponse->getMessage());
            return 1;
        }

        $this->info($serviceResponse->getMessage());
        return 0;
    }
}
