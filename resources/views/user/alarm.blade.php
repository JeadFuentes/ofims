<x-main-layout>
  <x-slot name="title">
    alarm
  </x-slot>
    <div class="container-sm">
    <!--<button type="button" class="btn btn-primary ml-3 mt-3 mb-2">New Device</button>-->
    <table class="ml-3 mt-5 table table-striped table-hover" style="width: 100%">
        <thead class="text-center">
          <tr>
            <th scope="col">#</th>
            <th scope="col">Device name</th>
            <th scope="col">Responder</th>
            <th scope="col">Response Time</th>
            <th scope="col">Alarm Time</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <tbody class="table-group-divider text-center">
          <tr>
            <th scope="row">1</th>
            <td>Otto</td>
            <td>Mark</td>
            <td>Otto</td>
            <td>@mdo</td>
            <td>
                <button type="button" class="btn btn-sm btn-success">RESPOND</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
</x-main-layout>