<?php
namespace EvansKim\Translator;

use Illuminate\Support\Facades\App;

/**/
trait TranslationTrait {
    protected $isTranslated = false;
    protected $localeTrans = '';
    protected $autoTranslate = false;
    public function __get($name)
    {
        if($this->isTranslated || $this->autoTranslate ){
            $this->isTranslated = false;
            $tranlation = parent::belongsToMany(Translation::class)
                ->where( config("translator.table").'.locale', '=', $this->localeTrans )
                ->where( config("translator.table").".name", $name)
                ->first();
            if(!empty($tranlation)){
                return $tranlation->value;
            }else{
                return parent::__get($name);
            }
        }else{
            return parent::__get($name);
        }
    }
    public function translate($locale="")
    {
        if(!$locale){
            $this->localeTrans = App::getLocale();
        }else{
            $this->localeTrans = $locale;
        }
        $this->isTranslated = true;
        return $this;
    }
    public function translations()
    {
        return parent::belongsToMany(Translation::class);
    }

    /**
     * set translations into database
     * @param $field
     * @param array $translations
     */
    public function syncTranslation($field, Array $translations)
    {
        if(!empty($translations)){
            foreach ( $translations as $trans ){
                $temp = explode( config("translator.delimiter"), $trans);
                $trans = ['locale'=>$temp[0], 'group'=>self::class, 'name'=>$field, 'value'=>$temp[1]];
                $translation = Translation::where($trans)->first();
                if(empty($translation)){
                    $translation = new Translation();
                    $translation->fill($trans);
                    $translation->save();
                }
                $ids[] = $translation->id;
                // If some data were removed,
                Translation::where( [['group', self::class], ['name', $field]] )->whereNotIn('id', $ids)->delete();
            }
            $this->translations()->sync($ids);
        }
    }

    /**
     * Get all translations of this model.
     *
     * @param string $field
     * @return mixed
     */
    public function getTranslations($field="")
    {
        if($field){
            return Translation::where("group", self::class)->where("name", $field)->get();
        }
        return Translation::where("group", self::class)->get();
    }
}