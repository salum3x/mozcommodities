import './bootstrap';

// Livewire 3 ships and starts its own Alpine via @livewireScripts.
// Importing alpinejs here registers a second Alpine and breaks wire:click,
// x-data, and reactivity. Use Alpine.plugin / Livewire.directive from a
// `livewire:init` listener if extra plugins are needed.
