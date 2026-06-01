<?php

$experts = [

    [
        'image' => 'https://images.unsplash.com/photo-1489278353717-f64c6ee8a4d2?w=400&h=500&fit=crop&auto=format',
        'name' => 'Amara Osei',
        'certification' => 'NBC-HWC · 8 years',
        'role' => 'Wellness Coach',
        'specialty' => 'Holistic Health & Nutrition'
    ],

    [
        'image' => 'https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?w=400&h=500&fit=crop&auto=format',
        'name' => "James O'Connor",
        'certification' => 'FMS-L2 · 11 years',
        'role' => 'Mobility Specialist',
        'specialty' => 'Movement Optimization'
    ],

    [
        'image' => 'https://images.unsplash.com/photo-1548690312-e3b507d8c110?w=400&h=500&fit=crop&auto=format',
        'name' => 'Sofia Marchetti',
        'certification' => 'PMA-CPT · 13 years',
        'role' => 'Pilates Instructor',
        'specialty' => 'Clinical Pilates & Reformer'
    ],

    [
        'image' => 'https://images.unsplash.com/photo-1526080652727-5b77f74eacd2?w=400&h=500&fit=crop&auto=format',
        'name' => 'Daniel Krause',
        'certification' => 'CSCS · 15 years',
        'role' => 'Performance Coach',
        'specialty' => 'Elite Athletic Development'
    ],

    [
    'image' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=400&h=500&fit=crop&auto=format',
    'name' => 'Sarah Mitchell',
    'certification' => 'RYT-500 · 12 years',
    'role' => 'Senior Yoga Instructor',
    'specialty' => 'Vinyasa Flow & Restorative'
],

[
    'image' => 'https://images.unsplash.com/photo-1560250097-0b93528c311a?w=400&h=500&fit=crop&auto=format',
    'name' => 'Marcus Chen',
    'certification' => 'NASM-CPT · 10 years',
    'role' => 'Lead Fitness Coach',
    'specialty' => 'HIIT & Functional Training'
],

[
    'image' => 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=400&h=500&fit=crop&auto=format',
    'name' => 'Emily Rodriguez',
    'certification' => 'MCSP · 14 years',
    'role' => 'Senior Physiotherapist',
    'specialty' => 'Sports Injury & Rehabilitation'
],

[
    'image' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=400&h=500&fit=crop&auto=format',
    'name' => 'David Park',
    'certification' => 'CSCS · 9 years',
    'role' => 'Strength Coach',
    'specialty' => 'Powerlifting & Athletic Performance'
]
];
?>

<section id="experts" class="experts">

    <div class="experts__header">

        <div>

            <p class="experts__subtitle">
                OUR TEAM
            </p>

            <h2 class="experts__heading">
                Meet Our
                <br>
                <span>Expert Specialists.</span>
            </h2>

        </div>

        <div class="experts__controls">

            <button
                    id="expertsPrev"
                    class="experts__button experts__button--prev">

                <i class="fa-solid fa-chevron-left"></i>
            </button>

            <button
                    id="expertsNext"
                    class="experts__button experts__button--next">

                <i class="fa-solid fa-chevron-right"></i>
            </button>

        </div>

    </div>

    <div class="experts__carousel">

        <div class="experts__track">

            <?php foreach ($experts as $expert): ?>

                <?php

                $image = $expert['image'];
                $name = $expert['name'];
                $certification = $expert['certification'];
                $role = $expert['role'];
                $specialty = $expert['specialty'];

                include __DIR__ . '/../expert_card.php';

                ?>

            <?php endforeach; ?>

        </div>

    </div>

    <div class="experts__dots">

        <span class="experts__dot"></span>
        <span class="experts__dot"></span>
        <span class="experts__dot experts__dot--active"></span>
        <span class="experts__dot"></span>
        <span class="experts__dot"></span>

    </div>

</section>
