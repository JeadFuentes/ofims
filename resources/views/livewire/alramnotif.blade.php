<?php

use Livewire\Volt\Component;

new class extends Component {
    public $results =[];

    public function mount(){
        $alarms = DB::table('devices')->join('triger','devices.id','=','triger.device_id')
        ->select('triger.id as id','devices.dev_name as device_name',
        'triger.updated_at as res_time', 'triger.created_at as alarm_time')->get();

        foreach ($alarms as $alarm) {
            $this->results [] =[
                'id' => $alarm->id,
                'device_name' => $alarm->device_name,
                'alarm_time' => $alarm->alarm_time,
            ];
        }
    }
}; ?>

<div>
    <h3>Alarm notifications</h3>
                <i class="fa fa-bell text-danger pb-2"></i>
                <table class="table">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Alarm Time</th>
                        <th scope="col">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach ($this->results as $res)
                        <tr>
                            <th scope="row">{{$res['id']}}</th>
                            <td>{{$res['device_name']}}</td>
                            <td>{{$res['alarm_time']}}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-danger">RESPOND</button>
                                <button type="button" class="btn btn-sm btn-success">VIEW MAP</button>
                            </td>
                          </tr>
                        @endforeach
                    </tbody>
                  </table>
</div>
