<table class="table table-striped table-condensed">
    @foreach($games as $week => $weekGames)
        <tr>
            <td colspan="3" class="text-center">
                <strong>{{ (new NumberFormatter('en_US', NumberFormatter::ORDINAL))->format($week) }} Week Match
                    Result</strong></td>
        </tr>
        @foreach($weekGames as $weekGame)
            <tr>
                <td width="40%">{{ $weekGame->homeTeam->name }}</td>
                <td width="20%" class="text-center">{{ $weekGame->home_score }} - {{ $weekGame->away_score }}</td>
                <td width="40%" class="text-right">{{ $weekGame->awayTeam->name }}</td>
            </tr>
        @endforeach
        @if(count($games) === 1)
            <tr>
                <td colspan="3" class="text-right">
                    <a class="btn btn-secondary" href="{{ route('edit', $week) }}">Edit</a>
            </tr>
        @endif
    @endforeach
</table>
