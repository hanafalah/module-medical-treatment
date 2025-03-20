<?php

namespace Gilanggustina\ModuleMedicalTreatment\Models\MedicalTreatment;

use Illuminate\Database\Eloquent\SoftDeletes;
use Hanafalah\LaravelSupport\Models\BaseModel;
use Hanafalah\LaravelHasProps\Concerns\HasProps;

class MedicalServiceTreatment extends BaseModel
{
    use SoftDeletes, HasProps;

    protected $list = ['id', 'medical_treatment_id', 'medic_service_id'];
    protected $show = [];

    // public function toViewApi(){
    //     return new ViewMedicalServiceTreatment($this);
    // }

    // public function toShowApi(){
    //     return new ViewMedicalTreatment($this);
    // }

    //EIGER SECTION
    public function medicalTreatment()
    {
        return $this->belongsToModel('MedicalTreatment');
    }
    public function medicService()
    {
        return $this->belongsToModel('MedicService');
    }

    //ENDEIGER SECTION
}
