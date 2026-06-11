<?php
/**
 * * Variabile trimise din SessionsPageController:
 *
 * @var string $requestedDate data specificata in URL sau data de azi
 * @var \DateTime $startOfWeek DateTime reprezentand ziua de luni a saptamanii selectate
 * @var \DateTime $endOfWeek DateTime reprezentand ziua de duminica a saptamanii selectate
 * @var string $prevWeek data zilei de luni din saptamana precedenta pentru butonul previous
 * @var string $nextWeek data zilei de luni din saptamana urmatoare pentru butnoul next
 * @var object $trainerData date despre trainer daca userul logat e trainer, false daca nu e trainer
 * @var array $allTrainers toti trainerii
 * @var array $availableRooms toate salile available pentru trainer de acel tip, daca nu e trainer va fi empty
 * * @var array $weeklySchedule array asociativ ce contine toate datele formatate pentru calendar.
 * $weeklySchedule:
 * [
 * '2026-06-01' => [
 * 'day_name'  => 'Monday',
 * 'day_short' => 'Jun 1',
 * 'sessions'  => [ (Array de obiecte Session)
 * 0 => object(Session) { ->name, ->type, ->trainer_name, ->start_time, ->end_time, ... }
 * ]
 * ],
 * '2026-06-02' => [ ... ], // Marti
 *  tot asa pana duminica
 * ]
 * @var array $sessionParticipantsMap map care asociaza id sesiune cu participantii lui
 * [
 *  (int) $session_id => [
 *  0 => object(stdClass) {
 *  ->id,
 *  ->first_name,
 *  ->last_name,
 *  ->email,
 *  ->profile_picture,
 *  ->booked_at
 *  },
 *  1 => object(stdClass) { ... }
 *  ]
 *  ]
 *
 * @var array $activeSubscriptionData date despre abonamentele active ale utilizatorului curent.
 *  Structura: [ 'can_book_anything' => bool, 'sessions_by_type' => [ 'fitness' => int, 'all' => int, ... ] ]
 * /
 *

 */
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sessions</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="/kim/public/css/global.css?v=1.1">
    <link rel="stylesheet" href="/kim/public/css/booking_page.css?v=1.2">
    <link rel="stylesheet" href="/kim/public/css/session_card.css?v=1.1">
    <link rel="stylesheet" href="/kim/public/css/forms.css">
    <script src="/kim/public/js/session_filters.js" defer></script>
    <script src="/kim/public/js/popup.js" defer></script>
    <script src="/kim/public/js/header.js" defer></script>
    <script src="/kim/public/js/session_create_listener.js" defer></script>
</head>
<body>

<?php include 'components/header.php'; ?>

<?php
require_once __DIR__ . '/../classes/FormField.php';

?>

