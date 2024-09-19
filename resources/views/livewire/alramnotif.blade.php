<?php

use Livewire\Volt\Component;
use App\Models\Triger;

new class extends Component {
    public $results =[];

    public function mount(){
        $alarms = DB::table('triger')
        ->leftJoin('devices', 'devices.id', '=', 'triger.device_id')
        ->select(
            'triger.id as id',
            'devices.dev_name as device_name',
            'triger.updated_at as res_time',
            'triger.created_at as alarm_time',
            'triger.user_id as user'
        )->get();

        foreach ($alarms as $alarm) {
            if (! $alarm->user) {
                $this->results [] =[
                'id' => $alarm->id,
                'device_name' => $alarm->device_name,
                'alarm_time' => $alarm->alarm_time,
            ];
            }
        }
    }

    public function openMaps($id){
        $this->redirect(route('user.maps', $id));
    }

    public function respond($id){
        $triger = Triger::find($id);

        $status = [
          'user_id' => Auth::user()->id
        ];

        $triger->fill($status);

        $triger->save();

        session()->flash('message', 'Responded');
        $this->redirect(route('user.alarm'));
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
                                @if (Auth::user()->usertype == 'Fireman')
                                    <button wire:click="respond({{$res['id']}})" type="button" class="btn btn-sm btn-danger">RESPOND</button>
                                @endif
                                <button wire:click="openMaps({{$res['id']}})" type="button" class="btn btn-sm btn-success">VIEW MAP</button>
                            </td>
                          </tr>
                        @endforeach
                    </tbody>
                  </table>
</div>
