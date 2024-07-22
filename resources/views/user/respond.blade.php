<x-main-layout>
    <x-slot name="title">
      respond
    </x-slot>
      <div class="container-sm">
      <button type="button" class="btn btn-primary ml-3 mt-3 mb-2">New Responder</button>
      <table class="ml-3 table table-striped table-hover" style="width: 100%">
          <thead class="text-center">
            <tr>
              <th scope="col">#</th>
              <th scope="col">Alarm Device</th>
              <th scope="col">Responder</th>
              <th scope="col">Responded Time</th>
              <th scope="col">Action</th>
            </tr>
          </thead>
          <tbody class="table-group-divider text-center">
            <tr>
              <th scope="row">1</th>
              <td>Mark</td>
              <td>Otto</td>
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