<div style="text-align:center;background:#0461BE;color:white;padding:20px;">
    <h2>{{ count($tasks) }} Task(s) Due Today</h2>

    <div>{{ $today }}</div>
</div>

<br><br>

<table width="700" style="border-collapse:collapse">

    @foreach($tasks as $task)

        @php

            $color='#00E676';

            if($task['priority']=='High'){
                $color='#FF5252';
            }
            elseif($task['priority']=='Medium'){
                $color='#FFFF8D';
            }

        @endphp

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

        <tr>
            <td colspan="2" style="height:20px;"></td>
        </tr>

    @endforeach

</table>