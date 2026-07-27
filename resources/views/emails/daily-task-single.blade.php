@php
    $color = '#00E676';

    if($task['priority']=='High'){
        $color='#FF5252';
    }
    elseif($task['priority']=='Medium'){
        $color='#FFFF8D';
    }
@endphp

<div style="text-align:center;background:#0461BE;color:white;padding:20px;">
    <h2>Task Due Today</h2>
    <div>{{ $today }}</div>
</div>

<br><br>

<table width="600" style="border-collapse:collapse">

    <tr>
        <td style="background:{{ $color }};padding:15px;">
            <b>{{ $task['task_name'] }}</b>
            <br>
            {{ $task['reservation_name'] }}
            ({{ $task['customer_name'] }})
        </td>

        <td style="background:{{ $color }};padding:15px;text-align:right">
            {{ $task['priority'] }}
        </td>
    </tr>

</table>