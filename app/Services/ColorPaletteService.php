<?php

namespace App\Services;

use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ColorPaletteService
{
    /**
     * Get the active color palette for the current tenant.
     */
    public function getActivePalette(): ?array
    {
        $tenantId = saas_tenant('id');

        // If no tenant found, return null
        if (!$tenantId) {
            return null;
        }

        return Cache::remember("color_palette_tenant_{$tenantId}", 3600, function () use ($tenantId) {
            $tenant = Tenant::find($tenantId);
            
            if (!$tenant || empty($tenant->theme_colors)) {
                return $this->getDefaultColors();
            }

            return is_string($tenant->theme_colors) ? json_decode($tenant->theme_colors, true) : $tenant->theme_colors;
        });
    }

    /**
     * Get all colors for the current tenant.
     */
    public function getAllColors(): array
    {
        $palette = $this->getActivePalette();

        if (!$palette) {
            return $this->getDefaultColors();
        }

        return $palette;
    }

    /**
     * Get default colors when no tenant is found.
     */
    private function getDefaultColors(): array
    {
        return [
            'primary' => [
                '50' => '#eff6ff',
                '100' => '#dbeafe',
                '500' => '#3b82f6',
                '600' => '#2563eb',
                '700' => '#1d4ed8',
                '900' => '#1e3a8a',
            ],
            'secondary' => [
                '50' => '#f8fafc',
                '100' => '#f1f5f9',
                '500' => '#64748b',
                '600' => '#475569',
                '700' => '#334155',
                '900' => '#0f172a',
            ],
            'accent' => [
                '50' => '#fef3c7',
                '100' => '#fde68a',
                '500' => '#f59e0b',
                '600' => '#d97706',
                '700' => '#b45309',
                '900' => '#78350f',
            ],
            'success' => '#10b981',
            'warning' => '#f59e0b',
            'error' => '#ef4444',
            'info' => '#3b82f6',
        ];
    }

    /**
     * Helper to convert hex color to RGB string.
     */
    private function hexToRgb(string $hex): string
    {
        $hex = str_replace('#', '', $hex);
        if (strlen($hex) == 3) {
            $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
            $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
            $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
        } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }
        return "{$r}, {$g}, {$b}";
    }

    /**
     * Generate CSS custom properties for the current tenant's color palette.
     */
    public function generateCSSVariables(): string
    {
        $colors = $this->getAllColors();

        $css = ":root {\n";

        // Primary colors
        if (isset($colors['primary'])) {
            foreach ($colors['primary'] as $shade => $color) {
                $css .= "    --color-primary-{$shade}: {$color};\n";
                $css .= "    --color-primary-{$shade}-rgb: " . $this->hexToRgb($color) . ";\n";
            }
        }

        // Secondary colors
        if (isset($colors['secondary'])) {
            foreach ($colors['secondary'] as $shade => $color) {
                $css .= "    --color-secondary-{$shade}: {$color};\n";
                $css .= "    --color-secondary-{$shade}-rgb: " . $this->hexToRgb($color) . ";\n";
            }
        }

        // Accent colors
        if (isset($colors['accent'])) {
            foreach ($colors['accent'] as $shade => $color) {
                $css .= "    --color-accent-{$shade}: {$color};\n";
                $css .= "    --color-accent-{$shade}-rgb: " . $this->hexToRgb($color) . ";\n";
            }
        }

        // Status colors
        if (isset($colors['success'])) {
            $css .= "    --color-success: {$colors['success']};\n";
        }
        if (isset($colors['warning'])) {
            $css .= "    --color-warning: {$colors['warning']};\n";
        }
        if (isset($colors['error'])) {
            $css .= "    --color-error: {$colors['error']};\n";
        }
        if (isset($colors['info'])) {
            $css .= "    --color-info: {$colors['info']};\n";
        }

        $css .= "}\n\n";

        // Add CSS rules to ensure Tailwind classes use our variables
        $css .= "/* Ensure Tailwind/DaisyUI classes use our CSS variables */\n";
        $css .= ".text-primary { color: var(--color-primary-600) !important; }\n";
        $css .= ".bg-primary { background-color: var(--color-primary-600) !important; }\n";
        $css .= ".border-primary { border-color: var(--color-primary-600) !important; }\n";
        $css .= ".text-primary-500 { color: var(--color-primary-500) !important; }\n";
        $css .= ".text-primary-600 { color: var(--color-primary-600) !important; }\n";
        $css .= ".bg-primary-600 { background-color: var(--color-primary-600) !important; }\n";
        $css .= ".border-primary-600 { border-color: var(--color-primary-600) !important; }\n";
        $css .= ".bg-primary-50 { background-color: var(--color-primary-50) !important; }\n";
        $css .= ".bg-primary-100 { background-color: var(--color-primary-100) !important; }\n";
        $css .= ".text-primary-700 { color: var(--color-primary-700) !important; }\n";
        $css .= ".bg-primary-700 { background-color: var(--color-primary-700) !important; }\n";
        $css .= ".bg-primary-900 { background-color: var(--color-primary-900) !important; }\n";
        $css .= ".text-primary-900 { color: var(--color-primary-900) !important; }\n";
        
        $css .= ".text-secondary-600 { color: var(--color-secondary-600) !important; }\n";
        $css .= ".bg-secondary-100 { background-color: var(--color-secondary-100) !important; }\n";
        
        $css .= ".hover\\:text-primary-600:hover { color: var(--color-primary-600) !important; }\n";
        $css .= ".hover\\:bg-primary-700:hover { background-color: var(--color-primary-700) !important; }\n";
        $css .= ".hover\\:bg-secondary-200:hover { background-color: var(--color-secondary-200) !important; }\n";

        // Button custom overrides
        $css .= ".btn-primary { background-color: var(--color-primary-600) !important; border-color: var(--color-primary-600) !important; color: #ffffff !important; }\n";
        $css .= ".btn-primary:hover { background-color: var(--color-primary-700) !important; border-color: var(--color-primary-700) !important; }\n";

        // Opacity classes overrides
        $css .= ".bg-primary\\/10 { background-color: rgba(var(--color-primary-600-rgb, 37, 99, 235), 0.1) !important; }\n";
        $css .= ".text-primary\\/10 { color: rgba(var(--color-primary-600-rgb, 37, 99, 235), 0.1) !important; }\n";
        $css .= ".shadow-primary\\/20 { --tw-shadow-color: rgba(var(--color-primary-600-rgb, 37, 99, 235), 0.2) !important; }\n";

        return $css;
    }

    /**
     * Generate inline CSS for the current tenant.
     */
    public function generateInlineCSS(): string
    {
        return "<style>\n" . $this->generateCSSVariables() . "</style>";
    }

    /**
     * Update color palette for a tenant.
     */
    public function updatePalette(string $tenantId, array $colors): Tenant
    {
        $tenant = Tenant::findOrFail($tenantId);
        $tenant->update(['theme_colors' => $colors]);

        // Clear cache
        Cache::forget("color_palette_tenant_{$tenantId}");

        return $tenant;
    }

    /**
     * Get predefined color schemes.
     */
    public function getPredefinedSchemes(): array
    {
        return [
            'blue' => [
                'name' => 'Blue Theme',
                'primary' => [
                    '500' => '#3b82f6',
                    '600' => '#2563eb',
                    '700' => '#1d4ed8',
                ],
            ],
            'green' => [
                'name' => 'Green Theme',
                'primary' => [
                    '500' => '#22c55e',
                    '600' => '#16a34a',
                    '700' => '#15803d',
                ],
            ],
            'purple' => [
                'name' => 'Purple Theme',
                'primary' => [
                    '500' => '#a855f7',
                    '600' => '#9333ea',
                    '700' => '#7c3aed',
                ],
            ],
            'red' => [
                'name' => 'Red Theme',
                'primary' => [
                    '500' => '#ef4444',
                    '600' => '#dc2626',
                    '700' => '#b91c1c',
                ],
            ],
            'indigo' => [
                'name' => 'Indigo Theme',
                'primary' => [
                    '500' => '#6366f1',
                    '600' => '#4f46e5',
                    '700' => '#4338ca',
                ],
            ],
        ];
    }
}
