<?php
require_once
    __DIR__ .
    '/../../classes/FormField.php';
?>

<section class="rooms">

    <div class="rooms__header">

        <div>

            <h1 class="rooms__title">
                Facilities & Equipment
            </h1>

            <p class="rooms__subtitle">
                Manage rooms and equipment.
            </p>

        </div>

        <button
            class="
                rooms__add-button
                js-open-popup
            "
            data-target="popupOverlay_addRoom"
        >
            Add Room
        </button>

    </div>

    <div class="rooms__grid">

        <?php foreach($rooms as $room): ?>

            <?php

            $icon =
                match($room->type){

                    'fitness' =>
                    'fa-dumbbell',

                    'strength' =>
                    'fa-weight-hanging',

                    'physiotherapy' =>
                    'fa-heart-pulse',

                    default =>
                    'fa-building'
                };

            ?>

            <div class="room-card">

                <div class="room-card__top">

                    <div class="room-card__icon">

                        <i
                            class="
                                fa-solid
                                <?= $icon ?>
                            "
                        ></i>

                    </div>

                    <span
                        class="
                            room-card__status
                            <?= $room->is_active
                            ? 'room-card__status--active'
                            : 'room-card__status--inactive'
                        ?>
                        "
                    >

                        <span
                            class="room-card__dot"
                        ></span>

                        <?= $room->is_active
                            ? 'Available'
                            : 'Inactive'
                        ?>

                    </span>

                </div>

                <h3
                    class="room-card__name"
                >
                    <?= htmlspecialchars(
                        $room->name
                    ) ?>
                </h3>

                <p
                    class="room-card__type"
                >
                    <?= ucfirst(
                        $room->type
                    ) ?>
                </p>

                <div
                    class="room-card__stats"
                >

                    <span>

                        <i
                            class="
                                fa-solid
                                fa-users
                            "
                        ></i>

                        <?= $room->current_bookings ?>
                        /
                        <?= $room->capacity ?>

                    </span>

                    <span>

                        <i
                            class="
                                fa-regular
                                fa-calendar
                            "
                        ></i>

                        <?= $room->sessions_count ?>
                        this week

                    </span>

                </div>

                <?php

                $occupancyPercent =
                    $room->capacity > 0
                        ? min(
                        100,
                        (
                            $room->current_bookings
                            * 100
                        )
                        /
                        $room->capacity
                    )
                        : 0;

                ?>

                <div
                    class="room-card__progress"
                >

                    <div
                        class="room-card__progress-fill"
                        style="
                            width:
                        <?= $occupancyPercent ?>%;
                            "
                    ></div>

                </div>

                <div
                    class="room-card__actions"
                >

                    <button
                        class="
                            room-card__edit
                            js-open-popup
                        "
                        data-target="popupOverlay_<?= $room->id ?>"
                    >

                        <i
                            class="
                                fa-regular
                                fa-pen-to-square
                            "
                        ></i>

                        Edit Room

                    </button>

                </div>

                <div
                    class="
                        room-card__equipment-list
                    "
                >

                    <?php

                    $equipment =
                        explode(
                            ',',
                            $room->equipment_list ?? ''
                        );

                    foreach(
                        $equipment
                        as $item
                    ):

                        $item =
                            trim($item);

                        if(
                            empty($item)
                        ){
                            continue;
                        }

                        ?>

                        <span
                            class="
                                room-card__equipment-item
                            "
                        >
                            <?= htmlspecialchars(
                                $item
                            ) ?>
                        </span>

                    <?php endforeach; ?>

                </div>

            </div>

            <?php

            $title =
                'Edit Room';

            $submit =
                'Save';

            $action =
                '/kim/api/rooms/update';

            $infoText =
                null;

            $idField =
                FormField::create(
                    '',
                    'room_id'
                )
                    ->type('hidden')
                    ->value($room->id);

            $nameField =
                FormField::create(
                    'Room Name',
                    'name'
                )
                    ->required(true)
                    ->value($room->name);

            $capacityField =
                FormField::create(
                    'Capacity',
                    'capacity'
                )
                    ->type('number')
                    ->required(true)
                    ->value($room->capacity);

            $typeField =
                FormField::create(
                    'Room Type',
                    'type'
                )
                    ->type('select')
                    ->required(true)
                    ->options([

                        'fitness' =>
                            'Fitness',

                        'strength' =>
                            'Strength',

                        'physiotherapy' =>
                            'Physiotherapy'
                    ])
                    ->value($room->type);

            $statusField =
                FormField::create(
                    'Status',
                    'is_active'
                )
                    ->type('select')
                    ->required(true)
                    ->options([

                        '1' =>
                            'Active',

                        '0' =>
                            'Inactive'
                    ])
                    ->value($room->is_active);

            $formBody = [

                $idField,

                $nameField,

                $capacityField,

                $typeField,

                $statusField
            ];

            $popupId =
                'popupOverlay_' .
                $room->id;

            include
                __DIR__ .
                '/../popup.php';

            ?>

        <?php endforeach; ?>

    </div>

    <?php

    $title =
        'Add Room';

    $submit =
        'Create Room';

    $action =
        '/kim/api/rooms/create';

    $infoText =
        null;

    $nameField =
        FormField::create(
            'Room Name',
            'name'
        )
            ->required(true);

    $capacityField =
        FormField::create(
            'Capacity',
            'capacity'
        )
            ->type('number')
            ->required(true);

    $typeField =
        FormField::create(
            'Room Type',
            'type'
        )
            ->type('select')
            ->required(true)
            ->options([

                'fitness' =>
                    'Fitness',

                'strength' =>
                    'Strength',

                'physiotherapy' =>
                    'Physiotherapy'
            ]);

    $statusField =
        FormField::create(
            'Status',
            'is_active'
        )
            ->type('select')
            ->required(true)
            ->options([

                '1' =>
                    'Active',

                '0' =>
                    'Inactive'
            ])
            ->value(1);

    $formBody = [

        $nameField,

        $capacityField,

        $typeField,

        $statusField
    ];

    $popupId =
        'popupOverlay_addRoom';

    include
        __DIR__ .
        '/../popup.php';

    ?>

</section>