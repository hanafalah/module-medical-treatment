<?php

namespace Gilanggustina\ModuleMedicalTreatment\Supports;

use Hanafalah\LaravelSupport\Supports\PackageManagement;

class BaseModuleMedicalTreatment extends PackageManagement
{
    /** @var array */
    protected $__module_medical_treatment_config = [];

    /**
     * A description of the entire PHP function.
     *
     * @param Container $app The Container instance
     * @throws Exception description of exception
     * @return void
     */
    public function __construct()
    {
        $this->setConfig('module-medical-treatment', $this->__module_medical_treatment_config);
    }
}
