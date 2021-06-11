<?php

namespace App\Classes;
use DB;

class Pengujian{
    public function pengujian_cosine($data_uji){
        $training = $this->training();

        $pengujian_cosine =  $this->perhitungan_pengujian($data_uji,$training['training_cosine']);
        return $pengujian_cosine;
    }

    public function pengujian_jaccard($data_uji){
        $training = $this->training();

        $pengujian_jaccard =  $this->perhitungan_pengujian($data_uji,$training['training_jaccard']);
        return $pengujian_jaccard;
    }

    public function training(){
        // mengambil data training
        $data = DB::select("SELECT nilai_guru,
        CASE
             WHEN nilai_cosine < 2  then 10
             WHEN nilai_cosine < 12  then 20
             WHEN nilai_cosine < 22  then 30
             WHEN nilai_cosine < 32  then 40
             WHEN nilai_cosine < 42  then 50
             WHEN nilai_cosine < 52  then 60
             WHEN nilai_cosine < 62  then 70
             WHEN nilai_cosine < 72  then 80
             WHEN nilai_cosine < 82  then 90
             ELSE 100
        END as 
        nilai_cosine, 
        CASE
             WHEN nilai_jaccard < 6 then 20
             WHEN nilai_jaccard < 16  then 30
             WHEN nilai_jaccard < 26  then 40
             WHEN nilai_jaccard < 36  then 50
             WHEN nilai_jaccard < 46  then 60
             WHEN nilai_jaccard < 56  then 70
             WHEN nilai_jaccard < 66  then 80
             WHEN nilai_jaccard < 76  then 90
             ELSE 100
        END as nilai_jaccard
        FROM `jawaban` WHERE nilai_guru IS NOT NULL AND id_jawaban <= 456");

        $sum_c = 0;
        $sum_j = 0;
        foreach ($data as $key => $value) {
            // mencari nilai absolute
            $abs_cosine = abs($value->nilai_guru - $value->nilai_cosine);
            $abs_jaccard = abs($value->nilai_guru - $value->nilai_jaccard);
            
            $sum_c = $sum_c + $abs_cosine;
            $sum_j = $sum_j + $abs_jaccard;
        }

        // menghitung rata - rata
        $hasil_training_c = $sum_c/count($data);
        $hasil_training_j = $sum_j/count($data);

        return array('training_cosine' => $hasil_training_c, 'training_jaccard' => $hasil_training_j);
    }

    public function perhitungan_pengujian($data, $hasil_training){
        $TP = 0; $TN = 0; $FP = 0; $FN = 0;

        foreach ($data as $key => $value) {
            if($value >= 0 && $value <= $hasil_training){
                $TP++;
            }elseif ($value >= $hasil_training) {
                $FP++;
            }elseif ($value <= 0 && $value >= -$hasil_training) {
                $TN++;
            }elseif ($value <= -$hasil_training) {
                $FN++;
            }
        }

        $precision = $TP/($TP+$FP);
        $recall = $TP/($TP+$FN);
        $accuracy = ($TP+$TN)/($TP+$TN+$FP+$FN);

        $precision = number_format($precision*100, 2, '.', '');
        $recall = number_format($recall*100, 2, '.', '');
        $accuracy = number_format($accuracy*100, 2, '.', '');

        $decision = array('TP' => $TP, 'TN' => $TN, 'FP' => $FP, 'FN' => $FN);
        $hasil_pengujian = array('precision' => $precision, 'recall' => $recall, 'accuracy' => $accuracy);
        return array_merge($decision, $hasil_pengujian);
    }
}