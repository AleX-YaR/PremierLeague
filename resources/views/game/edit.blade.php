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
                            @include('game.editMatchResults')
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
