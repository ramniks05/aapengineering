<?php

return [
    'phone' => env('COMPANY_PHONE', '+91 98765 43210'),
    'email' => env('COMPANY_EMAIL', 'info@aapengineerings.com'),
    'support_email' => env('COMPANY_SUPPORT_EMAIL', 'projects@aapengineerings.com'),
    'address' => env('COMPANY_ADDRESS', 'Office No. 204, Green Tech Plaza, Hinjewadi Phase 1, Pune, Maharashtra 411057'),
    'city' => env('COMPANY_CITY', 'Pune'),
    'hours' => env('COMPANY_HOURS', 'Mon – Sat: 9:30 AM – 6:30 PM'),
    'whatsapp' => env('COMPANY_WHATSAPP', '919876543210'),
    'whatsapp_message' => env('COMPANY_WHATSAPP_MESSAGE', 'Hello AAP Engineerings, I would like to discuss an electrical project.'),
    // Google Maps embed (no API key required). Replace when you have the final office pin.
    'map_embed_url' => env(
        'COMPANY_MAP_EMBED_URL',
        'https://maps.google.com/maps?q=Hinjewadi%20Phase%201%2C%20Pune%2C%20Maharashtra&t=&z=14&ie=UTF8&iwloc=&output=embed'
    ),
    'map_link' => env(
        'COMPANY_MAP_LINK',
        'https://www.google.com/maps/search/?api=1&query=Hinjewadi+Phase+1,+Pune,+Maharashtra'
    ),
];
