<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/profile.php';

$defaults = [
    // Демонстрационные данные. Их можно заменить через /admin/settings.php без правки кода.
    'site_name' => 'ÉLAN SKIN',
    'eyebrow' => 'Эстетическая косметология · premium care',
    'headline' => 'Точная косметология. Тихая роскошь вашей кожи.',
    'description' => 'Персональные протоколы ухода, деликатная эстетика и понятный маршрут к здоровому, ухоженному виду кожи — без перегруженных обещаний.',
    'specialist' => 'Специалист эстетической косметологии',
    'city' => 'Санкт-Петербург',
    'phone' => '',
    'telegram' => '',
    'whatsapp' => '',
    'address' => '',
    'booking_url' => '',
    'seo' => [
        'title' => 'ÉLAN SKIN — премиальная эстетическая косметология',
        'description' => 'Персональные программы ухода за кожей, профессиональные процедуры и эстетическая косметология. Демонстрационный premium-сайт косметолога.',
        'robots' => 'noindex,nofollow',
    ],
];

return site_profile_load($defaults);
