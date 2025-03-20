<?php

namespace Hanafalah\ModuleMedicalTreatment\Schemas;

use Hanafalah\ModuleService\Contracts\ServicePrice;
use Illuminate\Database\Eloquent\Builder;
use Hanafalah\ModuleMedicalTreatment\Contracts;
use Hanafalah\ModuleMedicalTreatment\Contracts\MedicalServiceTreatment;
use Hanafalah\ModuleMedicalTreatment\Resources\MedicalTreatment\ShowMedicalTreatment;
use Hanafalah\ModuleMedicalTreatment\Resources\MedicalTreatment\ViewMedicalTreatment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Hanafalah\LaravelSupport\Supports\PackageManagement;
use Hanafalah\ModuleTransaction\Contracts\PriceComponent;

class MedicalTreatment extends PackageManagement implements Contracts\MedicalTreatment
{
    protected array $__guard   = ['id'];
    protected array $__add     = ['name'];
    protected string $__entity = 'MedicalTreatment';
    public static $medical_treatment_model;
    protected static $__service_label_model;

    protected array $__resources = [
        'view' => ViewMedicalTreatment::class,
        'show' => ShowMedicalTreatment::class
    ];

    public function prepareViewMedicalTreatmentPaginate(int $perPage = 50, array $columns = ['*'], string $pageName = 'page', ?int $page = null, ?int $total = null): LengthAwarePaginator
    {
        $attributes ??= request()->all();
        $paginate_options = compact('perPage', 'columns', 'pageName', 'page', 'total');
        $model = $this->medicalTreatment()->paginate(...$this->arrayValues($paginate_options))
            ->appends($attributes);
        return static::$medical_treatment_model = $model;
    }

    public function viewMedicalTreatmentPaginate(int $perPage = 50, array $columns = ['*'], string $pageName = 'page', ?int $page = null, ?int $total = null): array
    {
        $paginate_options = compact('perPage', 'columns', 'pageName', 'page', 'total');
        return $this->transforming($this->__resources['view'], function () use ($paginate_options) {
            return $this->prepareViewMedicalTreatmentPaginate(...$this->arrayValues($paginate_options));
        });
    }

    public function medicalTreatment(mixed $conditionals = null): Builder
    {
        $this->booting();
        return $this->MedicalTreatmentModel()->withParameters('or')->conditionals($conditionals)
            ->with(['medicServices', 'serviceLabel', 'treatment', 'priceComponents.tariffComponent'])
            ->orderBy('name', 'asc');
    }

    public function showUsingRelation(): array
    {
        return [
            'medicServices',
            'priceComponents.tariffComponent',
            'treatment'
        ];
    }


    public function prepareShowMedicalTreatment(?Model $model = null, ?array $attributes = null): Model
    {
        $attributes ??= \request()->all();

        $model ??= $this->getMedicalTreatment();
        if (!isset($model)) {
            $id = $attributes['id'] ?? null;
            if (!isset($id)) throw new \Exception('id is required');

            $model = $this->medicalTreatment()->with($this->showUsingRelation())->findOrFail($id);
        } else {
            $model->load($this->showUsingRelation());
        }
        return static::$medical_treatment_model = $model;
    }

    public function showMedicalTreatment(?Model $model = null): array
    {
        return $this->transforming($this->__resources['show'], function () use ($model) {
            return $this->prepareShowMedicalTreatment($model);
        });
    }

    public function prepareUpdateMedicalTreatmentStatus(?array $attributes = null): Model
    {
        $attributes ??= \request()->all();

        if (!isset($attributes['id'])) throw new \Exception('id is required');

        $model = $this->MedicalTreatmentModel()->findOrFail($attributes['id']);

        $model->status = $attributes['status'];
        $model->save();
        return static::$medical_treatment_model = $model;
    }

    public function updateMedicalTreatmentStatus(): array
    {
        return $this->transaction(function () {
            return $this->showMedicalTreatment($this->prepareUpdateMedicalTreatmentStatus());
        });
    }

