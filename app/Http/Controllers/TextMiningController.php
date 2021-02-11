<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Classes\TextMining;
use App\Classes\NaziefAndriani;
use App\Classes\CosineSimilarity;
use App\Classes\JaccardSimilarity;
use App\Classes\DiceSimilarity;


class TextMiningController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // $mining = new TextMining();
        // // $stemming = new NaziefAndriani();
        // $jaccard = new JaccardSimilarity();
        // $cosine = new CosineSimilarity();
        // // $dice = new DiceSimilarity();

        // $katakunci = "Sistem penilAian? adalah ujian eSsay online essay, ada berbasis. Website";
        // print("<h2>Kata Kunci</h2>");
        // print_r($katakunci);
        // print("<br/>");

        // $katakunci = $mining->textMining($katakunci);
        // print_r($katakunci);
        // print("<br/>");


        // $jawaban = "penilAian proses adalah eSsay online essay, ada berbasis. Website indonesia";
        // print("<h2>Jawaban</h2>");
        // print_r($jawaban);
        // print("<br/>");

        // $jawaban = $mining->textMining($jawaban);
        // print_r($jawaban);
        // print("<br/>");

        // print("<h2>Jaccard</h2>");
        // $jaccardsimilarity = $jaccard->prosesJaccard($katakunci, $jawaban);
        // print($jaccardsimilarity);
        // print("%");

        // print("<h2>Cosine</h2>");
        // $cosinesimilarity = $cosine->prosesCosine($katakunci, $jawaban);
        // print($cosinesimilarity);
        // print("%");

        // // print("<h2>Dice</h2>");
        // // $dicesimilarity = $dice->prosesDice($katakunci, $jawaban);
        // // print($dicesimilarity);
        // // print("%");

        return view('welcome');
        // // return view('kriteria',['kriteria' => $kriteria]);
    }
}

?>
