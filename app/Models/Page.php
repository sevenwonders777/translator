<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Phrase;

class Page extends Model
{
    public $fillable = ['title','slug'];
    
    public function getPhraseList() {
        $result = [];
        
        $hashes = Phrase::select('hash')
            ->wherePageId($this->id)
            ->groupBy('hash')
            ->orderBy('created_at')
            ->get();
           
        foreach($hashes as $hashRow) {
            $result[$hashRow->hash] = [];

            $rows = Phrase::join('languages', 'languages.id', '=', 'phrases.language_id')
                ->wherePageId($this->id) 
                ->whereHash($hashRow->hash)  
                ->orderBy('priority')
                ->get();
            foreach($rows as $row) {
                $result[$hashRow->hash][$row->code] = $row->word;    
            }
        }
        
        return $result;
    }
}
