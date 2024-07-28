<x-main-layout>
  <x-slot name="title">
    devices
  </x-slot>
  @if (session()->has('message'))
    <div class="alert alert-success">
        {{ session('message') }}
    </div>
  @endif
    <livewire:devices />
</x-main-layout>