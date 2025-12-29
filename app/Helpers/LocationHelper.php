<?php

namespace App\Helpers;

class LocationHelper
{
    /**
     * Calcula a distância entre duas coordenadas em quilômetros usando a fórmula de Haversine
     *
     * @param float $lat1 Latitude do ponto 1
     * @param float $lon1 Longitude do ponto 1
     * @param float $lat2 Latitude do ponto 2
     * @param float $lon2 Longitude do ponto 2
     * @return float Distância em quilômetros
     */
    public static function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        if (is_null($lat1) || is_null($lon1) || is_null($lat2) || is_null($lon2)) {
            return 0;
        }

        $earthRadius = 6371; // Raio da Terra em km

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distance = $earthRadius * $c;

        return round($distance, 2);
    }

    /**
     * Calcula o custo de frete baseado na distância
     *
     * @param float $distance Distância em quilômetros
     * @param float $weightKg Peso em quilogramas
     * @return float Custo do frete em Meticais
     */
    public static function calculateShippingCost($distance, $weightKg = 1)
    {
        // Custo base por km
        $costPerKm = 5; // 5 MT por km

        // Custo adicional por kg (acima de 10kg)
        $weightMultiplier = $weightKg > 10 ? ($weightKg / 10) : 1;

        // Custo mínimo de frete
        $minimumCost = 50; // 50 MT

        $shippingCost = $distance * $costPerKm * $weightMultiplier;

        return max($shippingCost, $minimumCost);
    }

    /**
     * Formata a distância para exibição
     *
     * @param float $distance Distância em quilômetros
     * @return string Distância formatada
     */
    public static function formatDistance($distance)
    {
        if ($distance < 1) {
            return round($distance * 1000) . ' m';
        }

        return round($distance, 1) . ' km';
    }
}
