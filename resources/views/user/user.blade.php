<x-main-layout>
  <x-slot name="title">
    user
  </x-slot>
  @if (session()->has('message'))
      <div class="alert alert-success">
          {{ session('message') }}
      </div>
    @endif
  <livewire:users />
</x-main-layout>