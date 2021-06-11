<?php

namespace App\Classes;

// use App\Classes\NaziefAndriani;
use Sastrawi;

class TextMining{
    public function textMining($kata){
        // proses TexMining

        $kata = $this->casefolding($kata);  //casefolding
        $kata = $this->tokenizing($kata);   //tokenizing
        // print ($kata ."<br>");


        $kata = $this->filtering($kata);    //filtering
        // print($kata ."<br>");

        $stemmerf = new Sastrawi\Stemmer\StemmerFactory();
        $stemmer = $stemmerf->createStemmer();
        $kata = $stemmer->stem($kata);
        // print ($kata ."<br>");

        $kata = str_replace("-", " ", $kata);
        $kata = preg_replace('/\s+/', ' ', $kata);

        // cek apakah ada valaue kosong pada array
        $cek_null_value = explode(" ", $kata);
        foreach ($cek_null_value as $key => $value) {
            if(!$cek_null_value[$key]){
                unset($cek_null_value[$key]);
            }
        }
        $kata = array_values($cek_null_value);
        $kata = implode(" ", $kata);

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
        // $kata = str_replace("-", "", $kata);
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
        $kata = preg_replace('/\s+/', ' ', $kata);
        
        return $kata;
    }

    public function filtering($kata){
        $kata = explode(" ", $kata); //proses tokenisasi

        // membuang kata-kata yang memiliki tingkat kemunculan tinggi dan tidak memiliki makna khusus
        $stopword = file("id.stopwords.02.01.2016.txt", FILE_IGNORE_NEW_LINES);

        $kata = array_diff($kata, $stopword); //remove stopword

        // return array_values($kata);
        return implode(" ", $kata);
    }
}

?>