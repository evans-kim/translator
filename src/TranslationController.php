<?php

namespace EvansKim\Translator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TranslationController extends Controller
{
    public function translations(Request $request)
    {
        $q = $request->input('q');

        $translations = Translation::select("id", "name as text");

        if($q){
            $translations = $translations->where('name','like','%'.$q.'%')->paginate();

        }else{
            $translations = $translations->paginate();
        }
        return $translations;
    }
}
