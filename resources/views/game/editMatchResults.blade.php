<form action="{{ route('update') }}" method="post">
    @csrf
    @method('PUT')
    <table class="table table-striped table-condensed">
        @foreach($games as $week => $weekGames)
            <tr>
                <td colspan="3" class="text-center">
                    <strong>{{ (new NumberFormatter('en_US', NumberFormatter::ORDINAL))->format($week) }} Week Match
                        Result</strong></td>
            </tr>
            @foreach($weekGames as $weekGame)
                <tr>
                    <td width="30%">{{ $weekGame->homeTeam->name }}</td>
                    <td width="40%" class="text-center">
                        <input type="number" min="0" max="99"
                               name="games[{{ $weekGame->id }}][home_score]"
                               value="{{ $weekGame->home_score }}"> -
                        <input type="number" min="0" max="99"
                               name="games[{{ $weekGame->id }}][away_score]"
                               value="{{ $weekGame->away_score }}">
                    </td>
                    <td width="30%" class="text-right">{{ $weekGame->awayTeam->name }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="3" class="text-right"><input class="btn btn-secondary text-right" type="submit"
                                                          value="Update"></td>
            </tr>
        @endforeach
    </table>
</form>
