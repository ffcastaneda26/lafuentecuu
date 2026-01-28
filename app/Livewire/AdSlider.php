<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Sponsor;
use App\Models\Advertisement;
use App\Enums\AdvertisementPositionEnum;
use Illuminate\Support\Collection;

class AdSlider extends Component
{
    public function render()
    {
        $ads = $this->getAvailableAds();

        return view('livewire.ad-slider', [
            'ads' => $ads
        ]);
    }

    private function getAvailableAds(): Collection
    {
        // Mantenimiento de caducidad
        Advertisement::where('active', true)
            ->whereNotNull('end_date')
            ->where('end_date', '<', now()->startOfDay())
            ->update(['active' => false]);

        // Anuncios Activos para la cabecera
        return Advertisement::active()
            ->where('position', AdvertisementPositionEnum::HEADER->value)
            ->orderBy('priority', 'asc')
            ->get(['id', 'title', 'click_url', 'media_url', 'type', 'sponsor_id']);
    }
}
