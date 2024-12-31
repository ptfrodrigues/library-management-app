import './bootstrap';
import Alpine from 'alpinejs';
import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';

Livewire.start();
window.Alpine = Alpine;
Alpine.start();