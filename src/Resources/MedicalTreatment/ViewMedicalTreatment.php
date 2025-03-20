<?php

namespace Hanafalah\ModuleMedicalTreatment\Resources\MedicalTreatment;

use Hanafalah\LaravelSupport\Resources\ApiResource;

class ViewMedicalTreatment extends ApiResource
{
  public function toArray(\Illuminate\Http\Request $request): array
  {
    $arr = [
      'id'         => $this->id,
      'name'       => $this->name,
      'treatment_code' => $this->treatment_code ?? $this->medical_treatment_code,
      'treatment'      => $this->relationValidation('treatment', function () {
        return $this->treatment->toViewApi();
      }),
      'service_label_id'   => $this->service_label_id,
      'service_label'      => isset($this->service_label) ? [
        'id'   => $this->service_label['id'] ?? null,
        'name' => $this->service_label['name'] ?? null,
        'note' => $this->service_label['note'] ?? null
      ] : null,
      'tariff_components' => $this->relationValidation('priceComponents', function () {
        $priceComponents = $this->priceComponents;
        return $priceComponents->transform(function ($priceComponent) {
          return  [
            "id"    => $priceComponent->tariff_component_id,
            "price" => $priceComponent->price ?? $this->treatment->price ?? 0,
            "name"  => $priceComponent->tariffComponent->name ?? "Name is invalid",
          ];
        });
      }),
      'medic_services' => $this->relationValidation('medicServices', function () {
        return $this->medicServices->transform(function ($medicService) {
          $service = $medicService->service;
          return [
            'id'   => $service->id,
            'name' => $medicService->name
          ];
        });
      }),
      'created_at' => $this->created_at,
      'updated_at' => $this->updated_at
    ];
    $arr = $this->mergeArray($this->getPropsData() ?? [], $arr);

    return $arr;
  }
}
