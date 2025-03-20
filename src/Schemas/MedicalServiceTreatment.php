<?php

namespace Gilanggustina\ModuleMedicalTreatment\Schemas;

use Illuminate\Database\Eloquent\Builder;
use Gilanggustina\ModuleMedicalTreatment\Contracts;
use Illuminate\Database\Eloquent\Model;
use Hanafalah\LaravelSupport\Supports\PackageManagement;

class MedicalServiceTreatment extends PackageManagement implements Contracts\MedicalServiceTreatment
{
    protected array $__guard   = ['id', 'medical_treatment_id'];
    protected array $__add     = ['medical_treatment_id', 'medic_service_id'];
    protected string $__entity = 'MedicalServiceTreatment';
    public static $medical_service_treatment_model;

    public function showUsingRelation(): array
    {
        return [];
    }

    public function medicalServiceTreatment(mixed $conditionals = null): Builder
    {
        $this->booting();
        return $this->MedicalServiceTreatment()
            ->withParameters()
            ->conditionals($conditionals);
    }

    public function prepareStoreMedicalServiceTreatment(?array $attributes = null): Model
    {
        $attributes ??= request()->all();
        if (isset($attributes['id'])) {
            $guard = ['id' => $attributes['id']];
        } else {
            if (!isset($attributes['medic_service_id']) || !isset($attributes['medical_treatment_id'])) throw new \Exception('medic_service_id and medical_treatment_id is required');
            $guard = [
                'medic_service_id' => $attributes['medic_service_id'],
                'medical_treatment_id' => $attributes['medical_treatment_id']
            ];
        }
        $model = $this->MedicalServiceTreatmentModel()->updateOrCreate($guard);
        $model->name = $attributes['name'] ?? null;
        $model->save();
        return static::$medical_service_treatment_model = $model;
    }
}
