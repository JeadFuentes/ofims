<x-main-layout>
  <x-slot name="title">
    alarm
  </x-slot>
  @if (session()->has('message'))
    <div class="alert alert-success">
        {{ session('message') }}
    </div>
  @endif
  <livewire:alarm />
</x-main-layout>