<?php

namespace Database\Seeders;

use App\Models\State;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        State::create([
            'name' => 'Por asignar'
        ]);

        State::create([
            'name' => 'Por iniciar'
        ]);

        State::create([
            'name' => 'En ruta'
        ]);

        State::create([
            'name' => 'En el lugar'
        ]);

        State::create([
            'name' => 'Finalizado'
        ]);

        State::create([
            'name' => 'Anulado'
        ]);
    }
}
