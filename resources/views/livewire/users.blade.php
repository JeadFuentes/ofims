<?php

use Illuminate\Validation\Rule;
use Livewire\Volt\Component;
use Livewire\Attributes\On;
use App\Models\User;

new class extends Component {
    public $users = [];
    public $results = [];

    public $name = '';
    public $address = '';
    public $number = '';
    public $usertype = '';
    public $email = '';
    public $id = '';

    public function mount(){
        $users = User::all();

        foreach ($users as $user) {
            $this->results [] = [
            'id' => $user->id,
            'name' => $user->name,
            'address' => $user->address,
            'number' => $user->number,
            'usertype' => $user->usertype,
            'email' => $user->email,
        ];
        }   
    }
    //edit
    public function openEdit($id){
        $this->users = User::find($id);

        $this->name = $this->users->name;
        $this->address = $this->users->address;
        $this->number= $this->users->number;
        $this->usertype = $this->users->usertype;
        $this->email = $this->users->email;

        $this->dispatch('showEditModal');
    }

    public function updateUser(): void
    {
        $user = User::find($this->users->id);

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['string', 'max:255'],
            'number' => ['required'],
            'usertype' => ['required'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
        ]);

        $user->fill($validated);

        $user->save();

        session()->flash('message', 'Saved Succesfully');
        redirect()->to('/user');
    }
    //delete
    public function openDelete($id){
        $this->id = $id;

        $this->dispatch('showDeleteModal');
    }

    public function deleteModal()
    {
        $user = User::find($this->id);

        $user->delete();
        session()->flash('message', 'Deletes Succesfully');
        $this->redirect(route('user.user'));
    }
}; ?>

<div>
    <div class="container-sm">
        <button type="button" class="btn btn-primary ml-0 mt-3 mb-2" data-bs-toggle="modal" data-bs-target="#usermodal">New user</button>
        <x-user-modal>
        </x-user-modal>
        <table class="ml-3 table table-striped table-hover" style="width: 100%">
            <thead class="text-center">
              <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Address</th>
                <th scope="col">Number</th>
                <th scope="col">Usertype</th>
                <th scope="col">Username</th>
                <th scope="col">Action</th>
              </tr>
            </thead>
            <tbody class="table-group-divider text-center">
                @foreach ($this->results as $user)
                <tr>
                    <th scope="row">{{$user['id']}}</th>
                    <td>{{$user['name']}}</td>
                    <td>{{$user['address']}}</td>
                    <td>{{$user['number']}}</td>
                    <td>{{$user['usertype']}}</td>
                    <td>{{$user['email']}}</td>
                    <td>
                        <button wire:click="openEdit({{$user['id']}})" type="button" class="btn btn-sm btn-success">Edit</button>
                        <button wire:click="openDelete({{$user['id']}})" type="button" class="btn btn-sm btn-danger">Delete</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
          </table>
        </div>
          <!--modals-->  
  <!--edit modal-->
  <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-danger">
          <h1 class="modal-title fs-5" id="editModalLabel">EDIT USER</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form wire:submit="updateUser">
                <div>
                    <x-input-label for="name" :value="__('Name')" />
                    <x-text-input wire:model="name" id="name" name="name" type="text" class="mt-1 block w-full" required autofocus autocomplete="name" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>
                <div>
                    <x-input-label for="address" :value="__('Address')" />
                    <x-text-input wire:model="address" id="address" name="address" type="text" class="mt-1 block w-full" required autofocus autocomplete="name" />
                    <x-input-error class="mt-2" :messages="$errors->get('address')" />
                </div>
                <div>
                    <x-input-label for="number" :value="__('Number')" />
                    <x-text-input wire:model="number" id="number" name="number" type="text" class="mt-1 block w-full" required autofocus autocomplete="name" />
                    <x-input-error class="mt-2" :messages="$errors->get('number')" />
                </div>
                <div>
                    <x-input-label for="usertype" :value="__('Usertype')" />
                    <select wire:model="usertype" id="usertype" class="rounded-md select block mt-1 w-full" name="usertype">
                        <option value="">Please Select User Type</option>
                        <option value="Owner">Owner</option>
                        <option value="Fireman">Fireman</option>
                        <option value="Admin">Admin</option>
                    </select>
                    <x-input-error :messages="$errors->get('usertype')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input wire:model="email" id="email" class="block mt-1 w-full" type="email" name="email" required autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
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
    <!--delete modal-->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger">
            <h1 class="modal-title fs-5" id="deleteModalLabel">DELETE USER</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form wire:submit="deleteModal">

                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-500">
                        {{ __('Are you sure you want to delete this User?')}}
                    </h2>
            
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        {{ __('Once the User is deleted, all of its resources and data will be permanently deleted.') }}
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

    <!--end of div-->
</div>
@script
 <script>
    $wire.on('showEditModal', () => {
      $('#editModal').modal('show');
    });
    $wire.on('showDeleteModal', () => {
      $('#deleteModal').modal('show');
    });
    $wire.on('close', () => {
      $('#editModal').modal('hide');
      $('#deleteModal').modal('hide');
    });
 </script>
@endscript