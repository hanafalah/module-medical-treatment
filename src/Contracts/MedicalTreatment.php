<?php

namespace Hanafalah\ModuleMedicalTreatment\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface MedicalTreatment extends ModuleMedicalTreatment
{
  public function prepareViewMedicalTreatmentPaginate(int $perPage = 50, array $columns = ['*'], string $pageName = 'page', ?int $page = null, ?int $total = null): LengthAwarePaginator;
  public function viewMedicalTreatmentPaginate(int $perPage = 50, array $columns = ['*'], string $pageName = 'page', ?int $page = null, ?int $total = null): array;
  public function medicalTreatment(mixed $conditionals = null): Builder;
  public function showUsingRelation(): array;
  public function prepareShowMedicalTreatment(?Model $model = null, ?array $attributes = null): Model;
  public function showMedicalTreatment(?Model $model = null): array;
  public function prepareStoreMedicalTreatment(?array $attributes = null): Model;
  public function storeMedicalTreatment(): array;
  public function getMedicalTreatment(): mixed;
  public function addOrChange(?array $attributes = []): self;
  public function prepareDeleteMedicalTreatment(?array $attributes = null): bool;
  public function deleteMedicalTreatment(): bool;
}
