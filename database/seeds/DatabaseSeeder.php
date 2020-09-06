<?php

use Illuminate\Database\Seeder;
use App\Models\Ignug\State;
use App\Models\Attendance\Catalogue as AttendanceCatalogue;
use App\Models\Ignug\Catalogue as IgnugCatalogue;
use App\Models\Ignug\Location as IgnugLocation;
use App\Role;
use App\User;
use App\Models\Ignug\Teacher;
use App\Models\JobBoard\Professional;
use App\Models\JobBoard\AcademicFormation;
use App\Models\JobBoard\Course;
use App\Models\JobBoard\ProfessionalReference;
use App\Models\JobBoard\ProfessionalExperience;
use App\Models\JobBoard\Language;
use App\Models\JobBoard\Ability;
use App\Models\JobBoard\Category;
use App\Models\JobBoard\Company;
use App\Models\JobBoard\Offer;
use App\Models\JobBoard\Catalogue as JobBoardCatalogue;
use App\Models\JobBoard\Location as JobBoardLocation;
use Faker\Generator as Faker;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $faker = new Faker();
        // States
        factory(State::class)->create([
            'code' => '1',
            'name' => 'ACTIVE',
            'state' => 1,
        ]);
        factory(State::class)->create([
            'code' => '2',
            'name' => 'INACTIVE',
            'state' => 1,
        ]);
        factory(State::class)->create([
            'code' => '3',
            'name' => 'DELETED',
            'state' => 1,
        ]);

        // Catalogues
        // Workday Principal
        factory(AttendanceCatalogue::class)->create([
            'code' => 'work',
            'name' => 'Jornada',
            'type' => 'workdays.principal',
            'icon' => 'pi pi-calendar',
        ]);

        // Workday Secundary
        factory(AttendanceCatalogue::class)->create([
            'code' => 'lunch',
            'name' => 'Almuerzo',
            'type' => 'workdays.secondary',
            'icon' => 'pi pi-briefcase',
        ]);

        // Task Processes
        factory(AttendanceCatalogue::class, 1)->create(
            [
                'code' => 'academic',
                'type' => 'tasks.process',
                'icon' => 'pi pi-calendar',
            ]
        )->each(function ($catalogue) {
            for ($i = 0; $i < 20; $i++) {
                $catalogue->children()->save(factory(AttendanceCatalogue::class)->make(
                    [
                        'code' => $i,
                        'type' => 'tasks.activity',
                        'icon' => 'pi pi-calendar',
                    ]
                ));
            }
        });

        factory(AttendanceCatalogue::class, 1)->create(
            [
                'code' => 'administrative',
                'type' => 'tasks.process',
                'icon' => 'pi pi-calendar',
            ]
        )->each(function ($catalogue) {
            for ($i = 0; $i < 20; $i++) {
                $catalogue->children()->save(factory(AttendanceCatalogue::class)->make(
                    [
                        'code' => $i,
                        'type' => 'tasks.activity',
                        'icon' => 'pi pi-calendar',
                    ]
                ));
            }
        });

        factory(AttendanceCatalogue::class, 1)->create(
            [
                'code' => 'entailment',
                'type' => 'tasks.process',
                'icon' => 'pi pi-calendar',
            ]
        )->each(function ($catalogue) {
            for ($i = 0; $i < 20; $i++) {
                $catalogue->children()->save(factory(AttendanceCatalogue::class)->make(
                    [
                        'code' => $i,
                        'type' => 'tasks.activity',
                        'icon' => 'pi pi-calendar',
                    ]
                ));
            }
        });

        factory(AttendanceCatalogue::class, 1)->create(
            [
                'code' => 'investigation',
                'type' => 'tasks.process',
                'icon' => 'pi pi-calendar',
            ]
        )->each(function ($catalogue) {
            for ($i = 0; $i < 20; $i++) {
                $catalogue->children()->save(factory(AttendanceCatalogue::class)->make(
                    [
                        'code' => $i,
                        'type' => 'tasks.activity',
                        'icon' => 'pi pi-calendar',
                    ]
                ));
            }
        });

        // Ethnic origin
        for ($i = 0; $i < 20; $i++) {
            factory(IgnugCatalogue::class)->create([
                'code' => $i,
                'type' => 'users.ethnic_origin',
            ]);
        }

        // Sex
        factory(IgnugCatalogue::class)->create([
            'code' => '1',
            'name' => 'HOMBRE',
            'type' => 'users.sex',
        ]);
        factory(IgnugCatalogue::class)->create([
            'code' => '2',
            'name' => 'MUJER',
            'type' => 'users.sex',
        ]);
        // Gender
        factory(IgnugCatalogue::class)->create([
            'code' => '1',
            'name' => 'MASCULINO',
            'type' => 'users.gender',
        ]);
        factory(IgnugCatalogue::class)->create([
            'code' => '2',
            'name' => 'FEMENINO',
            'type' => 'users.gender',
        ]);

        // Indetification Type
        factory(IgnugCatalogue::class)->create([
            'code' => '1',
            'name' => 'CEDULA',
            'type' => 'users.identification_type',
            'state_id' => 1,
        ]);
        factory(IgnugCatalogue::class)->create([
            'code' => '2',
            'name' => 'PASAPORTE',
            'type' => 'users.identification_type',
            'state_id' => 1,
        ]);

        // Blood Type
        factory(IgnugCatalogue::class)->create([
            'code' => '1',
            'name' => 'A+',
            'type' => 'blood_type',
            'state_id' => 1,
        ]);
        factory(IgnugCatalogue::class)->create([
            'code' => '2',
            'name' => 'A-',
            'type' => 'blood_type',
            'state_id' => 1,
        ]);
        factory(IgnugCatalogue::class)->create([
            'code' => '3',
            'name' => 'B+',
            'type' => 'blood_type',
            'state_id' => 1,
        ]);
        factory(IgnugCatalogue::class)->create([
            'code' => '4',
            'name' => 'B-',
            'type' => 'blood_type',
            'state_id' => 1,
        ]);
        factory(IgnugCatalogue::class)->create([
            'code' => '5',
            'name' => 'AB+',
            'type' => 'blood_type',
            'state_id' => 1,
        ]);
        factory(IgnugCatalogue::class)->create([
            'code' => '6',
            'name' => 'AB-',
            'type' => 'blood_type',
            'state_id' => 1,
        ]);
        factory(IgnugCatalogue::class)->create([
            'code' => '7',
            'name' => 'O+',
            'type' => 'blood_type',
            'state_id' => 1,
        ]);
        factory(IgnugCatalogue::class)->create([
            'code' => '8',
            'name' => 'O-',
            'type' => 'blood_type',
            'state_id' => 1,
        ]);

        // career modality
        factory(IgnugCatalogue::class)->create([
            'code' => '1',
            'name' => 'PRESENCIAL',
            'type' => 'career_modality',
            'state_id' => 1,
        ]);
        factory(IgnugCatalogue::class)->create([
            'code' => '2',
            'name' => 'SEMI-PRESENCIAL',
            'type' => 'career_modality',
            'state_id' => 1,
        ]);
        factory(IgnugCatalogue::class)->create([
            'code' => '3',
            'name' => 'DISTANCIA',
            'type' => 'career_modality',
            'state_id' => 1,
        ]);
        factory(IgnugCatalogue::class)->create([
            'code' => '4',
            'name' => 'DUAL',
            'type' => 'career_modality',
            'state_id' => 1,
        ]);

        // career type
        factory(IgnugCatalogue::class)->create([
            'code' => '1',
            'name' => 'TECNICATURA',
            'type' => 'career_type',
            'state_id' => 1,
        ]);
        factory(IgnugCatalogue::class)->create([
            'code' => '2',
            'name' => 'TECNOLOGIA',
            'type' => 'career_type',
            'state_id' => 1,
        ]);

        // location
        for ($i = 0; $i < 2; $i++) {
            factory(IgnugLocation::class)->create([
                'type' => 'users.country',
            ])->each(function ($location) {
                for ($j = 0; $j < 2; $j++) {
                    $location->children()->save(factory(IgnugLocation::class)->make(
                        [
                            'type' => 'users.state',
                        ]
                    ));
                }
            });
        }
        factory(Category::class, 100)->create();
        factory(JobBoardCatalogue::class, 100)->create();
        factory(JobBoardLocation::class, 100)->create();
        // roles system
        factory(IgnugCatalogue::class)->create([
            'code' => 'attendance',
            'name' => 'Attendance',
            'type' => 'roles.system',
        ])->each(function ($system) {
            factory(Role::class)->create([
                'code' => '1',
                'name' => 'DOCENTE',
                'system_id' => $system->id,
            ])->each(function ($role) {
                $user = factory(User::class)->create([
                    'user_name' => '1234567891',
                ]);
                $user->teacher()->save(factory(Teacher::class)->make());
                $user->roles()->attach($role->id);
            });
            factory(Role::class)->create([
                'code' => '2',
                'name' => 'ADMINISTRATIVO',
                'system_id' => $system->id,
            ])->each(function ($role) {
                $user = factory(User::class)->create([
                    'user_name' => '1234567892',
                ]);
                $user->teacher()->save(factory(Teacher::class)->make());
                $user->roles()->attach($role->id);
            });
        });

        factory(IgnugCatalogue::class)->create([
            'code' => 'attendance',
            'name' => 'Attendance',
            'type' => 'roles.system',
        ])->each(function ($system) {
            factory(Role::class)->create([
                'code' => '1',
                'name' => 'DOCENTE',
                'system_id' => $system->id,
            ])->each(function ($role) {
                $user = factory(User::class)->create([
                    'user_name' => '1234567891',
                ]);
                $user->teacher()->save(factory(Teacher::class)->make());
                $user->roles()->attach($role->id);
            });
            factory(Role::class)->create([
                'code' => '2',
                'name' => 'ADMINISTRATIVO',
                'system_id' => $system->id,
            ])->each(function ($role) {
                $user = factory(User::class)->create([
                    'user_name' => '1234567892',
                ]);
                $user->teacher()->save(factory(Teacher::class)->make());
                $user->roles()->attach($role->id);
            });
        });

        factory(IgnugCatalogue::class)->create([
            'code' => 'jobboard',
            'name' => 'JobBoard',
            'type' => 'roles.system',
        ])->each(function ($system) {
            factory(Role::class)->create([
                'code' => '1',
                'name' => 'PROFESSIONAL',
                'system_id' => $system->id,
            ])->each(function ($role) {
                $user = factory(User::class)->create([
                    'user_name' => '1234567892',
                ]);
                $professional = $user->professional()->save(factory(Professional::class)->make());
                $professional->academicFormations()->save(factory(AcademicFormation::class)->make());
                $professional->abilities()->save(factory(Ability::class)->make());
                $professional->languages()->save(factory(Language::class)->make());
                $professional->courses()->save(factory(Course::class)->make());
                $professional->professionalExperiences()->save(factory(ProfessionalExperience::class)->make());
                $professional->professionalReferences()->save(factory(ProfessionalReference::class)->make());
                $user->roles()->attach($role->id);
            });
            factory(Role::class)->create([
                'code' => '2',
                'name' => 'COMPANY',
                'system_id' => $system->id,
            ])->each(function ($role) {
                $user = factory(User::class)->create([
                    'user_name' => '1234567894',
                ]);
                $company = $user->company()->save(factory(Company::class)->make());
                $offer = $company->offers()->save(factory(Offer::class)->make());
                $offer->categories()->attach(random_int(1, 100));
                $offer->professionals()->attach(random_int(1, 100));
                $company->professionals()->attach(random_int(1, 100));
                $user->roles()->attach($role->id);
            });
        });

        factory(IgnugCatalogue::class)->create([
            'code' => 'web',
            'name' => 'Web',
            'type' => 'roles.system',
        ])->each(function ($system) {
            factory(Role::class)->create([
                'code' => '1',
                'name' => 'ADMINISTRATOR',
                'system_id' => $system->id,
            ])->each(function ($role) {
                $user = factory(User::class)->create([
                    'user_name' => '1234567895',
                ]);
                $user->roles()->attach($role->id);
            });
        });


        /*
            drop schema if exists authentication cascade;
            drop schema if exists attendance cascade;
            drop schema if exists ignug cascade;
            drop schema if exists job_board cascade;
            drop schema if exists web cascade;

            create schema authentication;
            create schema attendance;
            create schema ignug;
            create schema job_board;
            create schema web;
        */
    }
}
