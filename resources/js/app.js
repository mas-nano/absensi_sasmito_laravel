import "./bootstrap";
import "../../vendor/masmerise/livewire-toaster/resources/js";
import {
    Livewire,
    Alpine,
} from "../../vendor/livewire/livewire/dist/livewire.esm";
import focus from "@alpinejs/focus";

Alpine.plugin(focus);
Livewire.start();