    public function prepareStoreMedicalTreatment(?array $attributes = null): Model
    {
        $attributes ??= \request()->all();
        if (!isset($attributes['name'])) throw new \Exception('name is required');

        $model = $this->medicalTreatment()->updateOrCreate([
            'id' => $attributes['id'] ?? null
        ], [
            'name' => $attributes['name'],
        ]);

        if (isset($attributes['medic_services']) && count($attributes['medic_services']) > 0) {
            $medic_service_schema = $this->schemaContract('medical_service_treatment');
            $keep_service_treatment_ids = [];
            foreach ($attributes['medic_services'] as $medic_service) {
                $service = $this->ServiceModel()->findOrFail($medic_service['id']);
                $medical_service_treatment = $medic_service_schema->prepareStoreMedicalServiceTreatment([
                    'medical_treatment_id' => $model->getKey(),
                    'medic_service_id'     => $service->reference_id,
                    'name'                 => $service->name,
                    'note'                 => $service->result
                ]);
                $keep_service_treatment_ids[] = $medical_service_treatment->getKey();
            }
            $this->MedicalServiceTreatmentModel()
                ->withoutGlobalScopes()
                ->where('medical_treatment_id', $model->getKey())
                ->whereNotIn('id', $keep_service_treatment_ids)
                ->forceDelete();
        } else {
            throw new \Exception('medic_services is required');
        }

        $treatment = $model->treatment;

        if (isset($attributes['service_label_id'])) {
            $service_label = $this->ServiceLabelModel()->findOrFail($attributes['service_label_id']);
            static::$__service_label_model = $service_label;

            $treatment = $model->treatment;
            $treatment->service_label = [
                'id'   => $attributes['service_label_id'],
                'name' => $service_label->name,
                'note' => $service_label->result
            ];
            $model->service_label = $treatment->service_label;
            $model->service_label_id = $attributes['service_label_id'];
        } else {
            $treatment->service_label = null;
            $model->service_label     = null;
            $model->service_label_id  = null;
        }

        if (isset($attributes['tariff_components']) && count($attributes['tariff_components']) > 0) {
            $price_schema = $this->schemaContract('price_component');
            $attributes['model_id'] = $model->getKey();
            $attributes['model_type'] = $model->getMorphClass();
            $price_schema->prepareStorePriceComponent($attributes);

            $treatment->price = $price_schema->getPrice();

            $service_price_schema = app($this->__schema_contracts['service_price']);
            $service_price_schema->prepareStoreServicePrice([
                'service_id'         => $treatment->getKey(),
                'service_item_id'    => $treatment->reference_id,
                'service_item_type'  => $treatment->reference_type,
                'price'              => $treatment->price,
            ]);

            if (isset($attributes['margin'])) {
                $treatment->cogs = $treatment->price - $treatment->price * $attributes['margin'] / 100;
            }
        }
        if (isset($attributes['examination_stuff_id'])) {
            $examStuff = $this->ExaminationStuffModel()->findOrFail($attributes['examination_stuff_id']);

            $treatment->service_label_id   = $examStuff->getKey();
            $treatment->service_label_name = $examStuff->name;
            $treatment->service_label_flag = $examStuff->flag;
        }
        $treatment->save();
        $model->save();

        $service_price_schema = $this->schemaContract('service_price');
        $service_price_schema->prepareStoreServicePrice([
            'service_id'         => $treatment->getKey(),
            'service_item_id'    => $treatment->reference_id,
            'service_item_type'  => $treatment->reference_type,
            'price'              => $treatment->price,
        ]);
        return static::$medical_treatment_model = $model;
    }

    public function storeMedicalTreatment(): array
    {
        return $this->transaction(function () {
            return $this->showMedicalTreatment($this->prepareStoreMedicalTreatment());
        });
    }

    public function prepareDeleteMedicalTreatment(?array $attributes = null): bool
    {
        $attributes ??= request()->all();
        if (!isset($attributes['id'])) throw new \Exception('id is required');

        return $this->MedicalTreatmentModel()->destroy($attributes['id']);
    }

    public function deleteMedicalTreatment(): bool
    {
        return $this->transaction(function () {
            return $this->prepareDeleteMedicalTreatment();
        });
    }

    public function getMedicalTreatment(): mixed
    {
        return static::$medical_treatment_model;
    }


    public function addOrChange(?array $attributes = []): self
    {
        $medical_treatment = $this->updateOrCreate($attributes);
        static::$medical_treatment_model = $medical_treatment;
        if (isset($attributes['price'])) {
            $medical_treatment->load('treatment');
            if (isset($medical_treatment->treatment)) {
                $treatment = $medical_treatment->treatment;
                $treatment->price = $attributes['price'];
                $treatment->save();
            }
        }
        return $this;
    }
}
