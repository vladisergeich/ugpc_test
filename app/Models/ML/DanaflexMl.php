<?php

namespace App\Models\ML;

use Illuminate\Database\Eloquent\Model;
use Session;

class DanaflexMl extends Model
{
    protected $connection = 'global';

    public static function ml($varname,  $baseText='')
    {
        $lnags = ['ru','en','cz']; //поменять на справочник

        $lng = Session::get('MlKey' , 'ru');
        $url = redirect()->back()->getTargetUrl();
        $element = self::where('varname',$varname)->where('main',true)->first();
        if ($element == null){
            foreach ($lnags as $key => $lang) {
                $element = new self();
                $element->varname = $varname;
                $element->text = $baseText;
                if ($lang == 'ru')
                    $element->main = true;
                $element->lngCode = $lang;
                $element->link = $url;
                $element->save();
            }
            $finaltext = $baseText;
        } else{
            $finaltext = $element = self::where('varname',$varname)->where('lngCode',$lng)->first()->text;
        }
        return $finaltext;
    }

    public function getOtherLangs()
    {
        return self::where('main',false)->where('varname',$this->varname)->get();
    }
}
