<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Classes\TextMining;
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
        // $dice = new DiceSimilarity();


        $data = Jawaban::join('soal', 'jawaban.id_soal', '=', 'soal.id_soal')->whereNotNull('jawaban.nilai_guru')->select('soal.stemming', 'jawaban.stemming_jawaban_siswa')->get();

        $avg_cosine = DB::select("SELECT AVG(nilai_cosine) as 'avg_cosine' FROM `jawaban` WHERE nilai_guru IS NOT NULL");
        $nilai_cosine = $avg_cosine[0]->avg_cosine;
        print("AVG Nilai_Cosine:  ". $nilai_cosine ."<br>");

        $avg_jaccard = DB::select("SELECT AVG(nilai_jaccard) as 'avg_jaccard' FROM `jawaban` WHERE nilai_guru IS NOT NULL");
        $nilai_jaccard = $avg_jaccard[0]->avg_jaccard;
        print("AVG Nilai_Jaccard:  ". $nilai_jaccard ."<br>");

        $avg_guru = DB::select("SELECT AVG(nilai_guru) as 'avg_guru' FROM `jawaban` WHERE nilai_guru IS NOT NULL");
        $nilai_guru = $avg_guru[0]->avg_guru;
        print("AVG Nilai_Guru:  ". $nilai_guru ."<br>");


        
        $precision = $mining->precision($nilai_cosine, $nilai_guru);
        $recall = $mining->recall($nilai_cosine, $nilai_guru);
        print("Precision: " .$precision ."<br>");
        print("recall: " .$recall ."<br>");

        
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
        print("<br>");
        $group_avg = DB::select("SELECT soal.id_detail, AVG(nilai_cosine) as 'avg_cosine', AVG(nilai_jaccard) as 'avg_jaccard', AVG(nilai_guru) as 'avg_guru' FROM jawaban 
            join soal on jawaban.id_soal = soal.id_soal
            WHERE nilai_guru IS NOT NULL
            Group by soal.id_detail");

        print_r($group_avg);
        print("<br><br>");

        foreach ($group_avg as $key => $value) {
            $mapel = DB::select("SELECT mapel.nama_mapel FROM detailmapel
                join mapel on detailmapel.id_mapel = mapel.id_mapel
                WHERE detailmapel.id_detail = $value->id_detail");
            print_r($mapel[0]->nama_mapel. "<br>");

            $precision = $mining->precision($value->avg_cosine, $value->avg_guru);
            $recall = $mining->recall($value->avg_cosine, $value->avg_guru);
            print("Cosine: ". $precision ." (precision) ". $recall ." (recall)");
            print("<br>");

            $precision = $mining->precision($value->avg_jaccard, $value->avg_guru);
            $recall = $mining->recall($value->avg_jaccard, $value->avg_guru);
            print("jaccard: ". $precision ." (precision) ". $recall ." (recall)");
            print("<br><br>");
        }




        $katakunci = "mencoba menjalankan fungsi text mining untuk penilaian jawaban";
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


        $jawaban = "mencoba-coba text mining";
        print("<h2>Jawaban</h2>");
        print_r($jawaban);
        print("<br/>");

        $jawaban = $mining->textMining($jawaban);
        print_r($jawaban);
        print("<br/>");

        print("<h2>Jaccard</h2>");
        $jaccardsimilarity = $jaccard->prosesJaccard($katakunci, $jawaban);
        print($jaccardsimilarity);
        print("%");

        print("<h2>Cosine</h2>");
        $cosinesimilarity = $cosine->prosesCosine($katakunci, $jawaban);
        print($cosinesimilarity);
        print("%");

        // print("<h2>Dice</h2>");
        // $dicesimilarity = $dice->prosesDice($katakunci, $jawaban);
        // print($dicesimilarity);
        // print("%");

        return view('welcome');
        // // return view('kriteria',['kriteria' => $kriteria]);
    }
}

?>
