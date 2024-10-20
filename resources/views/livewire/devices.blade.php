<?php

use Livewire\Volt\Component;
use App\Models\Devices;
use App\Models\User;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public $results =[];
    public $users =[];
    public $devices = [];
    public $alarms = [];

    public $id = '';
    public $userid = '';
    public $dev_name = '';
    public $dev_serial = '';
    public $dev_address = '';
    public $dev_number = '';

    public $sortBy = 'id';
    public $sortDirection = 'asc';
    public $perPage = 5;
    public $search = '';

    #[On('reload')]
    public function with(): array{
        if (Auth::user()->usertype == 'Admin'){
            $this->results = [];
            $dev = DB::table('users')->join('devices','users.id','=','devices.userid')
                ->select('devices.id as id', 'users.name as owner', 'devices.dev_name as name', 'devices.dev_serial as serial',
                'devices.dev_address as address', 'devices.dev_number as number')
                ->where('userid', 'like', '%'.$this->search.'%')
                ->orWhere('dev_name', 'like', '%'.$this->search.'%')
                ->orWhere('name', 'like', '%'.$this->search.'%')
                ->orWhere('dev_serial', 'like', '%'.$this->search.'%')
                ->orWhere('dev_address', 'like', '%'.$this->search.'%')
                ->orWhere('dev_number', 'like', '%'.$this->search.'%')
                ->orderBy($this->sortBy, $this->sortDirection)->get();

            foreach ($dev as $dev) {
                $this->results [] =[
                'id' => $dev->id,
                'owner' => $dev->owner,
                'name' => $dev->name,
                'serial' => $dev->serial,
                'address' => $dev->address,
                'number' => $dev->number,
            ];
            }
            return [
                'devices' => $this->results,
        ];
        }
        else{
            $this->results = [];
            $dev = DB::table('users')->join('devices','users.id','=','devices.userid')
                ->select('devices.id as id', 'users.name as owner', 'devices.dev_name as name', 'devices.dev_serial as serial',
                'devices.dev_address as address', 'devices.dev_number as number')->where('devices.userid',Auth::user()->id)
                ->where('userid', 'like', '%'.$this->search.'%')
                ->orWhere('dev_name', 'like', '%'.$this->search.'%')
                ->orWhere('name', 'like', '%'.$this->search.'%')
                ->orWhere('dev_serial', 'like', '%'.$this->search.'%')
                ->orWhere('dev_address', 'like', '%'.$this->search.'%')
                ->orWhere('dev_number', 'like', '%'.$this->search.'%')
                ->orderBy($this->sortBy, $this->sortDirection)->get();

                foreach ($dev as $dev) {
                $this->results [] =[
                'id' => $dev->id,
                'owner' => $dev->owner,
                'name' => $dev->name,
                'serial' => $dev->serial,
                'address' => $dev->address,
                'number' => $dev->number,
            ];
            }
           return [
                'devices' => $this->results,
            ];
        }
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

    //new devices
    public function openNew(){
        $users = DB::table('users')->where('usertype', '=', 'Owner')->get();
        foreach ($users as $user) {
            $this->users [] = [
                'id' => $user->id,
                'name' => $user->name,
            ];
        }
        $this->dispatch('openAddNewModal');
    }

    public function newDevice(){
        $validated = $this->validate([
            'userid' => ['required', 'max:255'],
            'dev_name' => ['required', 'string', 'max:255'],
            'dev_serial' => ['required', 'string', 'max:255'],
            'dev_address' => ['required', 'string', 'max:255'],
            'dev_number' => ['required', 'max:255'],
        ]);

        Devices::create($validated);

        session()->flash('message', 'Added Succesfully');
        $this->redirect(route('user.devices'));
    }
    //edit device
    public function openEdit($id){
        $this->devices = Devices::find($id);
        $users = DB::table('users')->where('usertype', '=', 'Owner')->get();
        foreach ($users as $user) {
            $this->users [] = [
                'id' => $user->id,
                'name' => $user->name,
            ];
        }
        $this->id = $this->devices->id;
        $this->userid = $this->devices->userid;
        $this->dev_name = $this->devices->dev_name;
        $this->dev_serial = $this->devices->dev_serial;
        $this->dev_address = $this->devices->dev_address;
        $this->dev_number = $this->devices->dev_number;
        $this->dispatch('showEditModal');
    }

    public function editDevices(){
        $devices = Devices::find($this->id);

        $validated = $this->validate([
            'userid' => ['required', 'max:255'],
            'dev_name' => ['required', 'string', 'max:255'],
            'dev_serial' => ['required', 'string', 'max:255'],
            'dev_address' => ['required', 'string', 'max:255'],
            'dev_number' => ['required', 'max:255'],
        ]);

        $devices->fill($validated);

        $devices->save();

        session()->flash('message', 'Added Succesfully');
        $this->redirect(route('user.devices'));
    }

//delete
    public function openDelete($id){
        $this->id = $id;

        $this->dispatch('showDeleteModal');
    }

    public function deleteModal()
    {
        $devices = Devices::find($this->id);

        $devices->delete();
        session()->flash('message', 'Deleted Succesfully');
        $this->redirect(route('user.devices'));
    }

    //triger
    public function openTriger($id){
        $this->alarms = [];
        $alarms = DB::table('devices')->join('triger','devices.id','=','triger.device_id')
        ->join('users','users.id','=','triger.user_id')
        ->select('users.name as responder','triger.updated_at as res_time', 'triger.created_at as alarm_time')
        ->where('devices.id', '=', $id)->get();

        foreach ($alarms as $alarm) {
            $this->alarms [] =[
                'responder' => $alarm->responder,
                'res_time' => $alarm->res_time,
                'alarm_time' => $alarm->alarm_time,
            ];
        }
        $this->dispatch('showTrigerModal');
    }
}; ?>

