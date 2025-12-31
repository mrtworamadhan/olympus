<!DOCTYPE html>
<html>
<head>
    <title>Match Report</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 20px; text-transform: uppercase; }
        .header p { margin: 2px 0; font-size: 10px; color: #555; }
        
        .score-board { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .score-board td { text-align: center; vertical-align: middle; padding: 10px; }
        .team-name { font-size: 16px; font-weight: bold; width: 40%; }
        .score { font-size: 30px; font-weight: bold; width: 20%; }
        .meta { font-size: 10px; color: #555; }

        .section-title { background-color: #eee; padding: 5px; font-weight: bold; text-transform: uppercase; font-size: 11px; margin-bottom: 10px; border-left: 4px solid #333; }

        .events-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 11px; }
        .events-table th { background-color: #f8f8f8; text-align: left; padding: 5px; border-bottom: 1px solid #ddd; }
        .events-table td { padding: 5px; border-bottom: 1px solid #eee; }

        .players-container { width: 100%; overflow: hidden; }
        .team-column { width: 48%; float: left; margin-right: 2%; }
        .team-column:last-child { margin-right: 0; }
        
        .player-list { width: 100%; border-collapse: collapse; font-size: 10px; }
        .player-list th { border-bottom: 1px solid #333; text-align: left; padding: 3px; }
        .player-list td { border-bottom: 1px solid #eee; padding: 3px; }

        .signatures {
            margin-top: 50px;
            width: 100%;
        }

        .sig-table {
            width: 100%;
            border-collapse: collapse;
        }

        .sig-table td {
            width: 33.33%;
            text-align: center;
            vertical-align: top;
            padding: 0 10px;
        }

        .sig-line {
            border-top: 1px solid #000;
            margin-top: 40px;
        }

        .sig-label {
            font-size: 10px;
            font-weight: bold;
            margin-top: 5px;
        }


        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .badge { padding: 2px 4px; border-radius: 3px; color: white; font-size: 9px; font-weight: bold; }
        .bg-goal { background-color: #10b981; color: black; }
        .bg-yellow { background-color: #fbbf24; color: black; }
        .bg-red { background-color: #ef4444; }
        .qr-container { position: absolute; top: 0; right: 0; text-align: center; }
        .qr-box { display: inline-block; margin-left: 10px; }
        .qr-label { font-size: 8px; text-transform: uppercase; margin-top: 2px; }
        .header { position: relative; height: 120px; }
    </style>
</head>
<body>

    <div class="header">
        <h1>OFFICIAL MATCH REPORT</h1>
        <p>{{ strtoupper($game->category->event->name ?? 'Turnamen') }} - {{ strtoupper($game->category->name) }}</p>
        <p>Date: {{ $game->scheduled_at->format('d F Y') }} | Time: {{ $game->scheduled_at->format('H:i') }} | Venue: {{ $game->venue->name ?? 'TBA' }}</p>
        <p>Match ID: #{{ $game->id }} | Group/Stage: {{ $game->meta_data['group_stage'] ?? '-' }}</p>
    </div>

    <table class="score-board">
        <tr>
            <td class="team-name text-right">{{ $game->homeCompetitor->name }}</td>
            <td class="score">
                {{ $game->home_score }} - {{ $game->away_score }}
                @if(isset($game->meta_data['penalty_home']))
                    <div style="font-size: 12px; font-weight: normal; margin-top: 5px;">
                        (Pen: {{ $game->meta_data['penalty_home'] }} - {{ $game->meta_data['penalty_away'] }})
                    </div>
                @endif
            </td>
            <td class="team-name text-left">{{ $game->awayCompetitor->name }}</td>
        </tr>
    </table>

    <div class="section-title">Match Events (Timeline)</div>
    <table class="events-table">
        <thead>
            <tr>
                <th width="10%">Min</th>
                <th width="15%">Team</th>
                <th width="40%">Player</th>
                <th width="35%">Event</th>
            </tr>
        </thead>
        <tbody>
            @forelse($game->events->sortBy('minute') as $event)
                <tr>
                    <td class="text-center">{{ $event->minute }}'</td>
                    <td>{{ $event->competitor_id == $game->home_competitor_id ? 'HOME' : 'AWAY' }}</td>
                    <td>
                        <strong>{{ $event->player->name ?? 'Unknown' }}</strong>
                        <span style="color: #666; font-size: 9px;">(#{{ $event->player->number ?? '-' }})</span>
                    </td>
                    <td>
                        @if($event->event_type == 'goal') <span class="badge bg-goal">GOAL</span>
                        @elseif($event->event_type == 'yellow_card') <span class="badge bg-yellow">YELLOW CARD</span>
                        @elseif($event->event_type == 'red_card') <span class="badge bg-red">RED CARD</span>
                        @elseif($event->event_type == 'foul') <span style="color: #666">Foul</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center" style="padding: 10px; color: #999;">No events recorded.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="section-title">Team Rosters</div>
    <div class="players-container">
        
        <div class="team-column">
            <h4 style="margin: 0 0 5px 0;">{{ $game->homeCompetitor->name }} (HOME)</h4>
            <table class="player-list">
                <thead>
                    <tr>
                        <th width="15%">#</th>
                        <th>Name</th>
                        <th width="20%">Pos</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($game->homeCompetitor->players->sortBy('number') as $player)
                    <tr>
                        <td>{{ $player->number }}</td>
                        <td>{{ $player->name }}</td>
                        <td>{{ $player->position }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="team-column">
            <h4 style="margin: 0 0 5px 0;">{{ $game->awayCompetitor->name }} (AWAY)</h4>
            <table class="player-list">
                <thead>
                    <tr>
                        <th width="15%">#</th>
                        <th>Name</th>
                        <th width="20%">Pos</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($game->awayCompetitor->players->sortBy('number') as $player)
                    <tr>
                        <td>{{ $player->number }}</td>
                        <td>{{ $player->name }}</td>
                        <td>{{ $player->position }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

    <div class="signatures">

        <table class="sig-table">
            <tr>
                <td>
                    <div class="sig-line"></div>
                    <div class="sig-label">
                        Coach: {{ $game->homeCompetitor->name }}<br>
                        <span style="font-weight: normal; font-size: 9px;">(Tanda Tangan)</span>
                    </div>
                </td>

                <td>
                    <div class="sig-line"></div>
                    <div class="sig-label">
                        {{ $game->referee->name ?? '.........................' }}<br>
                        <span style="font-weight: normal; font-size: 9px;">Match Referee</span>
                    </div>
                </td>

                <td>
                    <div class="sig-line"></div>
                    <div class="sig-label">
                        Coach: {{ $game->awayCompetitor->name }}<br>
                        <span style="font-weight: normal; font-size: 9px;">(Tanda Tangan)</span>
                    </div>
                </td>
            </tr>
        </table>

        <div style="margin-top: 30px; width: 100%; text-align: right; font-size: 9px; color: #777;">
            Recorded by: {{ $game->operator->name ?? 'System Admin' }}
        </div>
    </div>


</body>
</html>