<?php

use Gilanggustina\ModuleMedicalTreatment\{
    Models,
    Contracts,
    Commands as ModuleMedicalTreatmentCommands
};
use Hanafalah\ModuleMedicService\Models\MedicService;

return [
    'contracts' => [
        'medical_treatment'          => Contracts\MedicalTreatment::class,
        'medical_service_treatment'  => Contracts\MedicalServiceTreatment::class,
        'module_medical_treatment'   => Contracts\ModuleMedicalTreatment::class
    ],
    'commands' => [
        ModuleMedicalTreatmentCommands\InstallMakeCommand::class
    ],
    'database' => [
        'models' => [
            'MedicalTreatment'           => Models\MedicalTreatment\MedicalTreatment::class,
            'MedicalServiceTreatment'    => Models\MedicalTreatment\MedicalServiceTreatment::class,
            'MedicService'               => MedicService::class,
        ]
    ]
];
