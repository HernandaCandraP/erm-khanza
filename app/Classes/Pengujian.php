<?php

namespace App\Classes;

use DB;

class Pengujian
{
    public function pengujian_cosine($data_uji)
    {
        $training = $this->training();

        $pengujian_cosine =  $this->perhitungan_pengujian($data_uji,$training['training_cosine']);
        return $pengujian_cosine;
    }

    public function pengujian_jaccard($data_uji){
        $training = $this->training();

        $pengujian_jaccard =$this->perhitungan_pengujian($data_uji, $training['training_jaccard']);
        return $pengujian_jaccard;
    }

    public function training()
    {
        // mengambil data training
        $data = DB::select("SELECT nilai_guru,
        CASE
             WHEN jawaban_siswa is null or jawaban_siswa = '' then 0
             WHEN nilai_cosine < 1  then 20
             WHEN nilai_cosine < 11  then 30
             WHEN nilai_cosine < 21  then 40
             WHEN nilai_cosine < 31  then 50
             WHEN nilai_cosine < 41  then 60
             WHEN nilai_cosine < 51  then 70
             WHEN nilai_cosine < 61  then 80
             WHEN nilai_cosine < 71  then 90
             ELSE 100
        END as 
        nilai_cosine, 
        CASE
            WHEN jawaban_siswa is null or jawaban_siswa = '' then 0
            WHEN nilai_jaccard < 3  then 30
             WHEN nilai_jaccard < 13  then 40
             WHEN nilai_jaccard < 23  then 50
             WHEN nilai_jaccard < 33  then 60
             WHEN nilai_jaccard < 43  then 70
             WHEN nilai_jaccard < 53  then 80
             WHEN nilai_jaccard < 63  then 90
             ELSE 100
        END as nilai_jaccard
        FROM `jawaban` WHERE nilai_guru IS NOT NULL LIMIT 150");

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
        $hasil_training_c = $sum_c / count($data);
        $hasil_training_j = $sum_j / count($data);
        // print_r(array('training_cosine' => $hasil_training_c, 'training_jaccard' => $hasil_training_j));
        return array('training_cosine' => $hasil_training_c, 'training_jaccard' => $hasil_training_j);
    }

    public function perhitungan_pengujian($data, $hasil_training)
    {
        $TP = 0;
        $TN = 0;
        $FP = 0;
        $FN = 0;

        foreach ($data as $key => $value) {
            if ($value >= 0 && $value <= $hasil_training) {
                $TP++;
            } elseif ($value >= $hasil_training) {
                $FP++;
            } elseif ($value <= 0 && $value >= -$hasil_training) {
                $TN++;
            } elseif ($value <= -$hasil_training) {
                $FN++;
            }
        }

        $precision = $TP / ($TP + $FP);
        $recall = $TP / ($TP + $FN);
        $accuracy = ($TP + $TN) / ($TP + $TN + $FP + $FN);

        $precision = number_format($precision * 100, 2, '.', '');
        $recall = number_format($recall * 100, 2, '.', '');
        $accuracy = number_format($accuracy * 100, 2, '.', '');

        $decision = array('TP' => $TP, 'TN' => $TN, 'FP' => $FP, 'FN' => $FN);
        $hasil_pengujian = array('precision' => $precision, 'recall' => $recall, 'accuracy' => $accuracy);
        return array_merge($decision, $hasil_pengujian);
    }
}
