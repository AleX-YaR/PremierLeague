@extends('../app')

@section('content')
    @if(session('errors'))
        {{ session('errors') }}
    @endif
    <table class="table table-striped table-condensed">
        <tbody>
        <tr>
            <td width="70%">
                <table class="table table-striped table-condensed">
                    <thead>
                    <th width="50%" class="text-center"><strong>League Table</strong></th>
                    <th width="50%" class="text-center"><strong>Match Results</strong></th>
                    </thead>
                    <tbody>
                    <tr>
                        <td>
                            @include('game.leagueTable')
                        </td>
                        <td>
                            @includeWhen(count($games), 'game.matchResults')
                        </td>
                    </tr>
                    <tr>
                        <td>
                            @if($currentWeek < 6)
                                <a class="btn btn-secondary" href="{{ route('playAll') }}">Play All</a>
                            @endif
                        </td>
                        <td class="text-right">
                            @if($currentWeek < 6)
                                <form action="{{ route('nextWeek') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="week" value="{{ $currentWeek + 1 }}">
                                    <div>
                                        <input class="btn btn-secondary" type="submit"
                                               value="{{ $currentWeek > 0 ? 'Next Week' : 'Start' }}">
                                    </div>
                                </form>
                            @else
                                <form action="{{ route('reset') }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <input class="btn btn-secondary" type="submit" value="Reset">
                                </form>
                            @endif
                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
            <td width="30%">
                @includeWhen($teamsPredictions, 'game.predictions')
            </td>
        </tr>
        </tbody>
    </table>
@endsection
