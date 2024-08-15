<?php

use Livewire\Volt\Component;
use App\Models\Devices;
use App\Models\User;

new class extends Component {
    public $results =[];
    public $users =[];
    public $devices = [];

    public $id = '';
    public $userid = '';
    public $dev_name = '';
    public $dev_serial = '';
    public $dev_address = '';
    public $dev_number = '';

    public function mount(){
        if (Auth::user()->usertype == 'Admin'){
            $devices = DB::table('users')->join('devices','users.id','=','devices.userid')
            ->select('devices.id as id', 'users.name as owner', 'devices.dev_name as name', 'devices.dev_serial as serial',
            'devices.dev_address as address', 'devices.dev_number as number')->get();

            foreach ($devices as $dev) {
                $this->results [] =[
                'id' => $dev->id,
                'owner' => $dev->owner,
                'name' => $dev->name,
                'serial' => $dev->serial,
                'address' => $dev->address,
                'number' => $dev->number,
            ];
            }
        }
        else{
            $devices = DB::table('users')->join('devices','users.id','=','devices.userid')
            ->select('devices.id as id', 'users.name as owner', 'devices.dev_name as name', 'devices.dev_serial as serial',
            'devices.dev_address as address', 'devices.dev_number as number')->where('devices.userid',Auth::user()->id)->get();

            foreach ($devices as $dev) {
                $this->results [] =[
                'id' => $dev->id,
                'owner' => $dev->owner,
                'name' => $dev->name,
                'serial' => $dev->serial,
                'address' => $dev->address,
                'number' => $dev->number,
            ];
            }
        }
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
}; ?>

<div>
    <div class="container-sm">
        <button wire:click="openNew()" type="button" class="btn btn-primary ml-3 mt-3 mb-2">New Device</button>
        <table class="ml-3 table table-striped table-hover" style="width: 100%">
            <thead class="text-center">
              <tr>
                <th scope="col">#</th>
                <th scope="col">Owner</th>
                <th scope="col">Name</th>
                <th scope="col">Serial</th>
                <th scope="col">Number</th>
                <th scope="col">Action</th>
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
                            <button wire:click="openEdit({{$res['id']}})" type="button" class="btn btn-sm btn-success">Trigger List</button>
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
    <!-- end of div -->
</div>
@script
 <script>
    $wire.on('openAddNewModal', () => {
      $('#addNewModal').modal('show');
    });
    $wire.on('showEditModal', () => {
      $('#editModal').modal('show');
    });
    $wire.on('showDeleteModal', () => {
      $('#deleteModal').modal('show');
    });
    $wire.on('close', () => {
      $('#deleteModal').modal('hide');
      $('#editModal').modal('hide');
      $('#addNewModal').modal('hide');
    });

 </script>
@endscript