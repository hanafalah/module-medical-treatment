<?php

use Gilanggustina\ModuleTreatment\Enums\Treatment\TreatmentStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Gilanggustina\ModuleMedicalTreatment\Models\MedicalTreatment\{
    MedicalServiceTreatment,MedicalTreatment
};

use Gii\ModuleMedicService\Models\MedicService;

return new class extends Migration
{
    use Zahzah\LaravelSupport\Concerns\NowYouSeeMe;

    private $__table,$__table_medical_treatment,$__table_medic_service;

    public function __construct(){
        $this->__table = app(config('database.models.MedicalServiceTreatment', MedicalServiceTreatment::class));
        $this->__table_medical_treatment = app(config('database.models.MedicalTreatment', MedicalTreatment::class));
        $this->__table_medic_service = app(config('database.models.MedicService', MedicService::class));
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        $table_name = $this->__table->getTable();
        if (!$this->isTableExists()){
            Schema::create($table_name, function (Blueprint $table) {
                $table->id();
                $table->json('props')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
            
            Schema::table($table_name,function (Blueprint $table){
                $table->foreignIdFor($this->__table_medical_treatment::class,'medical_treatment_id')
                    ->nullable()->after('id')
                    ->constrained($this->__table_medical_treatment->getTable(),$this->__table_medical_treatment->getKeyName(),'med_service_mt_fk')
                    ->cascadeOnUpdate()->restrictOnDelete();

                $table->foreignIdFor($this->__table_medic_service::class,'medic_service_id')
                    ->nullable()->after('id')
                    ->constrained($this->__table_medic_service->getTable(),$this->__table_medic_service->getKeyName(),'med_service_ms_fk')
                    ->cascadeOnUpdate()->restrictOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists($this->__table->getTable());
    }
};
