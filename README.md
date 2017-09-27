# translator
Laravel 5 Translator

 This is Laravel translator package for translating Eloquent Data.
 
Install
 
    composer require evans-kim/translator
    php artisan vendor:publish --tag=translator
    php artisan migrate
    php artisan translator:install menus // the argument is database table name

And, Please add a trait in your Eloquent Model
   
    class Menu extends Model
    {
        use TranslationTrait;
    }

Sample

    $translations = ['kr:처음화면','en:Home']; // delimiter can be changed at config file. [locale:value] 
    $menu = Menu::find(1);
    $menu->syncTranslation('name', $translation); // translation data will be created and sync it with Eloquent Model
    echo $menu->name; // Home
    echo $menu->translate('kr')->name; // 처음화면
    echo $menu->translate('en')->name; // Home
