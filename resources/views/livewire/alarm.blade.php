<?php

use Livewire\Volt\Component;

new class extends Component {
    public $results =[];

    public function mount(){
        $alarms = DB::table('devices')->join('triger','devices.id','=','triger.device_id')
        ->join('users','users.id','=','triger.user_id')
        ->select('triger.id as id','devices.dev_name as device_name', 'users.name as responder',
        'triger.updated_at as res_time', 'triger.created_at as alarm_time')->get();

        foreach ($alarms as $alarm) {
            $this->results [] =[
                'id' => $alarm->id,
                'device_name' => $alarm->device_name,
                'responder' => $alarm->responder,
                'res_time' => $alarm->res_time,
                'alarm_time' => $alarm->alarm_time,
            ];
        }
    }

    public function openMaps($id){
        $this->redirect(route('user.maps', $id));
    }
}; ?>

<div>
    <div class="container-sm">
        <!--<button type="button" class="btn btn-primary ml-3 mt-3 mb-2">New Device</button>-->
        <table class="ml-3 mt-5 table table-striped table-hover" style="width: 100%">
            <thead class="text-center">
              <tr>
                <th scope="col">#</th>
                <th scope="col">Device name</th>
                <th scope="col">Responder</th>
                <th scope="col">Response Time</th>
                <th scope="col">Alarm Time</th>
                <th scope="col">Action</th>
              </tr>
            </thead>
            <tbody class="table-group-divider text-center">
                @foreach ($this->results as $res)
                    <tr>
                        <th scope="row">{{$res['id']}}</th>
                        <td>{{$res['device_name']}}</td>
                        <td>{{$res['responder']}}</td>
                        <td>{{$res['res_time']}}</td>
                        <td>{{$res['alarm_time']}}</td>
                        <td>
                            <button wire:click="openMaps({{$res['id']}})" type="button" class="btn btn-sm btn-success">VIEW MAP</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
          </table>
        </div>
</div>
