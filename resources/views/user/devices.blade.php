<x-main-layout>
    <div class="container-sm">
    <h5 class="text">Devices Page</h5>
    <button type="button" class="btn btn-primary ml-3 mt-3 mb-2">New Device</button>
    <table class="ml-3 table table-striped table-hover" style="width: 90%">
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
          <tr>
            <th scope="row">1</th>
            <td>Mark</td>
            <td>Otto</td>
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