<div>
    <div class="container-sm">
        @if (Auth::user()->usertype == 'Admin')
        <div class="container my-4">
            <div class="row">
              <div class="col-sm">
                <button wire:click="openNew()" type="button" class="btn btn-primary w-50">New Device</button>
              </div>
              <div class="col-sm">
                <input id="searchTxt" class="form-control" type="text" placeholder="search">
              </div>
            </div>
        </div>
            
        @else
            <div class="ml-3 mt-3"></div>
        @endif
        <table class="ml-3 table table-striped table-hover" style="width: 100%">
            <thead class="text-center">
              <tr>
                <th style="cursor: pointer" wire:click="sortingBy('id')" scope="col">#</th>
                <th style="cursor: pointer" wire:click="sortingBy('owner')" scope="col">Owner</th>
                <th style="cursor: pointer" wire:click="sortingBy('name')" scope="col">Name</th>
                <th style="cursor: pointer" wire:click="sortingBy('serial')" scope="col">Serial</th>
                <th style="cursor: pointer" wire:click="sortingBy('name')" scope="col">Number</th>
                <th style="cursor: pointer" wire:click="sortingBy('action')" scope="col">Action</th>
              </tr>
            </thead>
            <tbody class="table-group-divider text-center">
                @foreach ($this->results as $res)
                    <tr>
                        <th scope="row">{{$res['id']}}</th>
                        <td>{{$res['owner']}}</td>
                        <td>{{$res['name']}}</td>
                        <td>{{$res['serial']}}</td>
                        <td>{{$res['number']}}</td>
                        <td>
                            <button wire:click="openEdit({{$res['id']}})" type="button" class="btn btn-sm btn-success">Edit</button>
                            <button wire:click="openTriger({{$res['id']}})" type="button" class="btn btn-sm btn-success">Trigger List</button>
                            <button wire:click="openDelete({{$res['id']}})" type="button" class="btn btn-sm btn-danger">Delete</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
          </table>
        </div>
        <!-- modals-->
        <!-- Add New -->
        <div class="modal fade" id="addNewModal" tabindex="-1" aria-labelledby="newModalLabel" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                <h1 class="modal-title fs-5" id="newModalLabel">ADD NEW DEVICE</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit="newDevice" class="space-y-6">
                        <div>
                            <x-input-label for="userid" :value="__('Owner')" />
                            <div class="block mt-1 w-full">
                                <select wire:model="userid" class="form-control">
                                    <option value="" selected>Select the Owner</option>
                                        @foreach ($this->users as $user)
                                            <option value="{{$user['id']}}">{{$user['name']}}</option>
                                        @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('department')" class="mt-2" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="dev_name" :value="__('Name')" />
                            <x-text-input wire:model="dev_name" id="dev_name" name="dev_name" type="text" class="mt-1 block w-full" required autofocus autocomplete="dev_name" />
                            <x-input-error class="mt-2" :messages="$errors->get('dev_name')" />
                        </div>

                        <div>
                            <x-input-label for="dev_serial" :value="__('Serial')" />
                            <x-text-input wire:model="dev_serial" id="dev_serial" name="dev_serial" type="text" class="mt-1 block w-full" required autocomplete="dev_serial" />
                            <x-input-error class="mt-2" :messages="$errors->get('dev_serial')" />
                        </div>

                        <div>
                            <x-input-label for="dev_address" :value="__('Address')" />
                            <x-text-input wire:model="dev_address" id="dev_address" name="dev_address" type="text" class="mt-1 block w-full" required autocomplete="dev_address" />
                            <x-input-error class="mt-2" :messages="$errors->get('dev_address')" />
                        </div>

                        <div>
                            <x-input-label for="dev_number" :value="__('Number')" />
                            <x-text-input wire:model="dev_number" id="dev_number" name="dev_number" type="number" class="mt-1 block w-full" required autocomplete="dev_number" />
                            <x-input-error class="mt-2" :messages="$errors->get('dev_number')" />
                        </div>
                
                        <div class="mt-6 flex justify-end">
                            <x-secondary-button x-on:click="$dispatch('close')">
                                {{ __('Cancel') }}
                            </x-secondary-button>
                
                            <div class="flex items-center gap-4">
                                <x-primary-button>{{ __('Save') }}</x-primary-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            </div>
        </div>
        <!-- Edit -->
   <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-danger">
          <h1 class="modal-title fs-5" id="editModalLabel">EDIT DEVICES</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form wire:submit="editDevices" class="space-y-6">
                <div>
                    <x-input-label for="userid" :value="__('Owner')" />
                    <div class="block mt-1 w-full">
                        <select wire:model="userid" class="form-control">
                            <option value="{{$this->userid}}" selected>Change if Apllicable</option>
                                @foreach ($this->users as $user)
                                    <option value="{{$user['id']}}">{{$user['name']}}</option>
                                @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('department')" class="mt-2" />
                    </div>
                </div>

                <div>
                    <x-input-label for="id" :value="__('Name')" />
                    <x-text-input wire:model="dev_name" id="dev_name" name="dev_name" type="text" class="mt-1 block w-full" required autofocus autocomplete="dev_name" />
                    <x-input-error class="mt-2" :messages="$errors->get('dev_name')" />
                </div>

                <div>
                    <x-input-label for="dev_serial" :value="__('Serial')" />
                    <x-text-input wire:model="dev_serial" id="dev_serial" name="dev_serial" type="text" class="mt-1 block w-full" required autocomplete="dev_serial" />
                    <x-input-error class="mt-2" :messages="$errors->get('dev_serial')" />
                </div>

                <div>
                    <x-input-label for="dev_address" :value="__('Address')" />
                    <x-text-input wire:model="dev_address" id="dev_address" name="dev_address" type="text" class="mt-1 block w-full" required autocomplete="dev_address" />
                    <x-input-error class="mt-2" :messages="$errors->get('dev_address')" />
                </div>

                <div>
                    <x-input-label for="dev_number" :value="__('Number')" />
                    <x-text-input wire:model="dev_number" id="dev_number" name="dev_number" type="number" class="mt-1 block w-full" required autocomplete="dev_number" />
                    <x-input-error class="mt-2" :messages="$errors->get('dev_number')" />
                </div>
        
                <div class="mt-6 flex justify-end">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        {{ __('Cancel') }}
                    </x-secondary-button>
        
                    <div class="flex items-center gap-4">
                        <x-primary-button>{{ __('Save') }}</x-primary-button>
                    </div>
                </div>
            </form>
        </div>
      </div>
    </div>
  </div>
  <!-- Delete -->
  <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-danger">
          <h1 class="modal-title fs-5" id="deleteModalLabel">DELETE DEVICES</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form wire:submit="deleteModal">

                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    {{ __('Are you sure you want to delete the Device?')}}
                </h2>
        
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    {{ __('Once the Device is deleted, all of its resources and data will be permanently deleted.') }}
                </p>
        
                <div class="mt-6 flex justify-end">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        {{ __('Cancel') }}
                    </x-secondary-button>
        
                    <x-danger-button class="ms-3">
                        {{ __('Delete Account') }}
                    </x-danger-button>
                </div>
            </form>
        </div>
      </div>
    </div>
  </div>

  <!-- triger list -->
  <div class="modal fade" id="trigerModal" tabindex="-1" aria-labelledby="trigerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-danger">
          <h1 class="modal-title fs-5" id="trigerModalLabel">DEVICES</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <table class="ml-3 table table-striped table-hover" style="width: 100%">
                <thead class="text-center">
                  <tr>
                    <th scope="col">Responder</th>
                    <th scope="col">Response Time</th>
                    <th scope="col">Alarm Time</th>
                  </tr>
                </thead>
                <tbody class="table-group-divider text-center">
                    @foreach ($this->alarms as $alarm)
                        <tr>
                            <th scope="row">{{$alarm['responder']}}</th>
                            <td>{{$alarm['res_time']}}</td>
                            <td>{{$alarm['alarm_time']}}</td>
                        </tr>
                    @endforeach
                </tbody>
              </table>
        </div>
      </div>
    </div>
  </div>
    <!-- end of div -->
</div>
@script
 <script>
    $(document).ready(function(){
      $('#searchTxt').on('keyup',function(){
        @this.search = $(this).val();
        @this.call('with');
      })
    });

    $wire.on('openAddNewModal', () => {
      $('#addNewModal').modal('show');
    });
    $wire.on('showEditModal', () => {
      $('#editModal').modal('show');
    });
    $wire.on('showDeleteModal', () => {
      $('#deleteModal').modal('show');
    });
    $wire.on('showTrigerModal', () => {
      $('#trigerModal').modal('show');
    });
    $wire.on('close', () => {
      $('#deleteModal').modal('hide');
      $('#editModal').modal('hide');
      $('#addNewModal').modal('hide');
    });

 </script>
@endscript