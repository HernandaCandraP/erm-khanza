<?php

namespace App\Classes;

use App\Classes\NaziefAndriani;

class TextMining{
    public function textMining($kata){
        // proses TexMining
        $kata = $this->casefolding($kata);  //casefolding
        $kata = $this->tokenizing($kata);   //tokenizing
        $kata = $this->filtering($kata);    //filtering

        $stemming = new NaziefAndriani();   //stemming
        $kata = $stemming->Stemming($kata);

        return $kata;
    }

    public function casefolding($kata){
        // mengubah format kata menjadi huruf kecil
        $kata = strtolower(trim($kata)); 

        return $kata;
    }

    public function tokenizing($kata){
        //bersihkan tanda baca, ganti dengan space 
        $kata = str_replace("'", "", $kata);
        $kata = str_replace("-", "", $kata);
        $kata = str_replace(")", "", $kata);
        $kata = str_replace("(", "", $kata);
        $kata = str_replace("\"", "", $kata);
        $kata = str_replace("/", "", $kata);
        $kata = str_replace("=", "", $kata);
        $kata = str_replace(".", "", $kata);
        $kata = str_replace(",", "", $kata);
        $kata = str_replace(":", "", $kata);
        $kata = str_replace(";", "", $kata);
        $kata = str_replace("!", "", $kata);
        $kata = str_replace("?", "", $kata); 
        $kata = str_replace(">", "", $kata); 
        $kata = str_replace("<", "", $kata); 
        
        return $kata;
    }

    public function filtering($kata){
        $kata = explode(" ", $kata); //proses tokenisasi

        // membuang kata-kata yang memiliki tingkat kemunculan tinggi dan tidak memiliki makna khusus
        $stopword = array("a", "about", "above", "acara", "across", "ada", "adalah");

        $kata = array_diff($kata, $stopword); //remove stopword

        return array_values($kata);
    }
}

?>