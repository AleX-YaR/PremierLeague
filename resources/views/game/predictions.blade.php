<table class="table table-striped table-condensed">
    <thead>
    <tr>
        <th colspan="2" class="text-center">
            <strong>{{ (new NumberFormatter('en_US', NumberFormatter::ORDINAL))->format($currentWeek) }} Week
                Prediction of Championship</strong></th>
    </tr>
    </thead>
    <tbody>
    @foreach($teamsPredictions as $teamsPrediction)
        <tr>
            <td>{{ $teamsPrediction['name'] }}</td>
            <td class="text-right">%{{ $teamsPrediction['prediction'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
