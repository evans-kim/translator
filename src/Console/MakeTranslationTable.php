<?php

namespace EvansKim\Translator\Console;

use DB;
use File;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class MakeTranslationTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translator:install {model1} ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make translation table migration file';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $model1 = $this->argument('model1');
        $model2 = config("translator.table");

        $pivots[] = str_singular( $model1 );
        $pivots[] = str_singular($model2);
        sort($pivots);
        $pivotName = implode("_", $pivots);
        $stub_path = app_path("Console/Commands/stubs");

        $cont = File::get( __DIR__ . "/pivotMigration.stub");

        $getPrimaryField = function($model){
            $columns = collect( DB::select(" SHOW COLUMNS FROM $model ") );
            $id = "id";
            $columns->each( function($item, $key) use ($id){
                if($item->Key == "PRI"){
                    $id = $item->Field;
                }
            });
            return $id;
        };
        $model1_id  =   $getPrimaryField($model1);
        $model2_id  =   $getPrimaryField($model2);


        $cont = preg_replace( "/PivotName/", $pivotName, $cont);
        $cont = preg_replace( "/PivotClass/",  ucfirst( camel_case($pivotName) ), $cont);
        $cont = preg_replace( "/Model1Pri/", str_singular($model1)."_".$model1_id, $cont);
        $cont = preg_replace( "/Model2Pri/", str_singular($model2)."_".$model2_id, $cont);
        $cont = preg_replace( "/M1Pid/", $model1_id, $cont);
        $cont = preg_replace( "/M2Pid/", $model2_id, $cont);
        $cont = preg_replace( "/model1/", $model1, $cont);
        $cont = preg_replace( "/model2/", $model2, $cont);
        $base = app_path("database/migrations");
        File::makeDirectory($base, 0755, true, true);
        File::put($base."/".date("Y_m_d_His")."_create_".$pivotName."_table.php", $cont);

        Artisan::call("migrate");

        $this->info("migration file was created and migrated.' ");
    }
}
