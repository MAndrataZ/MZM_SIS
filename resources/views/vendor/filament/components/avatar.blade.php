@props([
    'circular' => true,
    'size' => 'md',
])

@php
    $user = auth()->user();
    $fotoProfil = $user?->foto_profil;

    // Default image (public/default-avatar.png), ganti sesuai kebutuhanmu
    $defaultAvatar = asset('images/default-avatar.png');

    // Ambil URL foto_profil jika ada, else default
    $src = $fotoProfil ? asset('storage/' . $fotoProfil) : $defaultAvatar;
@endphp

<img
    src="{{ $src }}"
    {{
        $attributes->class([
            'fi-avatar object-cover object-center',
            'rounded-md' => ! $circular,
            'fi-circular rounded-full' => $circular,
            match ($size) {
                'sm' => 'h-6 w-6',
                'md' => 'h-8 w-8',
                'lg' => 'h-10 w-10',
                default => $size,
            },
        ])
    }}
/>
