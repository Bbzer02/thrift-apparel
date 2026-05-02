<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LandingHeroSlidesController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'slides' => ['required', 'array', 'min:1', 'max:20'],
            'slides.*.src' => ['required', 'string', 'max:2048'],
            'slides.*.alt' => ['nullable', 'string', 'max:255'],
            'slides.*.class' => ['nullable', 'string', 'max:100'],
        ]);

        $slides = collect($validated['slides'])
            ->map(function (array $slide, int $index): array {
                $class = $slide['class'] ?? 'slide';
                if (!in_array($class, ['slide', 'slide slide-grow-4', 'slide slide-grow-5'], true)) {
                    $class = 'slide';
                }

                return [
                    'src' => trim((string) $slide['src']),
                    'alt' => trim((string) ($slide['alt'] ?? ('Model '.($index + 1)))),
                    'class' => $class,
                ];
            })
            ->filter(fn (array $slide): bool => $slide['src'] !== '')
            ->values()
            ->all();

        if (!count($slides)) {
            return response()->json(['message' => 'No valid slides to save.'], 422);
        }

        Storage::disk('public')->put(
            'landing-hero-slides.json',
            json_encode($slides, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );

        return response()->json([
            'message' => 'Hero slides saved.',
            'slides' => $slides,
        ]);
    }
}
