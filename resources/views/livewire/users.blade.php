<?php

use Illuminate\Validation\Rule;
use Livewire\Volt\Component;
use Livewire\Attributes\On;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\User;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public $users = [];
    public $results = [];

    public $name = '';
    public $address = '';
    public $number = '';
    public $usertype = '';
    public $email = '';
    public $id = '';

    public $sortBy = 'id';
    public $sortDirection = 'asc';
    public $perPage = 10;
    public $search = '';

    //reset
    public string $password = '';
    public string $password_confirmation = '';

    #[On('reload')]
    public function with(): array{
        return [
            'userList' => User::search($this->search)->orderBy($this->sortBy, $this->sortDirection)->paginate($this->perPage),
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

    //reset password
    public function openResetPassword($id)
    {
        $this->id = $id;
        $this->dispatch('openResetPassword');
    }

    public function resetPassword()
    {
        $user = User::find($this->id);
        try {
            $validated = $this->validate([
                'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            $this->reset('password', 'password_confirmation');
            
            throw $e;
        }

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset('password', 'password_confirmation');

        session()->flash('message', 'Saved Succesfully');
        $this->redirect(route('user.user'));
    }
}; ?>

<div>
    <div class="container-sm">
        @if (Auth::user()->usertype == 'Admin')
        <div class="container my-4">
            <div class="row">
              <div class="col-sm">
                <button type="button" class="btn btn-primary w-50" data-bs-toggle="modal" data-bs-target="#usermodal">New user</button>
              </div>
              <div class="col-sm">
                <input id="searchTxt" class="form-control" type="text" placeholder="search">
              </div>
            </div>
        </div>
        @else
            <div class="ml-0 mt-3"></div>
        @endif
        <x-user-modal>
        </x-user-modal>
        <table class="ml-3 table table-striped table-hover" style="width: 100%">
            <thead class="text-center">
              <tr>
                <th style="cursor: pointer" wire:click="sortingBy('id')" scope="col">#</th>
                <th style="cursor: pointer" wire:click="sortingBy('name')" scope="col">Name</th>
                <th style="cursor: pointer" wire:click="sortingBy('address')" scope="col">Address</th>
                <th style="cursor: pointer" wire:click="sortingBy('number')" scope="col">Number</th>
                <th style="cursor: pointer" wire:click="sortingBy('usertype')" scope="col">Usertype</th>
                <th style="cursor: pointer" wire:click="sortingBy('email')" scope="col">Username</th>
                <th scope="col">Action</th>
              </tr>
            </thead>
            <tbody class="table-group-divider text-center">
                @if (Auth::user()->usertype == 'Admin')
                    @foreach ($userList as $user)
                    <tr>
                        <th scope="row">{{$user['id']}}</th>
                        <td>{{$user['name']}}</td>
                        <td>{{$user['address']}}</td>
                        <td>{{$user['number']}}</td>
                        <td>{{$user['usertype']}}</td>
                        <td>{{$user['email']}}</td>
                        <td>
                            <button wire:click="openEdit({{$user['id']}})" type="button" class="btn btn-sm btn-success">Edit</button>
                            <button wire:click="openResetPassword({{$user['id']}})" type="button" class="btn btn-sm btn-success">Reset Password</button>
                            <button wire:click="openDelete({{$user['id']}})" type="button" class="btn btn-sm btn-danger">Delete</button>
                        </td>
                    </tr>
                    @endforeach
                @else
                    @foreach ($userList as $user)
                        @if (Auth::user()->id == $user['id'])
                        <tr>
                            <th scope="row">{{$user['id']}}</th>
                            <td>{{$user['name']}}</td>
                            <td>{{$user['address']}}</td>
                            <td>{{$user['number']}}</td>
                            <td>{{$user['usertype']}}</td>
                            <td>{{$user['email']}}</td>
                            <td>
                                <button wire:click="openEdit({{$user['id']}})" type="button" class="btn btn-sm btn-success">Edit</button>
                                <button wire:click="openResetPassword({{$user['id']}})" type="button" class="btn btn-sm btn-success">Change Password</button>
                                <button wire:click="openDelete({{$user['id']}})" type="button" class="btn btn-sm btn-danger">Delete</button>
                            </td>
                        </tr>
                        @endif
                    @endforeach
                @endif
            </tbody>
          </table>
          <div class="text-white" style="color: white !important">
            {{$userList->links()}}
          </div>
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
                    <x-text-input wire:model="name" name="name" type="text" class="mt-1 block w-full" required autofocus autocomplete="name" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>
                <div>
                    <x-input-label for="address" :value="__('Address')" />
                    <x-text-input wire:model="address" name="address" type="text" class="mt-1 block w-full" required autofocus autocomplete="address" />
                    <x-input-error class="mt-2" :messages="$errors->get('address')" />
                </div>
                <div>
                    <x-input-label for="number" :value="__('Number')" />
                    <x-text-input wire:model="number" name="number" type="text" class="mt-1 block w-full" required autofocus autocomplete="number" />
                    <x-input-error class="mt-2" :messages="$errors->get('number')" />
                </div>
                <div>
                    <x-input-label for="usertype" :value="__('Usertype')" />
                    <select wire:model="usertype" class="rounded-md select block mt-1 w-full" name="usertype">
                        <option value="">Please Select User Type</option>
                        <option value="Owner">Owner</option>
                        <option value="Fireman">Fireman</option>
                        <option value="Admin">Admin</option>
                    </select>
                    <x-input-error :messages="$errors->get('usertype')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input wire:model="email" class="block mt-1 w-full" type="email" name="email" required autocomplete="email" />
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

    <!--Reset Password-->
    <div class="modal fade" id="resetPassword" tabindex="-1" aria-labelledby="resetModalLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h1 class="modal-title fs-5" id="resetModalLabel">RESET USER PASSWORD</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form wire:submit="resetPassword">
                    <div>
                        <x-input-label for="update_password_password" :value="__('New Password')" />
                        <x-text-input wire:model="password" id="update_password_password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>
            
                    <div>
                        <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" />
                        <x-text-input wire:model="password_confirmation" id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>
            
                    <div class="flex items-center justify-end mt-4">
                        <x-secondary-button x-on:click="$dispatch('close')">
                            {{ __('Cancel') }}
                        </x-secondary-button>

                        <x-primary-button>
                            {{ __('Reset Password') }}
                        </x-primary-button>
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
    $(document).ready(function(){
      $('#searchTxt').on('keyup',function(){
        @this.search = $(this).val();
        @this.call('with');
      })
    });

    $wire.on('showEditModal', () => {
      $('#editModal').modal('show');
    });
    $wire.on('showDeleteModal', () => {
      $('#deleteModal').modal('show');
    });
    $wire.on('openResetPassword', () => {
      $('#resetPassword').modal('show');
    });
    $wire.on('close', () => {
      $('#editModal').modal('hide');
      $('#deleteModal').modal('hide');
    });
 </script>
@endscript