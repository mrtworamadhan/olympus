<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\GameMatch;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class MatchReportController extends Controller
{
    public function download(GameMatch $game)
    {
        $game->load([
            'homeCompetitor.players', 
            'awayCompetitor.players', 
            'venue', 
            'category', 
            'events.player',
            'referee', 
            'operator'
        ]);

        $operatorUrl = route('match.operator', $game->id);
        $qrOperator = base64_encode(QrCode::format('svg')->size(100)->generate($operatorUrl));

        $publicUrl = route('public.match', $game->id);
        $qrPublic = base64_encode(QrCode::format('svg')->size(100)->generate($publicUrl));

        $data = [
            'game' => $game,
            'title' => 'Match Report - ' . $game->homeCompetitor->name . ' vs ' . $game->awayCompetitor->name,
            'qrOperator' => $qrOperator,
            'qrPublic' => $qrPublic,
        ];

        $pdf = Pdf::loadView('pdf.match-report', $data);

        $pdf->setPaper('a4', 'portrait');

        return $pdf->stream('Match-Report-'.$game->id.'.pdf'); 
    }
}