<?php

namespace App\Classes;

class JaccardSimilarity{
    public function prosesJaccard($kunci, $jawaban){
        $kunci = explode(" ", $kunci);
        $jawaban = explode(" ", $jawaban);

        $gabungan = $this->gabungan($kunci, $jawaban);
        $irisan = $this->irisan($kunci, $jawaban);

        $jaccard = ($irisan/$gabungan)*100;

        return $jaccard;
    }

    private function gabungan($kunci, $jawaban) {
        $unique_merged = array_merge($kunci, $jawaban);
        $unique_merged = array_unique($unique_merged);
        
        return count($unique_merged);
    }

    private function irisan($kunci, $jawaban){
        $kunci = array_values(array_unique($kunci));
        $jawaban = array_values(array_unique($jawaban));
        // print_r($kunci);
        $hasil = 0;
        for ($x=0; $x < count($kunci)  ; $x++) { 
            for ($y=0; $y < count($jawaban) ; $y++) { 
                if($kunci[$x] == $jawaban[$y]){
                    $hasil += 1;
                }
            }
        }
        return $hasil;
    }  
}

?>