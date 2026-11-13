@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-warm-300 bg-warm-50 focus:border-primary focus:ring-primary rounded-md shadow-sm text-warm-800']) }}>