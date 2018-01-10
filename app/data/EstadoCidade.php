<?php

namespace App\Data;

use Illuminate\Support\Facades\DB;

class EstadoCidade {

    public function getEstados() {
        return DB::select("SELECT * FROM estado");
    }

    public function getCidades($id) {
        return DB::select("SELECT * FROM cidade WHERE estado = ?", [$id]);
    }
}