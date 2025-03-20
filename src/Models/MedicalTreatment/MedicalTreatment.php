<?php

namespace Gilanggustina\ModuleMedicalTreatment\Models\MedicalTreatment;

use Hanafalah\ModuleService\Concerns\HasServiceItem;
use Gilanggustina\ModuleMedicalTreatment\Enums\MedicalTreatment\Status;
use Gilanggustina\ModuleMedicalTreatment\Resources\MedicalTreatment\ViewMedicalTreatment;
use Illuminate\Database\Eloquent\SoftDeletes;
use Hanafalah\LaravelSupport\Concerns\Support\HasEncoding;
use Hanafalah\LaravelSupport\Models\BaseModel;
use Hanafalah\LaravelHasProps\Concerns\HasProps;
use Gilanggustina\ModuleTreatment\Concerns\HasTreatment;

class MedicalTreatment extends BaseModel
{
    use SoftDeletes, HasProps, HasServiceItem, HasTreatment, HasEncoding;

    protected $list = ['id', 'name', 'status', 'props'];
    protected $show = [];
    protected $table = 'medical_treatments';

    protected $casts = [
        'name' => 'string'
    ];

    protected static function booted(): void
    {
        parent::booted();
        static::creating(function ($query) {
            if (!isset($query->medical_treatment_code)) $query->medical_treatment_code = static::hasEncoding('MEDICAL_TREATMENT');
            if (!isset($query->status)) $query->status = Status::ACTIVE->value;
        });
    }

    public function toViewApi()
    {
        return new ViewMedicalTreatment($this);
    }

    public function toShowApi()
    {
        return new ViewMedicalTreatment($this);
    }

    //EIGER SECTION
    public function medicServices()
    {
        return $this->belongsToManyModel(
            'MedicService',
            'MedicalServiceTreatment',
            'medical_treatment_id',
            'medic_service_id'
        );
    }
    public function serviceLabel()
    {
        return $this->belongsToModel('ServiceLabel');
    }
    public function medicalServiceTreatment()
    {
        return $this->hasOneModel('MedicalServiceTreatment');
    }
    public function medicalServiceTreatments()
    {
        return $this->hasManyModel('MedicalServiceTreatment');
    }
    public function priceComponent()
    {
        return $this->morphOneModel("PriceComponent", "model");
    }
    public function priceComponents()
    {
        return $this->morphManyModel("PriceComponent", "model");
    }
    //ENDEIGER SECTION
}
