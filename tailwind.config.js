import preset from './vendor/filament/support/tailwind.config.preset'

export default {
    presets: [preset],
    darkMode: 'class',
    content: [
        './app/Filament/**/*.php',
        './resources/views/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],
    theme: {
        extend: {
            colors: {
                dark: {
                    'bg-primary': '#1a1a1a',
                    'bg-secondary': '#2d2d2d',
                    'text-primary': '#ffffff',
                    'text-secondary': '#a0aec0',
                }
            }
        }
    }
}
