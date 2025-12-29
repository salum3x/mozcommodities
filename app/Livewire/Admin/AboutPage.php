<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Setting;

class AboutPage extends Component
{
    // Hero Section
    public $about_hero_title;
    public $about_hero_subtitle;
    public $about_intro_text;

    // Mission & Vision
    public $about_mission_title;
    public $about_mission_text;
    public $about_vision_title;
    public $about_vision_text;

    // Values
    public $about_values_title;
    public $about_value_1_title;
    public $about_value_1_text;
    public $about_value_2_title;
    public $about_value_2_text;
    public $about_value_3_title;
    public $about_value_3_text;
    public $about_value_4_title;
    public $about_value_4_text;

    // Team
    public $about_team_title;
    public $about_team_text;

    // Stats
    public $about_stats_products;
    public $about_stats_suppliers;
    public $about_stats_clients;
    public $about_stats_years;

    public function mount()
    {
        $this->about_hero_title = Setting::get('about_hero_title', '');
        $this->about_hero_subtitle = Setting::get('about_hero_subtitle', '');
        $this->about_intro_text = Setting::get('about_intro_text', '');

        $this->about_mission_title = Setting::get('about_mission_title', '');
        $this->about_mission_text = Setting::get('about_mission_text', '');
        $this->about_vision_title = Setting::get('about_vision_title', '');
        $this->about_vision_text = Setting::get('about_vision_text', '');

        $this->about_values_title = Setting::get('about_values_title', '');
        $this->about_value_1_title = Setting::get('about_value_1_title', '');
        $this->about_value_1_text = Setting::get('about_value_1_text', '');
        $this->about_value_2_title = Setting::get('about_value_2_title', '');
        $this->about_value_2_text = Setting::get('about_value_2_text', '');
        $this->about_value_3_title = Setting::get('about_value_3_title', '');
        $this->about_value_3_text = Setting::get('about_value_3_text', '');
        $this->about_value_4_title = Setting::get('about_value_4_title', '');
        $this->about_value_4_text = Setting::get('about_value_4_text', '');

        $this->about_team_title = Setting::get('about_team_title', '');
        $this->about_team_text = Setting::get('about_team_text', '');

        $this->about_stats_products = Setting::get('about_stats_products', '');
        $this->about_stats_suppliers = Setting::get('about_stats_suppliers', '');
        $this->about_stats_clients = Setting::get('about_stats_clients', '');
        $this->about_stats_years = Setting::get('about_stats_years', '');
    }

    public function save()
    {
        $this->validate([
            'about_hero_title' => 'required|string|max:255',
            'about_hero_subtitle' => 'required|string|max:500',
            'about_intro_text' => 'required|string',
            'about_mission_title' => 'required|string|max:255',
            'about_mission_text' => 'required|string',
            'about_vision_title' => 'required|string|max:255',
            'about_vision_text' => 'required|string',
            'about_values_title' => 'required|string|max:255',
            'about_value_1_title' => 'required|string|max:255',
            'about_value_1_text' => 'required|string',
            'about_value_2_title' => 'required|string|max:255',
            'about_value_2_text' => 'required|string',
            'about_value_3_title' => 'required|string|max:255',
            'about_value_3_text' => 'required|string',
            'about_value_4_title' => 'required|string|max:255',
            'about_value_4_text' => 'required|string',
            'about_team_title' => 'required|string|max:255',
            'about_team_text' => 'required|string',
            'about_stats_products' => 'required|string|max:50',
            'about_stats_suppliers' => 'required|string|max:50',
            'about_stats_clients' => 'required|string|max:50',
            'about_stats_years' => 'required|string|max:50',
        ]);

        // Save all settings
        Setting::set('about_hero_title', $this->about_hero_title);
        Setting::set('about_hero_subtitle', $this->about_hero_subtitle);
        Setting::set('about_intro_text', $this->about_intro_text);

        Setting::set('about_mission_title', $this->about_mission_title);
        Setting::set('about_mission_text', $this->about_mission_text);
        Setting::set('about_vision_title', $this->about_vision_title);
        Setting::set('about_vision_text', $this->about_vision_text);

        Setting::set('about_values_title', $this->about_values_title);
        Setting::set('about_value_1_title', $this->about_value_1_title);
        Setting::set('about_value_1_text', $this->about_value_1_text);
        Setting::set('about_value_2_title', $this->about_value_2_title);
        Setting::set('about_value_2_text', $this->about_value_2_text);
        Setting::set('about_value_3_title', $this->about_value_3_title);
        Setting::set('about_value_3_text', $this->about_value_3_text);
        Setting::set('about_value_4_title', $this->about_value_4_title);
        Setting::set('about_value_4_text', $this->about_value_4_text);

        Setting::set('about_team_title', $this->about_team_title);
        Setting::set('about_team_text', $this->about_team_text);

        Setting::set('about_stats_products', $this->about_stats_products);
        Setting::set('about_stats_suppliers', $this->about_stats_suppliers);
        Setting::set('about_stats_clients', $this->about_stats_clients);
        Setting::set('about_stats_years', $this->about_stats_years);

        session()->flash('message', 'Página "Sobre Nós" atualizada com sucesso!');
    }

    public function render()
    {
        return view('livewire.admin.about-page')
            ->layout('components.layouts.admin');
    }
}
