<?php

namespace App\Http\Controllers\Test;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Exception\HttpResponseException;

class TestController extends Controller {
    public function unruly(Request $request) {
        $unruly = new \App\Data\Source\Unruly([
                'username' => $_ENV['UNRULY_USERNAME'],
                'password' => $_ENV['UNRULY_PASSWORD']
            ]);
        $res = $unruly->login();
        $res = $unruly->report([
                'start' => '2017-02-01',
                'end' => '2017-03-01'
            ]);

        print_r($res);
        exit;
    }

    public function undertone(Request $request) {
        $undertone = new \App\Data\Source\Undertone([
                'username' => $_ENV['UNDERTONE_USERNAME'],
                'password' => $_ENV['UNDERTONE_PASSWORD']
            ]);
        $res = $undertone->login();
        $res = $undertone->orders([
                'start' => '2017-03-01',
                'end' => '2017-03-01'
            ]);

        print_r($res);
        exit;

    }

    public function operamedianetworks(Request $request) {

        $operamedianetworks = new \App\Data\Source\OperaMediaNetworks([
                'username' => $_ENV['OPERAMEDIANETWORKS_USERNAME'],
                'password' => $_ENV['OPERAMEDIANETWORKS_PASSWORD']
            ]);
        $res = $operamedianetworks->login();
        $res = $operamedianetworks->report([
                'start' => '2017-02-15',
                'end' => '2017-02-17'
            ]);

        print_r($res);
        exit;
    }

    public function criteo(Request $request) {

        $criteo = new \App\Data\Source\Criteo([
                'token' => $_ENV['CRITEO_TOKEN']
            ]);
        $res = $criteo->report([
                'start' => '2017-02-15',
                'end' => '2017-02-17'
            ]);

        print_r($res);
        exit;
    }

    public function adyoulike(Request $request) {

        $adyoulike = new \App\Data\Source\AdYouLike([
                'username' => $_ENV['ADYOULIKE_USERNAME'],
                'password' => $_ENV['ADYOULIKE_PASSWORD']
            ]);
        $res = $adyoulike->login();
        $res = $adyoulike->report([
                'start' => '2017-02-15',
                'end' => '2017-02-17'
            ]);

        print_r($res);
        exit;

    }

    public function triplelift(Request $request) {
        $triplelift = new \App\Data\Source\TripleLift([
                'username' => $_ENV['TRIPLELIFT_USERNAME'],
                'password' => $_ENV['TRIPLELIFT_PASSWORD']
            ]);
        $res = $triplelift->login();
        $res = $triplelift->report([
                'start' => '2017-02-15',
                'end' => '2017-02-17',
                'publisher' => '575'
            ]);

        print_r($res);
    }

    public function swoop(Request $request) {

        $swoop = new \App\Data\Source\Swoop([
                'username' => $_ENV['SWOOP_USERNAME'],
                'password' => $_ENV['SWOOP_PASSWORD']
            ]);
        $res = $swoop->login();
        $res = $swoop->report([
                'start' => '2017-02-15',
                'end' => '2017-02-21'
            ]);

        print_r($res);
    }

    public function connatix(Request $request) {

        $connatix = new \App\Data\Source\Connatix([
                'username' => $_ENV['CONNATIX_USERNAME'],
                'password' => $_ENV['CONNATIX_PASSWORD']
            ]);
        $res = $connatix->login();
        $res = $connatix->report([
                'start' => '2017-02-15',
                'end' => '2017-02-21'
            ]);

        print_r($res);

    }

    public function adsnative(Request $request) {

        $adsnative = new \App\Data\Source\AdsNative([
                'username' => $_ENV['ADSNATIVE_USERNAME'],
                'password' => $_ENV['ADSNATIVE_PASSWORD']
            ]);
        $res = $adsnative->login();
        $res = $adsnative->report([
                'start' => '2017-02-15',
                'end' => '2017-02-21'
            ]);

        print_r($res);


    }

    public function rubicon(Request $request) {
        $rubicon = new \App\Data\Source\Rubicon([
                'username' => $_ENV['RUBICON_USERNAME'],
                'password' => $_ENV['RUBICON_PASSWORD']
            ]);

        $res = $rubicon->login();
        $res = $rubicon->report([
                'start' => '2017-02-15',
                'end' => '2017-02-15'
            ]);
        print_r($res);
        exit;

    }

    public function taboola(Request $request) {

        $taboola = new \App\Data\Source\Taboola([
                'username' => $_ENV['TABOOLA_USERNAME'],
                'password' => $_ENV['TABOOLA_PASSWORD']
            ]);

        $res = $taboola->login();
        var_dump($res);
        exit;

        $res = $appnexus->report([
            'start' => '2017-02-15',
            'end' => '2017-02-21'
        ]);
        print_r($res);

    }

    public function sovrn(Request $request) {
        $sovrn = new \App\Data\Source\Sovrn([
                'username' => $_ENV['SOVRN_USERNAME'],
                'password' => $_ENV['SOVRN_PASSWORD']
            ]);
        $res = $sovrn->login();
        // $res = $sovrn->harvestSovrn([
        //     'start' => '03/12/2017',
        //     'end' => '03/13/2017'
        // ]);
        // print_r($res);
        $result = $sovrn->setup([
            'start' => '03/14/2017',
            'end' => '03/15/2017',
            'source' => 2
        ]);
        print_r($result);


        // $res = $sovrn->earnings([
        //         // 'start' => '2017-02-15',
        //         // 'end' => '2017-02-21',
        //         'start' => '2017-03-11',
        //         'end' => '2017-03-12',
        //         'site' => $sovrn->websites()[0]->site
        //     ]);
        // print_r($res);

        // $res = $sovrn->overview([
        //         'start' => '2017-02-15',
        //         'end' => '2017-02-21',
        //         'site' => $sovrn->websites()[0]->site
        //     ]);
        // print_r($res);
    }

    public function mediabong(Request $request) {

        $mediabong = new \App\Data\Source\MediaBong([
                'username' => $_ENV['MEDIABONG_USERNAME'],
                'password' => $_ENV['MEDIABONG_PASSWORD']
            ]);
        $res = $mediabong->login();
            //$res = $mediabong->group();
            $res = $mediabong->ecpm([
                'start' => '2017-02-15',
                'end' => '2017-02-21'
            ]);
        return response()->json($res);
    }

}