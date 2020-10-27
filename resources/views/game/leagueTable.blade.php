<table class="table table-striped table-condensed">
    <tbody>
    <thead>
    <th><strong>Teams</strong></th>
    <th><strong>PTS</strong></th>
    <th><strong>P</strong></th>
    <th><strong>W</strong></th>
    <th><strong>D</strong></th>
    <th><strong>L</strong></th>
    <th><strong>GD</strong></th>
    </thead>
    @foreach($teams as $team)
        <tr>
            <td>{{ $team->name }}</td>
            <td>{{ $team->getPoints() }}</td>
            <td>{{ $team->getPlayed() }}</td>
            <td>{{ $team->getWon() }}</td>
            <td>{{ $team->getDrawn() }}</td>
            <td>{{ $team->getLost() }}</td>
            <td>{{ $team->getGoalDifference() }}</td>
        </tr>
        @endforeach
        </tbody>
</table>
