<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Classes\TextMining;
use App\Classes\Pengujian;
use App\Classes\NaziefAndriani;
use App\Classes\CosineSimilarity;
use App\Classes\JaccardSimilarity;
use App\Classes\DiceSimilarity;
use Sastrawi;

// use App\Soal;
use App\Jawaban;


class TextMiningController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // $stemmerf = new Sastrawi\Stemmer\StemmerFactory();


        $mining = new TextMining();
        // $stemming = new NaziefAndriani();
        $jaccard = new JaccardSimilarity();
        $cosine = new CosineSimilarity();
        $pengujian = new Pengujian();
        // $dice = new DiceSimilarity();


        // $data = Jawaban::join('soal', 'jawaban.id_soal', '=', 'soal.id_soal')->whereNotNull('jawaban.nilai_guru')->select('soal.stemming', 'jawaban.stemming_jawaban_siswa')->get();


        // Data Uji
        // $data = DB::select("SELECT nilai_guru,
        // CASE
        //      WHEN nilai_cosine < 2  then 10
        //      WHEN nilai_cosine < 12  then 20
        //      WHEN nilai_cosine < 22  then 30
        //      WHEN nilai_cosine < 32  then 40
        //      WHEN nilai_cosine < 42  then 50
        //      WHEN nilai_cosine < 52  then 60
        //      WHEN nilai_cosine < 62  then 70
        //      WHEN nilai_cosine < 72  then 80
        //      WHEN nilai_cosine < 82  then 90
        //      ELSE 100
        // END as 
        // nilai_cosine, 
        // CASE
        //      WHEN nilai_jaccard < 6 then 20
        //      WHEN nilai_jaccard < 16  then 30
        //      WHEN nilai_jaccard < 26  then 40
        //      WHEN nilai_jaccard < 36  then 50
        //      WHEN nilai_jaccard < 46  then 60
        //      WHEN nilai_jaccard < 56  then 70
        //      WHEN nilai_jaccard < 66  then 80
        //      WHEN nilai_jaccard < 76  then 90
        //      ELSE 100
        // END as nilai_jaccard
        // FROM `jawaban` WHERE nilai_guru IS NOT NULL AND id_jawaban > 161");
        $data = DB::select("SELECT nilai_guru,
        CASE
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
             WHEN nilai_jaccard < 3  then 30
             WHEN nilai_jaccard < 13  then 40
             WHEN nilai_jaccard < 23  then 50
             WHEN nilai_jaccard < 33  then 60
             WHEN nilai_jaccard < 43  then 70
             WHEN nilai_jaccard < 53  then 80
             WHEN nilai_jaccard < 63  then 90
             ELSE 100
        END as nilai_jaccard
        FROM `jawaban` WHERE nilai_guru IS NOT NULL AND id_jawaban > 161");

        $data_cosine = [];
        $data_jaccard = [];
        foreach ($data as $key => $value) {
            array_push($data_cosine, $value->nilai_guru - $value->nilai_cosine);
            array_push($data_jaccard, $value->nilai_guru - $value->nilai_jaccard);
        }

        // $data = array(1, 6);

        $testing = $pengujian->pengujian_cosine($data_cosine);
        print("Cosine : ");
        print_r($testing);
        print("<br>");


        $testing = $pengujian->pengujian_jaccard($data_jaccard);
        print("Jaccard : ");
        print_r($testing);


        // $data = DB::select("SELECT nilai_guru,
        // CASE
        //      WHEN nilai_cosine < 2  then 10
        //      WHEN nilai_cosine < 12  then 20
        //      WHEN nilai_cosine < 22  then 30
        //      WHEN nilai_cosine < 32  then 40
        //      WHEN nilai_cosine < 42  then 50
        //      WHEN nilai_cosine < 52  then 60
        //      WHEN nilai_cosine < 62  then 70
        //      WHEN nilai_cosine < 72  then 80
        //      WHEN nilai_cosine < 82  then 90
        //      ELSE 100
        // END as 
        // nilai_cosine, 
        // CASE
        //      WHEN nilai_jaccard < 6 then 20
        //      WHEN nilai_jaccard < 16  then 30
        //      WHEN nilai_jaccard < 26  then 40
        //      WHEN nilai_jaccard < 36  then 50
        //      WHEN nilai_jaccard < 46  then 60
        //      WHEN nilai_jaccard < 56  then 70
        //      WHEN nilai_jaccard < 66  then 80
        //      WHEN nilai_jaccard < 76  then 90
        //      Else 100
        // END as nilai_jaccard
        // FROM `jawaban` WHERE nilai_guru IS NOT NULL");
        
         
        // // print_r($data);
        // $sum_c = 0;
        // $sum_j = 0;
        // $TP_C = 0; $TN_C = 0; $FP_C = 0; $FN_C = 0;
        // $TP_J = 0; $TN_J = 0; $FP_J = 0; $FN_J = 0;
        // foreach ($data as $key => $value) {
        //     $abs_cosine = abs($value->nilai_guru - $value->nilai_cosine);
        //     $abs_jaccard = abs($value->nilai_guru - $value->nilai_jaccard);
            
        //     $sum_c = $sum_c + $abs_cosine;
        //     $sum_j = $sum_j + $abs_jaccard;
        // }
        
        // $hasil_training_c = $sum_c/count($data);
        // $hasil_training_j = $sum_j/count($data);

        // print("<h3> hasil training Cosine ". $hasil_training_c . "<br> hasil training Jaccard ". $hasil_training_j. "</h3>");

        // foreach ($data as $key => $value) {
        //     $selisih_c = $value->nilai_guru - $value->nilai_cosine;
        //     $selisih_j = $value->nilai_guru - $value->nilai_jaccard;

        //     if($selisih_c >= 0 && $selisih_c <= $hasil_training_c){
        //         $TP_C++;
        //     }elseif ($selisih_c >= $hasil_training_c) {
        //         $FP_C++;
        //     }elseif ($selisih_c <= 0 && $selisih_c >= -$hasil_training_c) {
        //         $TN_C++;
        //     }elseif ($selisih_c <= -$hasil_training_c) {
        //         $FN_C++;
        //     }

        //     if($selisih_j >= 0 && $selisih_j <= $hasil_training_j){
        //         $TP_J++;
        //     }elseif ($selisih_j >= $hasil_training_j) {
        //         $FP_J++;
        //     }elseif ($selisih_j <= 0 && $selisih_j >= -$hasil_training_j) {
        //         $TN_J++;
        //     }elseif ($selisih_j <= -$hasil_training_j) {
        //         $FN_J++;
        //     }

        // }

        // print("<b>COSINE <br> TP: ". $TP_C ." - TN: ". $TN_C ." - FP: ". $FP_C ." - FN: ". $FN_C ."<br>");
        // print("Precision : ". $precision = $TP_C/($TP_C+$FP_C) ."<br>");
        // print("Recall : ". $recall = $TP_C/($TP_C+$FN_C) ."<br>");
        // print("Accuracy : ". $accuracy = ($TP_C+$TN_C)/($TP_C+$TN_C+$FP_C+$FN_C) ."<br><br>");

        // print("Jaccard <br> TP: ". $TP_J ." - TN: ". $TN_J ." - FP: ". $FP_J ." - FN: ". $FN_J ."<br>");
        // print("Precision : ". $precision = $TP_J/($TP_J+$FP_J) ."<br>");
        // print("Recall : ". $recall = $TP_J/($TP_J+$FN_J) ."<br>");
        // print("Accuracy : ". $accuracy = ($TP_J+$TN_J)/($TP_J+$TN_J+$FP_J+$FN_J) ."</b><br><br>");



        // $avg_cosine = DB::select("SELECT AVG(nilai_cosine) as 'avg_cosine' FROM `jawaban` WHERE nilai_guru IS NOT NULL");
        // $nilai_cosine = $avg_cosine[0]->avg_cosine;
        // print("AVG Nilai_Cosine:  ". $nilai_cosine ."<br>");

        // $avg_jaccard = DB::select("SELECT AVG(nilai_jaccard) as 'avg_jaccard' FROM `jawaban` WHERE nilai_guru IS NOT NULL");
        // $nilai_jaccard = $avg_jaccard[0]->avg_jaccard;
        // print("AVG Nilai_Jaccard:  ". $nilai_jaccard ."<br>");

        // $avg_guru = DB::select("SELECT AVG(nilai_guru) as 'avg_guru' FROM `jawaban` WHERE nilai_guru IS NOT NULL");
        // $nilai_guru = $avg_guru[0]->avg_guru;
        // print("AVG Nilai_Guru:  ". $nilai_guru ."<br>");


        // print("<h1>" . $mining->precision(100,100) . "</h1>");


        
        // $precision = $mining->precision($nilai_cosine, $nilai_guru);
        // $recall = $mining->recall($nilai_cosine, $nilai_guru);
        // $accuracy = $mining->accuracy($nilai_cosine, $nilai_guru);
        // print("Precision: " .$precision ."<br>");
        // print("recall: " .$recall ."<br>");
        // print("accuracy: " .$accuracy ."<br><br>");

        // print("Jaccard <br>");
        // $precision = $mining->precision($nilai_jaccard, $nilai_guru);
        // $recall = $mining->recall($nilai_jaccard, $nilai_guru);
        // $accuracy = $mining->accuracy($nilai_jaccard, $nilai_guru);
        // print("Precision: " .$precision ."<br>");
        // print("recall: " .$recall ."<br>");
        // print("accuracy: " .$accuracy ."<br>");

        
        // $accuracy = $mining->recall(60, 90);
        // print("<h1>accuracy: " .$nilai_jaccard ."_". $nilai_guru ."_". $nilai_cosine ."</h1><br>");
        // $selisih_j = $nilai_guru-$nilai_jaccard;
        // $selisih_c = $nilai_guru-$nilai_cosine;
        // print("<h1>accuracy: " .$selisih_j ."_". $selisih_c ."</h1><br>");

        // print($data);
        // foreach ($data as $key=>$data) {
        //     // $jawaban = $mining->textMining($data->jawaban_siswa);
        //     // print($data->jawaban_siswa ."<br><br>");
        //     $hasil_cosine[$key] = $cosine->prosesCosine($data->stemming, $data->stemming_jawaban_siswa);
        //     // print $hasil_cosine ."<br><br>";
        //     // $arr_hasil_cosine[] = $hasil_cosine;   
        // }

        // if(count($hasil_cosine)){
        //     print $average= array_sum($hasil_cosine)/count($hasil_cosine);
        // }
        
        // print("<br>");
        // $group_avg = DB::select("SELECT soal.id_detail, AVG(nilai_cosine) as 'avg_cosine', AVG(nilai_jaccard) as 'avg_jaccard', AVG(nilai_guru) as 'avg_guru' FROM jawaban 
        //     join soal on jawaban.id_soal = soal.id_soal
        //     WHERE nilai_guru IS NOT NULL
        //     Group by soal.id_detail");

        // print_r($group_avg);
        // print("<br><br>");

        // foreach ($group_avg as $key => $value) {
        //     $mapel = DB::select("SELECT mapel.nama_mapel FROM detailmapel
        //         join mapel on detailmapel.id_mapel = mapel.id_mapel
        //         WHERE detailmapel.id_detail = $value->id_detail");
        //     print_r($mapel[0]->nama_mapel. "<br>");

        //     $precision = $mining->precision($value->avg_cosine, $value->avg_guru);
        //     $recall = $mining->recall($value->avg_cosine, $value->avg_guru);
        //     $accuracy = $mining->accuracy($value->avg_cosine, $value->avg_guru);
        //     print("Cosine: ". $precision ." (precision) ". $recall ." (recall) ". $accuracy. " (accuracy)");
        //     print("<br>");

        //     $precision = $mining->precision($value->avg_jaccard, $value->avg_guru);
        //     $recall = $mining->recall($value->avg_jaccard, $value->avg_guru);
        //     $accuracy = $mining->accuracy($value->avg_jaccard, $value->avg_guru);
        //     print("jaccard: ". $precision ." (precision) ". $recall ." (recall) ". $accuracy. " (accuracy)");
        //     print("<br><br>");
        // }




        $katakunci = "pembubaran pki serta ormas-ormasnya
        perumusan harga dan perbaikan ekonomi
        rombak kabinet dwikora
        ";
        print("<h2>Kata Kunci</h2>");
        print_r($katakunci);
        print("<br/>");

        // $stemmer = $stemmerf->createStemmer();
        // $output = $stemmer->stem($katakunci);

        // $stopwordf = new Sastrawi\StopWordRemover\StopWordRemoverFactory();
        // $stopword = $stopwordf->createStopWordRemover($output);
        // $output = $stopword-
        
        // $output = 
        // print_r($output);

        $katakunci = $mining->textMining($katakunci);
        print_r($katakunci);
        print("<br/>");


        $jawaban = "membubarkan pki beserta ormas-ormasnya
        rombak kembali kabinet dwikora
        menurunkan harga sembako
        ";
        print("<h2>Jawaban</h2>");
        print_r($jawaban);
        print("<br/>");

        $jawaban = $mining->textMining($jawaban);
        print_r($jawaban);
        print("<br/>");

        print("<h2>Jaccard</h2>");
        $jaccardsimilarity = $jaccard->prosesJaccard($katakunci, $jawaban);
        print($jaccardsimilarity);
        print("% <br>");
        
        if ($jaccardsimilarity < 6 ) $jaccardsimilarity = 20;
        else if ($jaccardsimilarity < 16) $jaccardsimilarity = 30;
        else if ($jaccardsimilarity < 26) $jaccardsimilarity = 40;
        else if ($jaccardsimilarity < 36) $jaccardsimilarity = 50;
        else if ($jaccardsimilarity < 46) $jaccardsimilarity = 60;
        else if ($jaccardsimilarity < 56) $jaccardsimilarity = 70;
        else if ($jaccardsimilarity < 66) $jaccardsimilarity = 80;
        else if ($jaccardsimilarity < 76) $jaccardsimilarity = 90;
        else $jaccardsimilarity = 100;
        print($jaccardsimilarity);

        print("<h2>Cosine</h2>");
        $cosinesimilarity = $cosine->prosesCosine($katakunci, $jawaban);
        print($cosinesimilarity);
        print("% <br>");

        if($cosinesimilarity < 2) $cosinesimilarity = 10;
        else if($cosinesimilarity < 12)  $cosinesimilarity = 20;
        else if($cosinesimilarity < 22)  $cosinesimilarity = 30;
        else if($cosinesimilarity < 32)  $cosinesimilarity = 40;
        else if($cosinesimilarity < 42)  $cosinesimilarity = 50;
        else if($cosinesimilarity < 52)  $cosinesimilarity = 60;
        else if($cosinesimilarity < 62)  $cosinesimilarity = 70;
        else if($cosinesimilarity < 72)  $cosinesimilarity = 80;
        else if($cosinesimilarity < 82)  $cosinesimilarity = 90;
        else $cosinesimilarity = 100;

        print($cosinesimilarity);





        // print("<h2>Dice</h2>");
        // $dicesimilarity = $dice->prosesDice($katakunci, $jawaban);
        // print($dicesimilarity);
        // print("%");

        return view('welcome');
        // // return view('kriteria',['kriteria' => $kriteria]);
    }
}

?>
