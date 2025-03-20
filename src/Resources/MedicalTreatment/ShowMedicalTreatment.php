<?php

namespace Gilanggustina\ModuleMedicalTreatment\Resources\MedicalTreatment;

use Zahzah\LaravelSupport\Resources\ApiResource;

class ShowMedicalTreatment extends ViewMedicalTreatment
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request):array{
      $arr = [
      ];
      $arr = array_merge($arr, parent::toArray($request));
      
      return $arr;
    }
}
