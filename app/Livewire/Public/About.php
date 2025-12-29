<?php

namespace App\Livewire\Public;

use Livewire\Component;
use App\Models\Setting;

class About extends Component
{
    public function render()
    {
        return view('livewire.public.about', [
            'heroTitle' => Setting::get('about_hero_title', 'Sobre a MozCommodities'),
            'heroSubtitle' => Setting::get('about_hero_subtitle', ''),
            'introText' => Setting::get('about_intro_text', ''),

            'missionTitle' => Setting::get('about_mission_title', 'Nossa Missão'),
            'missionText' => Setting::get('about_mission_text', ''),
            'visionTitle' => Setting::get('about_vision_title', 'Nossa Visão'),
            'visionText' => Setting::get('about_vision_text', ''),

            'valuesTitle' => Setting::get('about_values_title', 'Nossos Valores'),
            'value1Title' => Setting::get('about_value_1_title', ''),
            'value1Text' => Setting::get('about_value_1_text', ''),
            'value2Title' => Setting::get('about_value_2_title', ''),
            'value2Text' => Setting::get('about_value_2_text', ''),
            'value3Title' => Setting::get('about_value_3_title', ''),
            'value3Text' => Setting::get('about_value_3_text', ''),
            'value4Title' => Setting::get('about_value_4_title', ''),
            'value4Text' => Setting::get('about_value_4_text', ''),

            'teamTitle' => Setting::get('about_team_title', 'Nossa Equipa'),
            'teamText' => Setting::get('about_team_text', ''),

            'statsProducts' => Setting::get('about_stats_products', '500+'),
            'statsSuppliers' => Setting::get('about_stats_suppliers', '100+'),
            'statsClients' => Setting::get('about_stats_clients', '1000+'),
            'statsYears' => Setting::get('about_stats_years', '5+'),
        ])->layout('components.layouts.shop');
    }
}