<?php include 'components/alert.php'; ?>
<div class="booking__container">

    <div class="booking__header">
        <?php if ($_SESSION['user_role'] === 'admin' || $_SESSION['user_role'] === 'trainer'): ?>
            <h1 class="booking__title">Manage Sessions</h1>
            <p class="booking__subtitle">Create, cancel or edit sessions.</p>
        <?php else: ?>
            <h1 class="booking__title">Book a Session</h1>
            <p class="booking__subtitle">Select an available session from the weekly schedule.</p>
        <?php endif; ?>
    </div>

    <div class="booking__controls">

        <!-- navigare -->
        <div class="booking__toolbar">
            <div class="week-nav">
                <a href='/kim/sessions?date=<?= $prevWeek ?>' class="week-nav__btn" id="previous_button"><i
                            class="fa-solid fa-chevron-left"></i></a>
                <div class="week-nav__date-display">
                    <span class="week-nav__range"><?= $startOfWeek->format('M d') ?> – <?= $endOfWeek->format('M d') ?></span>
                    <span class="week-nav__year"><?= $startOfWeek->format('Y') ?></span>
                </div>
                <a href='/kim/sessions?date=<?= $nextWeek ?>' class="week-nav__btn" id="next_button"><i
                            class="fa-solid fa-chevron-right"></i></a>
            </div>

            <a href='/kim/sessions' class="btn__hover-action">Go to Current Week</a>
        </div>

        <!-- filtre si buton create -->
        <div class="booking__utilities">

            <div class="booking__filters">
                <!-- filtrul abonamentului -->
                <div class="filter__group">
                    <span class="filter__label">Membership Type</span>
                    <div class="filter__buttons">
                        <!-- active pt buton selectat -->
                        <button class="filter__btn active" data-filter="all"><i class="fa-solid fa-layer-group"></i> All
                            Types
                        </button>
                        <button class="filter__btn" data-filter="fitness"><i class="fa-solid fa-dumbbell"></i> Fitness
                        </button>
                        <button class="filter__btn" data-filter="strength"><i class="fa-solid fa-weight-hanging"></i>
                            Strength
                        </button>
                        <button class="filter__btn" data-filter="physiotherapy"><i class="fa-solid fa-heart-pulse"></i>
                            Physiotherapy
                        </button>
                    </div>
                </div>

                <!-- filtru anternor -->
                <div class="filter__group">
                    <label for="trainer-select" class="filter__label">Trainer</label>
                    <div class="filter__select-wrapper">
                        <select id="trainer-select" class="filter__select">
                            <option value="all">All Trainers</option>
                            <?php foreach ($allTrainers as $trainer): ?>
                                <option class="trainer-select-option" data-type="<?= $trainer->specialization ?>"
                                        value="<?= $trainer->trainer_id ?>"><?= $trainer->first_name . ' ' . $trainer->last_name ?></option>
                            <?php endforeach; ?>

                        </select>
                    </div>
                </div>

            </div>

            <div class="create__group">

                <?php if ($_SESSION['user_role'] === 'trainer' || $_SESSION['user_role'] === 'admin'): ?>
                    <?php

                    $title = 'New Session';
                    $submit = 'Create Session';
                    $action = '/kim/api/sessions/create';
                    $popupId = 'popupOverlay_createSession';
                    $isAdmin = $_SESSION['user_role'] === 'admin';


                    $titleField = FormField::create('Title', 'session_title')->type('text')
                            ->placeholder('Workout #1')->required(true);

                    $formBody[] = $titleField;

                    if ($isAdmin) {
                        $trainerOptions = [];

                        foreach ($allTrainers as $trainer) {
                            $trainerOptions[$trainer->trainer_id] = $trainer->first_name . ' ' . $trainer->last_name . ' (' . ucfirst($trainer->specialization) . ')';
                        }

                        $trainerField = FormField::create('Trainer', 'session_trainer')->type('select')
                                ->required(true)->placeholder('Select a trainer')->options($trainerOptions);

                        $formBody[] = $trainerField;
                    }

                    $roomOptions = [];
                    $jsRoomData = [];

                    $roomsToIterate = $isAdmin ? models\Room::getAllActiveRooms() : $availableRooms;

                    if ($roomsToIterate) {
                        foreach ($roomsToIterate as $room) {
                            $roomOptions[$room->id] = $room->name . ' (Capacity: ' . $room->capacity . ')';

                            $jsRoomData[$room->id] = [
                                    'equipment' => $room->equipment_list ? $room->equipment_list : 'No functional equipment available',
                                    'type' => $room->type
                            ];
                        }
                    }


                    $roomField = FormField::create('Room', 'session_room')->type('select')
                            ->required('true')->placeholder('Select a room')->options($roomOptions);

                    $formBody[] = $roomField;

                    $startTimeField = FormField::create('Start Time', 'start_time')
                            ->type('datetime-local')
                            ->required(true)
                            ->limits(date('Y-m-d\TH:i'), null); // min tre sa fie egal cu current time

                    $endTimeField = FormField::create('End Time', 'end_time')
                            ->type('datetime-local')
                            ->required(true);

                    $capacityField = FormField::create('Max Capacity', 'max_capacity')
                            ->type('number')
                            ->placeholder('e.g. 15')
                            ->limits(1, 100)
                            ->required(true);


                    $formBody[] = $startTimeField;
                    $formBody[] = $endTimeField;
                    $formBody[] = $capacityField;

                    $jsTrainerData = [];
                    if ($isAdmin) {
                        foreach ($allTrainers as $trainer) {
                            $jsTrainerData[$trainer->trainer_id] = [
                                    'specialization' => $trainer->specialization
                            ];
                        }
                    }

                    ?>

                    <div id="hidden_room_data" style="display: none"
                         data-payload="<?= htmlspecialchars(json_encode($jsRoomData)) ?>"></div>
                    <?php if ($isAdmin): ?>
                        <div id="hidden_trainer_data" style="display: none"
                             data-payload='<?= htmlspecialchars(json_encode($jsTrainerData), ENT_QUOTES, 'UTF-8') ?>'></div>
                    <?php endif; ?>

                    <?php include 'components/popup.php' ?>


                    <button type="button" class="btn__hover-action js-open-popup"
                            data-target="popupOverlay_createSession"
                            <?php if (count($availableRooms) === 0 && !$isAdmin): ?>
                                disabled
                                title="Can't create session, there are no rooms active for your specialization"
                            <?php endif; ?>
                    >
                        Create Session
                    </button>

                <?php endif; ?>
            </div>
        </div>


    </div>

    <!-- grid principal -->
    <div class="schedule__wrapper">
        <!--        SCHIMB DATA TYPE DIN JS CA SA VAD CE SESIUNI RANDEZ PE BAZA FILTRU-->
        <div class="schedule__grid" data-type="all">

            <?php foreach ($weeklySchedule as $day): ?>

                <div class="schedule__column">
                    <!-- header zi -->
                    <div class="schedule__column-header">
                        <span class="schedule__day-name"><?= $day['day_name'] ?></span>
                        <span class="schedule__day-date"><?= $day['day_short'] ?></span>
                        <span class="schedule__session-count"><?= count($day['sessions']) ?> session<?= count($day['sessions']) != 1 ? 's' : '' ?></span>
                    </div>

                    <!-- lista sesiuni pt aceasta zi -->
                    <div class="schedule__column-body">
                        <div class="schedule__column-text"

                                <?php if (!empty($day['sessions'])): ?>
                                    style="display: none"
                                <?php endif; ?>
                        >
                            <?php if (empty($day['sessions'])): ?>

                                No sessions

                            <?php endif; ?>
                        </div>
                        <?php foreach ($day['sessions'] as $session): ?>
                            <?php $isSessionsPage = true; ?>
                            <?php include 'components/session_card.php' ?>

                        <?php endforeach; ?>
                    </div>
                </div>

            <?php endforeach; ?>


        </div>
    </div>
</div>

</body>
</html>
