<?php

namespace Gilanggustina\ModuleMedicalTreatment\Concerns;

trait HasProfession {
    protected $__foreign_key = 'treatment_id';

    public function initializeHasProfession(){
        $this->mergeFillable([
            $this->__foreign_key
        ]);
    }

    //EIGER SECTION
    public function reatment(){
        return $this->morphOneModel('Treatment');
    }
}