import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', 'Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Dark theme color palette
                dark: {
                    50: '#f8fafc',
                    100: '#f1f5f9',
                    200: '#e2e8f0',
                    300: '#cbd5e1',
                    400: '#94a3b8',
                    500: '#64748b',
                    600: '#475569',
                    700: '#334155',
                    800: '#1e293b',
                    900: '#0f172a',
                    950: '#020617',
                },
                primary: {
                    50: '#eff6ff',
                    100: '#dbeafe',
                    200: '#bfdbfe',
                    300: '#93c5fd',
                    400: '#60a5fa',
                    500: '#3b82f6',
                    600: '#2563eb',
                    700: '#1d4ed8',
                    800: '#1e40af',
                    900: '#1e3a8a',
                    950: '#172554',
                },
                accent: {
                    50: '#fdf4ff',
                    100: '#fae8ff',
                    200: '#f5d0fe',
                    300: '#f0abfc',
                    400: '#e879f9',
                    500: '#d946ef',
                    600: '#c026d3',
                    700: '#a21caf',
                    800: '#86198f',
                    900: '#701a75',
                    950: '#4a044e',
                },
                success: {
                    50: '#f0fdf4',
                    100: '#dcfce7',
                    200: '#bbf7d0',
                    300: '#86efac',
                    400: '#4ade80',
                    500: '#22c55e',
                    600: '#16a34a',
                    700: '#15803d',
                    800: '#166534',
                    900: '#14532d',
                    950: '#052e16',
                },
                warning: {
                    50: '#fffbeb',
                    100: '#fef3c7',
                    200: '#fde68a',
                    300: '#fcd34d',
                    400: '#fbbf24',
                    500: '#f59e0b',
                    600: '#d97706',
                    700: '#b45309',
                    800: '#92400e',
                    900: '#78350f',
                    950: '#451a03',
                },
                danger: {
                    50: '#fef2f2',
                    100: '#fee2e2',
                    200: '#fecaca',
                    300: '#fca5a5',
                    400: '#f87171',
                    500: '#ef4444',
                    600: '#dc2626',
                    700: '#b91c1c',
                    800: '#991b1b',
                    900: '#7f1d1d',
                    950: '#450a0a',
                },
            },
            // Fluid typography scales
            fontSize: {
                'fluid-xs': 'clamp(0.75rem, 0.7rem + 0.25vw, 0.875rem)',
                'fluid-sm': 'clamp(0.875rem, 0.825rem + 0.25vw, 1rem)',
                'fluid-base': 'clamp(1rem, 0.95rem + 0.25vw, 1.125rem)',
                'fluid-lg': 'clamp(1.125rem, 1.05rem + 0.375vw, 1.25rem)',
                'fluid-xl': 'clamp(1.25rem, 1.15rem + 0.5vw, 1.5rem)',
                'fluid-2xl': 'clamp(1.5rem, 1.35rem + 0.75vw, 1.875rem)',
                'fluid-3xl': 'clamp(1.875rem, 1.65rem + 1.125vw, 2.25rem)',
                'fluid-4xl': 'clamp(2.25rem, 1.95rem + 1.5vw, 3rem)',
                'fluid-5xl': 'clamp(3rem, 2.55rem + 2.25vw, 3.75rem)',
            },
            // Spring-based easing functions
            transitionTimingFunction: {
                spring: 'cubic-bezier(0.34, 1.56, 0.64, 1)',
                'spring-in': 'cubic-bezier(0.68, -0.6, 0.32, 1.6)',
                'spring-out': 'cubic-bezier(0.34, 1.56, 0.64, 1)',
                smooth: 'cubic-bezier(0.4, 0.0, 0.2, 1)',
                bounce: 'cubic-bezier(0.68, -0.55, 0.265, 1.55)',
            },
            animation: {
                // Enhanced fade animations with spring
                'fade-in': 'fadeIn 0.6s cubic-bezier(0.34, 1.56, 0.64, 1)',
                'fade-in-up': 'fadeInUp 0.7s cubic-bezier(0.34, 1.56, 0.64, 1)',
                'fade-in-down': 'fadeInDown 0.7s cubic-bezier(0.34, 1.56, 0.64, 1)',
                'fade-in-left': 'fadeInLeft 0.7s cubic-bezier(0.34, 1.56, 0.64, 1)',
                'fade-in-right': 'fadeInRight 0.7s cubic-bezier(0.34, 1.56, 0.64, 1)',

                // Slide animations
                'slide-in-right': 'slideInRight 0.5s cubic-bezier(0.34, 1.56, 0.64, 1)',
                'slide-in-left': 'slideInLeft 0.5s cubic-bezier(0.34, 1.56, 0.64, 1)',
                'slide-up': 'slideUp 0.5s cubic-bezier(0.34, 1.56, 0.64, 1)',
                'slide-down': 'slideDown 0.5s cubic-bezier(0.34, 1.56, 0.64, 1)',

                // Scale animations
                'scale-in': 'scaleIn 0.4s cubic-bezier(0.34, 1.56, 0.64, 1)',
                'scale-in-center': 'scaleInCenter 0.5s cubic-bezier(0.34, 1.56, 0.64, 1)',
                'bounce-in': 'bounceIn 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55)',

                // Rotation animations
                'rotate-in': 'rotateIn 0.6s cubic-bezier(0.34, 1.56, 0.64, 1)',
                'flip-in': 'flipIn 0.7s cubic-bezier(0.34, 1.56, 0.64, 1)',

                // Continuous animations
                'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                'pulse-fast': 'pulse 1.5s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                glow: 'glow 2s ease-in-out infinite alternate',
                'glow-pulse': 'glowPulse 2.5s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                shimmer: 'shimmer 2.5s linear infinite',
                'shimmer-fast': 'shimmer 1.5s linear infinite',

                // Background animations
                'gradient-shift': 'gradientShift 15s ease infinite',
                'mesh-move': 'meshMove 20s ease infinite',

                // Interaction animations
                ripple: 'ripple 0.6s ease-out',
                shake: 'shake 0.5s cubic-bezier(0.36, 0.07, 0.19, 0.97)',
                wiggle: 'wiggle 0.5s ease-in-out',

                // Skeleton loaders
                'skeleton-pulse': 'skeletonPulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite',

                // Text animations
                'text-shimmer': 'textShimmer 3s linear infinite',
                'text-reveal': 'textReveal 0.8s cubic-bezier(0.34, 1.56, 0.64, 1)',

                // Stagger delays
                'stagger-1': 'fadeInUp 0.7s cubic-bezier(0.34, 1.56, 0.64, 1) 0.1s backwards',
                'stagger-2': 'fadeInUp 0.7s cubic-bezier(0.34, 1.56, 0.64, 1) 0.2s backwards',
                'stagger-3': 'fadeInUp 0.7s cubic-bezier(0.34, 1.56, 0.64, 1) 0.3s backwards',
                'stagger-4': 'fadeInUp 0.7s cubic-bezier(0.34, 1.56, 0.64, 1) 0.4s backwards',
                'stagger-5': 'fadeInUp 0.7s cubic-bezier(0.34, 1.56, 0.64, 1) 0.5s backwards',
            },
            keyframes: {
                fadeIn: {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                fadeInUp: {
                    '0%': { opacity: '0', transform: 'translateY(30px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                fadeInDown: {
                    '0%': { opacity: '0', transform: 'translateY(-30px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                fadeInLeft: {
                    '0%': { opacity: '0', transform: 'translateX(-30px)' },
                    '100%': { opacity: '1', transform: 'translateX(0)' },
                },
                fadeInRight: {
                    '0%': { opacity: '0', transform: 'translateX(30px)' },
                    '100%': { opacity: '1', transform: 'translateX(0)' },
                },
                slideInRight: {
                    '0%': { opacity: '0', transform: 'translateX(100px)' },
                    '100%': { opacity: '1', transform: 'translateX(0)' },
                },
                slideInLeft: {
                    '0%': { opacity: '0', transform: 'translateX(-100px)' },
                    '100%': { opacity: '1', transform: 'translateX(0)' },
                },
                slideUp: {
                    '0%': { transform: 'translateY(100%)' },
                    '100%': { transform: 'translateY(0)' },
                },
                slideDown: {
                    '0%': { transform: 'translateY(-100%)' },
                    '100%': { transform: 'translateY(0)' },
                },
                scaleIn: {
                    '0%': { opacity: '0', transform: 'scale(0.9)' },
                    '100%': { opacity: '1', transform: 'scale(1)' },
                },
                scaleInCenter: {
                    '0%': { opacity: '0', transform: 'scale(0.5)' },
                    '50%': { transform: 'scale(1.05)' },
                    '100%': { opacity: '1', transform: 'scale(1)' },
                },
                bounceIn: {
                    '0%': { opacity: '0', transform: 'scale(0.3)' },
                    '50%': { opacity: '1', transform: 'scale(1.05)' },
                    '70%': { transform: 'scale(0.95)' },
                    '100%': { opacity: '1', transform: 'scale(1)' },
                },
                rotateIn: {
                    '0%': { opacity: '0', transform: 'rotate(-180deg) scale(0.5)' },
                    '100%': { opacity: '1', transform: 'rotate(0) scale(1)' },
                },
                flipIn: {
                    '0%': { opacity: '0', transform: 'rotateY(-90deg)' },
                    '100%': { opacity: '1', transform: 'rotateY(0)' },
                },
                glow: {
                    '0%': {
                        boxShadow:
                            '0 0 5px rgba(59, 130, 246, 0.5), 0 0 10px rgba(59, 130, 246, 0.3)',
                    },
                    '100%': {
                        boxShadow:
                            '0 0 20px rgba(59, 130, 246, 0.8), 0 0 40px rgba(59, 130, 246, 0.4)',
                    },
                },
                glowPulse: {
                    '0%, 100%': { boxShadow: '0 0 10px rgba(59, 130, 246, 0.5)' },
                    '50%': {
                        boxShadow:
                            '0 0 30px rgba(59, 130, 246, 0.9), 0 0 60px rgba(59, 130, 246, 0.5)',
                    },
                },
                shimmer: {
                    '0%': { backgroundPosition: '-200% 0' },
                    '100%': { backgroundPosition: '200% 0' },
                },
                gradientShift: {
                    '0%, 100%': { backgroundPosition: '0% 50%' },
                    '50%': { backgroundPosition: '100% 50%' },
                },
                meshMove: {
                    '0%, 100%': { transform: 'translate(0, 0) scale(1)' },
                    '25%': { transform: 'translate(5%, 5%) scale(1.1)' },
                    '50%': { transform: 'translate(-5%, 5%) scale(0.9)' },
                    '75%': { transform: 'translate(5%, -5%) scale(1.05)' },
                },
                ripple: {
                    '0%': { transform: 'scale(0)', opacity: '1' },
                    '100%': { transform: 'scale(4)', opacity: '0' },
                },
                shake: {
                    '0%, 100%': { transform: 'translateX(0)' },
                    '10%, 30%, 50%, 70%, 90%': { transform: 'translateX(-4px)' },
                    '20%, 40%, 60%, 80%': { transform: 'translateX(4px)' },
                },
                wiggle: {
                    '0%, 100%': { transform: 'rotate(0deg)' },
                    '25%': { transform: 'rotate(-3deg)' },
                    '75%': { transform: 'rotate(3deg)' },
                },
                skeletonPulse: {
                    '0%, 100%': { opacity: '1' },
                    '50%': { opacity: '0.5' },
                },
                textShimmer: {
                    '0%': { backgroundPosition: '0% 50%' },
                    '100%': { backgroundPosition: '200% 50%' },
                },
                textReveal: {
                    '0%': { opacity: '0', transform: 'translateY(20px) scale(0.95)' },
                    '100%': { opacity: '1', transform: 'translateY(0) scale(1)' },
                },
            },
            backdropBlur: {
                xs: '2px',
                sm: '4px',
                DEFAULT: '8px',
                md: '12px',
                lg: '16px',
                xl: '24px',
                '2xl': '40px',
                '3xl': '64px',
            },
            backdropSaturate: {
                0: '0',
                50: '.5',
                100: '1',
                150: '1.5',
                200: '2',
            },
            boxShadow: {
                glass: '0 8px 32px 0 rgba(31, 38, 135, 0.37)',
                'glass-lg': '0 16px 48px 0 rgba(31, 38, 135, 0.5)',
                dark: '0 10px 25px -3px rgba(0, 0, 0, 0.3), 0 4px 6px -2px rgba(0, 0, 0, 0.05)',
                'dark-lg': '0 25px 50px -12px rgba(0, 0, 0, 0.5)',
                'dark-xl': '0 35px 60px -15px rgba(0, 0, 0, 0.7)',
                'glow-primary':
                    '0 0 20px rgba(59, 130, 246, 0.3), 0 0 40px rgba(59, 130, 246, 0.1)',
                'glow-primary-lg':
                    '0 0 30px rgba(59, 130, 246, 0.5), 0 0 60px rgba(59, 130, 246, 0.2)',
                'glow-accent': '0 0 20px rgba(217, 70, 239, 0.3), 0 0 40px rgba(217, 70, 239, 0.1)',
                'glow-accent-lg':
                    '0 0 30px rgba(217, 70, 239, 0.5), 0 0 60px rgba(217, 70, 239, 0.2)',
                'glow-success': '0 0 20px rgba(34, 197, 94, 0.3), 0 0 40px rgba(34, 197, 94, 0.1)',
                'inner-glow': 'inset 0 0 20px rgba(59, 130, 246, 0.2)',
                '3d': '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06), inset 0 -2px 0 rgba(0, 0, 0, 0.1)',
                '3d-lg':
                    '0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05), inset 0 -3px 0 rgba(0, 0, 0, 0.1)',
            },
            borderRadius: {
                xl: '0.75rem',
                '2xl': '1rem',
                '3xl': '1.5rem',
            },
            spacing: {
                18: '4.5rem',
                88: '22rem',
                128: '32rem',
            },
        },
    },

    plugins: [
        forms({ strategy: 'class' }),
        typography(),
        function ({ addUtilities, addBase }) {
            // Base layer for CSS variables and foundational styles
            addBase({
                ':root': {
                    '--spring-easing': 'cubic-bezier(0.34, 1.56, 0.64, 1)',
                    '--spring-duration': '0.6s',
                },
            });

            const newUtilities = {
                // Multi-layer glassmorphism
                '.glass': {
                    background: 'rgba(255, 255, 255, 0.1)',
                    'backdrop-filter': 'blur(10px) saturate(150%)',
                    '-webkit-backdrop-filter': 'blur(10px) saturate(150%)',
                    border: '1px solid rgba(255, 255, 255, 0.2)',
                },
                '.glass-dark': {
                    background: 'rgba(0, 0, 0, 0.3)',
                    'backdrop-filter': 'blur(10px) saturate(150%)',
                    '-webkit-backdrop-filter': 'blur(10px) saturate(150%)',
                    border: '1px solid rgba(255, 255, 255, 0.1)',
                },
                '.glass-frosted': {
                    background: 'rgba(15, 23, 42, 0.7)',
                    'backdrop-filter': 'blur(16px) saturate(180%)',
                    '-webkit-backdrop-filter': 'blur(16px) saturate(180%)',
                    border: '1px solid rgba(255, 255, 255, 0.1)',
                    'box-shadow': '0 8px 32px 0 rgba(31, 38, 135, 0.37)',
                },
                '.glass-premium': {
                    position: 'relative',
                    background: 'rgba(15, 23, 42, 0.6)',
                    'backdrop-filter': 'blur(20px) saturate(200%)',
                    '-webkit-backdrop-filter': 'blur(20px) saturate(200%)',
                    border: '1px solid rgba(255, 255, 255, 0.15)',
                    'box-shadow':
                        '0 8px 32px 0 rgba(31, 38, 135, 0.37), inset 0 1px 0 0 rgba(255, 255, 255, 0.1)',
                },

                // Text effects
                '.text-shadow': {
                    'text-shadow': '0 2px 4px rgba(0, 0, 0, 0.1)',
                },
                '.text-shadow-md': {
                    'text-shadow': '0 3px 6px rgba(0, 0, 0, 0.2)',
                },
                '.text-shadow-lg': {
                    'text-shadow': '0 4px 8px rgba(0, 0, 0, 0.3)',
                },
                '.text-shadow-glow': {
                    'text-shadow':
                        '0 0 10px rgba(59, 130, 246, 0.5), 0 0 20px rgba(59, 130, 246, 0.3)',
                },

                // Gradient text
                '.text-gradient-animated': {
                    background: 'linear-gradient(90deg, #3b82f6, #8b5cf6, #ec4899, #3b82f6)',
                    'background-size': '200% auto',
                    '-webkit-background-clip': 'text',
                    '-webkit-text-fill-color': 'transparent',
                    'background-clip': 'text',
                    animation: 'textShimmer 3s linear infinite',
                },

                // 3D Transform utilities
                '.transform-3d': {
                    'transform-style': 'preserve-3d',
                },
                '.backface-hidden': {
                    'backface-visibility': 'hidden',
                },

                // Tilt effect
                '.tilt': {
                    'transform-style': 'preserve-3d',
                    transition: 'transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1)',
                },

                // Magnetic effect
                '.magnetic': {
                    transition: 'transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1)',
                },

                // Ripple container
                '.ripple-container': {
                    position: 'relative',
                    overflow: 'hidden',
                },
                '.ripple': {
                    position: 'absolute',
                    'border-radius': '50%',
                    background: 'rgba(255, 255, 255, 0.3)',
                    'pointer-events': 'none',
                    animation: 'ripple 0.6s ease-out',
                },

                // Skeleton loader
                '.skeleton': {
                    background:
                        'linear-gradient(90deg, rgba(51, 65, 85, 0.4) 0%, rgba(71, 85, 105, 0.4) 50%, rgba(51, 65, 85, 0.4) 100%)',
                    'background-size': '200% 100%',
                    animation: 'shimmer 2s linear infinite',
                    'border-radius': '0.5rem',
                },

                // Noise texture overlay
                '.noise-texture': {
                    position: 'relative',
                    '&::before': {
                        content: '""',
                        position: 'absolute',
                        inset: '0',
                        'background-image': 'url(/noise.svg)',
                        'background-repeat': 'repeat',
                        opacity: '0.05',
                        'pointer-events': 'none',
                    },
                },

                // Mesh gradient backgrounds
                '.mesh-gradient': {
                    background:
                        'radial-gradient(at 40% 20%, rgba(59, 130, 246, 0.3) 0px, transparent 50%), radial-gradient(at 80% 0%, rgba(217, 70, 239, 0.3) 0px, transparent 50%), radial-gradient(at 0% 50%, rgba(16, 185, 129, 0.3) 0px, transparent 50%)',
                    'background-size': '200% 200%',
                    animation: 'meshMove 20s ease infinite',
                },
                '.mesh-gradient-animated': {
                    background:
                        'radial-gradient(at 40% 20%, rgba(59, 130, 246, 0.4) 0px, transparent 50%), radial-gradient(at 80% 0%, rgba(217, 70, 239, 0.4) 0px, transparent 50%), radial-gradient(at 0% 50%, rgba(16, 185, 129, 0.4) 0px, transparent 50%), radial-gradient(at 50% 50%, rgba(239, 68, 68, 0.2) 0px, transparent 50%)',
                    'background-size': '200% 200%',
                    animation: 'gradientShift 15s ease infinite',
                },

                // Performance hints
                '.gpu-accelerated': {
                    transform: 'translateZ(0)',
                    'will-change': 'transform',
                },
                '.smooth-rendering': {
                    '-webkit-font-smoothing': 'antialiased',
                    '-moz-osx-font-smoothing': 'grayscale',
                },

                // Scroll snap
                '.snap-x': {
                    'scroll-snap-type': 'x mandatory',
                    'scroll-behavior': 'smooth',
                },
                '.snap-y': {
                    'scroll-snap-type': 'y mandatory',
                    'scroll-behavior': 'smooth',
                },
                '.snap-start': {
                    'scroll-snap-align': 'start',
                },
                '.snap-center': {
                    'scroll-snap-align': 'center',
                },

                // CSS containment for performance
                '.contain-layout': {
                    contain: 'layout',
                },
                '.contain-paint': {
                    contain: 'paint',
                },
                '.contain-strict': {
                    contain: 'strict',
                },

                // Focus visible (accessibility)
                '.focus-ring': {
                    '&:focus-visible': {
                        outline: '2px solid rgba(59, 130, 246, 0.6)',
                        'outline-offset': '2px',
                        'border-radius': '0.375rem',
                    },
                },
                '.focus-ring-accent': {
                    '&:focus-visible': {
                        outline: '2px solid rgba(217, 70, 239, 0.6)',
                        'outline-offset': '2px',
                        'border-radius': '0.375rem',
                    },
                },
            };
            addUtilities(newUtilities);
        },
    ],
};
