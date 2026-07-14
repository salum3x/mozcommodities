<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Collection;

/**
 * Estimativa de frete por distância (Haversine).
 * Tarifas configuráveis em admin/pagamentos ou settings.
 * Fallback: base 50 MZN + 8 MZN/km, mínimo 80 MZN.
 */
class ShippingService
{
    public function baseFee(): float
    {
        return (float) Setting::get('shipping_base_fee', 50);
    }

    public function pricePerKm(): float
    {
        return (float) Setting::get('shipping_price_per_km', 8);
    }

    public function minFee(): float
    {
        return (float) Setting::get('shipping_min_fee', 80);
    }

    public function freeOverAmount(): ?float
    {
        $v = Setting::get('shipping_free_over_amount');
        return $v !== null && $v !== '' ? (float) $v : null;
    }

    /**
     * Distância Haversine em km entre dois pontos lat/lng.
     */
    public function distanceKm(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthKm = 6371.0;
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        $a = sin($dLat / 2) ** 2 + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) ** 2;
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earthKm * $c;
    }

    /**
     * Tenta ler o destino actual do cliente (cookie do mapa Leaflet).
     * Retorna [lat, lng, label] ou null.
     */
    public function destinationFromCookies(): ?array
    {
        $lat = request()->cookie('delivery_lat');
        $lng = request()->cookie('delivery_lng');
        $label = request()->cookie('delivery_label');
        if (!is_numeric($lat) || !is_numeric($lng)) return null;
        return [(float) $lat, (float) $lng, $label ? urldecode($label) : null];
    }

    /**
     * Origem do envio: localização do fornecedor (suppliers.latitude/longitude) ou warehouse default.
     * Aceita array de items para resolver fornecedor — caso multi-fornecedor, usa o primeiro.
     */
    public function originFor(?User $supplierUser = null): array
    {
        if ($supplierUser && $supplierUser->latitude && $supplierUser->longitude) {
            return [(float) $supplierUser->latitude, (float) $supplierUser->longitude];
        }
        // Warehouse default (Maputo, configurável)
        return [
            (float) Setting::get('warehouse_lat', -25.9692),
            (float) Setting::get('warehouse_lng', 32.5732),
        ];
    }

    /**
     * Calcula frete para uma encomenda.
     *
     * Estratégia: 1) tenta `shipping_zones` table pela província/cidade do destino;
     *             2) fallback para fórmula Haversine + tarifa base/km.
     */
    public function estimate(?array $destination = null, ?User $supplierUser = null, float $orderSubtotal = 0, float $weightKg = 0): array
    {
        $destination ??= $this->destinationFromCookies();
        if (!$destination) {
            return [
                'available' => false,
                'reason'    => 'Sem localização de entrega — escolhe no mapa do topo.',
                'cost'      => 0.0,
                'distance'  => null,
            ];
        }

        $label = $destination[2] ?? null;

        // 1) Zona configurada (preferido)
        $zone = \App\Models\ShippingZone::resolveFor($label);
        if ($zone) {
            // Free shipping threshold (per zone overrides global)
            $zoneFree = $zone->free_above_amount ? (float) $zone->free_above_amount : $this->freeOverAmount();
            if ($zoneFree !== null && $orderSubtotal >= $zoneFree) {
                return [
                    'available' => true,
                    'cost'      => 0.0,
                    'distance'  => null,
                    'breakdown' => 'Entrega grátis em ' . $zone->province . ' (pedido acima de ' . number_format($zoneFree, 2, ',', '.') . ' MZN).',
                    'label'     => $label,
                    'zone'      => $zone->province,
                ];
            }

            // Truckload (acima do threshold de kg → preço flat)
            if ($zone->truckload_threshold_kg && $weightKg >= $zone->truckload_threshold_kg && $zone->truckload_flat_fee) {
                return [
                    'available' => true,
                    'cost'      => (float) $zone->truckload_flat_fee,
                    'distance'  => null,
                    'breakdown' => sprintf('Frete carrada (≥ %d kg) — %s', $zone->truckload_threshold_kg, $zone->province),
                    'label'     => $label,
                    'zone'      => $zone->province,
                ];
            }

            $cost = (float) $zone->base_fee + ($weightKg * (float) $zone->per_kg_rate);
            return [
                'available' => true,
                'cost'      => round($cost, 2),
                'distance'  => null,
                'breakdown' => sprintf(
                    'Base %s MZN + %.1f kg × %s MZN/kg (%s)',
                    number_format((float) $zone->base_fee, 0, ',', '.'),
                    $weightKg,
                    number_format((float) $zone->per_kg_rate, 0, ',', '.'),
                    $zone->province
                ),
                'label'     => $label,
                'zone'      => $zone->province,
            ];
        }

        // 2) Fallback: distância Haversine
        $free = $this->freeOverAmount();
        if ($free !== null && $orderSubtotal >= $free) {
            return [
                'available' => true,
                'cost'      => 0.0,
                'distance'  => null,
                'breakdown' => 'Entrega grátis (pedido acima de ' . number_format($free, 2, ',', '.') . ' MZN).',
                'label'     => $label,
            ];
        }

        [$oLat, $oLng] = $this->originFor($supplierUser);
        [$dLat, $dLng] = $destination;
        $km = round($this->distanceKm($oLat, $oLng, $dLat, $dLng), 1);

        $cost = $this->baseFee() + $km * $this->pricePerKm();
        $cost = max($this->minFee(), $cost);

        return [
            'available'   => true,
            'cost'        => round($cost, 2),
            'distance'    => $km,
            'breakdown'   => sprintf(
                'Base %s MZN + %s km × %s MZN/km (zona não mapeada — usa distância)',
                number_format($this->baseFee(), 0, ',', '.'),
                number_format($km, 1, ',', '.'),
                number_format($this->pricePerKm(), 0, ',', '.')
            ),
            'label'       => $label,
        ];
    }
}
