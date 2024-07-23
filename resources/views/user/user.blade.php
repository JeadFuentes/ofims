<x-main-layout>
  <x-slot name="title">
    user
  </x-slot>
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
          <tr>
            <th scope="row">1</th>
            <td>Mark</td>
            <td>Otto</td>
            <td>@mdo</td>
            <td>@mdo</td>
            <td>@mdo</td>
            <td>
                <button type="button" class="btn btn-sm btn-success">Edit</button>
                <button type="button" class="btn btn-sm btn-danger">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
</x-main-layout>