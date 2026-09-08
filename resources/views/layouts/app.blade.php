<!DOCTYPE html><html class="light" lang="es"><head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>SISADM</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&amp;family=Manrope:wght@600;700;800&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">

<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.3.0/min/dropzone.min.css">

<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "outline-variant": "#c2c6d4",
                        "on-background": "#191c1e",
                        "surface-bright": "#f7f9fb",
                        "secondary-container": "#d0e1fb",
                        "surface-container-high": "#e6e8ea",
                        "error": "#ba1a1a",
                        "on-secondary-fixed": "#0b1c30",
                        "inverse-surface": "#2d3133",
                        "on-primary": "#ffffff",
                        "inverse-primary": "#a9c7ff",
                        "on-primary-fixed": "#001b3d",
                        "tertiary": "#005237",
                        "surface-variant": "#e0e3e5",
                        "secondary": "#505f76",
                        "on-error-container": "#93000a",
                        "on-primary-container": "#c8daff",
                        "secondary-fixed-dim": "#b7c8e1",
                        "on-surface": "#191c1e",
                        "secondary-fixed": "#d3e4fe",
                        "background": "#f7f9fb",
                        "tertiary-container": "#006d4a",
                        "surface-container": "#eceef0",
                        "primary": "#00478d",
                        "on-secondary-fixed-variant": "#38485d",
                        "on-tertiary": "#ffffff",
                        "primary-fixed": "#d6e3ff",
                        "on-tertiary-container": "#65f2b5",
                        "on-primary-fixed-variant": "#00468c",
                        "surface-container-lowest": "#ffffff",
                        "tertiary-fixed": "#6ffbbe",
                        "primary-container": "#005eb8",
                        "inverse-on-surface": "#eff1f3",
                        "surface-container-low": "#f2f4f6",
                        "on-secondary": "#ffffff",
                        "on-surface-variant": "#424752",
                        "on-error": "#ffffff",
                        "tertiary-fixed-dim": "#4edea3",
                        "on-tertiary-fixed-variant": "#005236",
                        "surface-container-highest": "#e0e3e5",
                        "surface-tint": "#005db6",
                        "outline": "#727783",
                        "surface": "#f7f9fb",
                        "on-tertiary-fixed": "#002113",
                        "primary-fixed-dim": "#a9c7ff",
                        "on-secondary-container": "#54647a",
                        "surface-dim": "#d8dadc",
                        "error-container": "#ffdad6"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.125rem",
                        "lg": "0.25rem",
                        "xl": "0.5rem",
                        "full": "0.75rem"
                    },
                    "spacing": {
                        "gutter": "24px",
                        "xs": "4px",
                        "sm": "8px",
                        "max-width": "1440px",
                        "base": "4px",
                        "margin-mobile": "16px",
                        "lg": "24px",
                        "xl": "48px",
                        "md": "16px",
                        "margin-desktop": "32px"
                    },
                    "fontFamily": {
                        "headline-lg-mobile": ["Manrope"],
                        "label-md": ["Inter"],
                        "body-lg": ["Inter"],
                        "body-md": ["Inter"],
                        "headline-md": ["Manrope"],
                        "headline-lg": ["Manrope"],
                        "body-sm": ["Inter"],
                        "label-sm": ["Inter"],
                        "headline-sm": ["Manrope"]
                    },
                    "fontSize": {
                        "headline-lg-mobile": ["24px", {"lineHeight": "32px", "letterSpacing": "-0.01em", "fontWeight": "700"}],
                        "label-md": ["14px", {"lineHeight": "20px", "letterSpacing": "0.05em", "fontWeight": "600"}],
                        "body-lg": ["18px", {"lineHeight": "28px", "fontWeight": "400"}],
                        "body-md": ["16px", {"lineHeight": "24px", "fontWeight": "400"}],
                        "headline-md": ["24px", {"lineHeight": "32px", "fontWeight": "600"}],
                        "headline-lg": ["32px", {"lineHeight": "40px", "letterSpacing": "-0.02em", "fontWeight": "700"}],
                        "body-sm": ["14px", {"lineHeight": "20px", "fontWeight": "400"}],
                        "label-sm": ["12px", {"lineHeight": "16px", "fontWeight": "500"}],
                        "headline-sm": ["20px", {"lineHeight": "28px", "fontWeight": "600"}]
                    }
                },
            },
        }
    </script>
<style>
        .material-symbols-outlined {
            font-variation-settings: "FILL" 0, "wght" 400, "GRAD" 0, "opsz" 24
        }
        .glass-panel {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.3)
        }
    </style>
</head>


<body class="bg-surface text-on-surface font-body-md min-h-screen">


@yield('content')


{{-- SCRIPTS GLOBALES --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>
@stack('scripts')

</body>


</html>
