<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class UserGroup extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $routes = Route::getRoutes();
        $routeList = [];
        foreach ($routes as $key => $value) {

            if ($value->action['namespace'] == 'App\Http\Controllers' && !empty($value->action['as'])) {
                if (!in_array($value->action['as'], $routeList)) {
                    $routeList[] = $value->action['as'];
                }
            }
        }
        DB::table('user_groups')->insert([
            'ug_name' => 'Administrator',
            'ug_permissions' => implode( ',',$routeList ),
        ]);
    }
}
