<?php

namespace Hanafalah\ModuleMedicalTreatment\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

interface MedicalServiceTreatment extends ModuleMedicalTreatment
{
  public function showUsingRelation(): array;
  public function medicalServiceTreatment(mixed $conditionals = null): Builder;
  public function prepareStoreMedicalServiceTreatment(?array $attributes = null): Model;
}
