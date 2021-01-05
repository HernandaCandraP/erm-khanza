<?php

namespace App\Classes;

class CosineSimilarity{
    public function prosesCosine($kunci, $jawaban){
        $kunci = explode(" ", $kunci);
        $jawaban = explode(" ", $jawaban);

        $unique = $this->unique($kunci, $jawaban);

        // perhitungan banyaknya nilai kemunculan kata
        $kunci = $this->term($kunci, $unique);
        $jawaban = $this->term($jawaban, $unique);

        // menghitung jumlah term kunci dan jawaban
        $df = $this->df($kunci, $jawaban, $unique);

        // menghitung tf.idf
        $kunci = $this->tfidf($df, $kunci, $unique);
        $jawaban = $this->tfidf($df, $jawaban, $unique);

        // menghitung perkalian skalar
        $skalar = $this->skalar($kunci, $jawaban, $unique);

        // mencari panjang vektor
        $kunci = $this->panjang_vektor($kunci, $unique);
        $jawaban = $this->panjang_vektor($jawaban, $unique);

        // pembagian skalar dengan panjang vector
        $hasilakhir = $this->hasil_akhir($kunci, $jawaban, $skalar);

        return $hasilakhir;
    }

    private function unique($kunci, $jawaban) {
        $unique_merged = array_merge($kunci, $jawaban);
        $unique_merged = array_unique($unique_merged);
        
        return array_values($unique_merged);
    }

    private function term($kata, $unique){
        $hasil = [];
        $no = 0;
        for ($x=0; $x < count($unique)  ; $x++) { 
            for ($y=0; $y < count($kata) ; $y++) { 
                if($unique[$x] == $kata[$y]){
                    $no += 1;
                }
                $hasil[$x] = $no;
            }
            $no=0;
        }
        return $hasil;
    }
    
    private function df($Q1, $D1, $unique){
        $hasil = [];
        for ($x=0; $x < count($unique)  ; $x++) { 
            if ($Q1[$x] >=1 && $D1[$x] >=1) {
                $hasil[$x] = 2;
            }else{
                $hasil[$x] =1;
            }
        }
        return $hasil;
    }

    private function tfidf($df, $kata, $unique){
        $hasil = [];
        for ($x=0; $x < count($unique)  ; $x++) { 
            //idf = log(2)+1
            //tf-idf = idf * $kata dokumen
            $hasil[$x] = (log(2/$df[$x],10)+1) * $kata[$x];
        }
        return $hasil;
    }
    
    private function skalar($Q1, $D1, $unique){
        $hasil = [];
        $sum =0;
        for ($x=0; $x < count($unique)  ; $x++) { 
            $hasil[$x] = $Q1[$x] * $D1[$x];
            $sum += $hasil[$x];
        }
        return $sum;
    }
    
    private function panjang_vektor($kata, $unique){
        $sum = 0;    
        for ($x=0; $x < count($unique)  ; $x++) { 
            $hasil[$x] = pow($kata[$x],2);
            $sum += $hasil[$x];
        }
        $sum = sqrt($sum);
        return $sum;
    }
    
    private function hasil_akhir($Q1, $D1, $skalar){
        $hasil = $skalar/($Q1*$D1);
        return $hasil*100;
    }
}

?>