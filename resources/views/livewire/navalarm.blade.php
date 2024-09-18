<?php

use Livewire\Volt\Component;

new class extends Component {
    public $results;

    public function mount(){
        $alarms = DB::table('devices')->join('triger','devices.id','=','triger.device_id')
        ->select('triger.id as id','devices.dev_name as device_name',
        'triger.updated_at as res_time', 'triger.created_at as alarm_time', 'triger.user_id as user')->get();

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
}; ?>

<div>
    @if ($this->results)
        <button class="btn btn-danger" type="button"  data-toggle="modal" data-target="#form">
            <i class="fa-regular fa-bell"> {{count($this->results)}}</i> alert!
        </button>
    @else
        <button class="btn btn-outline-danger" type="button"  data-toggle="modal" data-target="#form">
            <i class="fa-regular fa-bell"></i>
        </button>
     @endif
</div>
