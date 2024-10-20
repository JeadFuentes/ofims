<?php

use Livewire\Volt\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public $sortBy = 'id';
    public $sortDirection = 'asc';
    public $perPage = 5;
    public $search = '';
    public $results =[];

    #[On('reload')]
    public function with(): array{
        $this->results = [];
            $alarms = DB::table('devices')->join('triger','devices.id','=','triger.device_id')
                ->join('users','users.id','=','triger.user_id')
                ->select('triger.id as id','devices.dev_name as device_name', 'users.name as responder',
                'triger.updated_at as res_time', 'triger.created_at as alarm_time')
                ->where('device_id', 'like', '%'.$this->search.'%')
                ->orWhere('dev_name', 'like', '%'.$this->search.'%')
                ->orWhere('name', 'like', '%'.$this->search.'%')
                ->orderBy($this->sortBy, $this->sortDirection)
                ->get();
            foreach ($alarms as $alarm) {
            $this->results [] =[
                'id' => $alarm->id,
                'device_name' => $alarm->device_name,
                'responder' => $alarm->responder,
                'res_time' => $alarm->res_time,
                'alarm_time' => $alarm->alarm_time,
            ];
        }
            return [
                'devices' => $this->results,
        ];
    }

    public function sortingBy($field){
        if ($this->sortDirection == 'asc'){
            $this->sortDirection = 'desc';
        }
        else{
            $this->sortDirection = 'asc';
        }

        $this->dispatch('reload');
        return $this->sortBy = $field;
    }

    public function updatingSearch(){
      $this->resetPage();
    }

    public function openMaps($id){
        $this->redirect(route('user.maps', $id));
    }
}; ?>

<div>
    <div class="container-sm">
        <input id="searchTxt" class="form-control my-4" type="text" placeholder="search">
        <!--<button type="button" class="btn btn-primary ml-3 mt-3 mb-2">New Device</button>-->
        <table class="ml-3 mt-4 table table-striped table-hover" style="width: 100%">
            <thead class="text-center">
              <tr>
                <th style="cursor: pointer" wire:click="sortingBy('id')" scope="col">#</th>
                <th style="cursor: pointer" wire:click="sortingBy('device_name')" scope="col">Device name</th>
                <th style="cursor: pointer" wire:click="sortingBy('responder')" scope="col">Responder</th>
                <th style="cursor: pointer" wire:click="sortingBy('res_time')" scope="col">Response Time</th>
                <th style="cursor: pointer" wire:click="sortingBy('alarm_time')" scope="col">Alarm Time</th>
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
@script
    <script>
    $(document).ready(function(){
      $('#searchTxt').on('keyup',function(){
        @this.search = $(this).val();
        @this.call('with');
      })
    });
    </script>
@endscript
