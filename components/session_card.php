<?php

/**
 * @var object $session
 * @var bool $isSessionsPage // daca vine din session page ca sa fie cardul putin diferit
 * @var array $availableRooms toate salile available pentru trainer de acel tip, daca nu e trainer va fi empty
 * @var array $sessionParticipantsMap map care asociaza id sesiune cu participantii lui
 *  [
 *   (int) $session_id => [
 *   0 => object(stdClass) {
 *   ->id,
 *   ->first_name,
 *   ->last_name,
 *   ->email,
 *   ->profile_picture,
 *   ->booked_at
 *   },
 *   1 => object(stdClass) { ... }
 *   ]
 *   ]
 * POATE SA FIE NULL DACA VINE DIN DASHBOARD
 *
 * @var array $activeSubscriptionData date despre abonamentele active ale utilizatorului curent.
 *   Structura: [ 'can_book_anything' => bool, 'sessions_by_type' => [ 'fitness' => int, 'all' => int, ... ] ]
 *  /
 * POATE SA FIE NULL DACA VINE DIN DASHBOARD
 */


$start_time = strtotime($session->start_time);
$end_time = strtotime($session->end_time);

$icons = [
        'fitness' => 'fa-solid fa-dumbbell',
        'physiotherapy' => 'fa-solid fa-heart-pulse',
        'strength' => 'fa-solid fa-weight-hanging'
];

$statusIcons = [
        'planned' => 'fa-regular fa-clock',
        'ongoing' => 'fa-solid fa-bolt',
        'canceled' => 'fa-regular fa-circle-xmark',
        'completed' => 'fa-regular fa-circle-check'
];

$status = isset($session->status) ? $session->status : 'planned';

$currentStatusIcon = isset($statusIcons[$status]) ? $statusIcons[$status] : 'fa-solid fa-circle-info';
$iconClass = isset($icons[$session->session_type]) ? $icons[$session->session_type] : 'fa-solid fa-dumbbell';
$userRole = $_SESSION['user_role'];


$cardClasses = 'session__card';
$listClasses = 'session__details-list';
$customStyleCard = '';

if (!isset($isSessionsPage)) {
    $isSessionsPage = false;
}
if ($isSessionsPage) {
    $cardClasses .= ' theme-' . $session->session_type;
    $customStyleCard = 'background-color: var(--card-light);';
}

$userId = $_SESSION['user_id'];
$isUserAlreadyBooked = false;
if (isset($sessionParticipantsMap)) {
    foreach ($sessionParticipantsMap[$session->session_id] as $sessionParticipant) {
        if ($sessionParticipant->id == $userId) {
            $isUserAlreadyBooked = true;
        }
    }
} else {
    //daca nu e setat inseamna ca sunt pe pagina de dashboard, deci implicit sunt booked deja
    $isUserAlreadyBooked = true;
}

$canBookThisSession = false;
if (isset($activeSubscriptionData)) {
    $specificSessionsLeft = isset($activeSubscriptionData['sessions_by_type'][$session->session_type]) ? $activeSubscriptionData['sessions_by_type'][$session->session_type] : 0;
    $allSessionsLeft = isset($activeSubscriptionData['sessions_by_type']['all']) ? $activeSubscriptionData['sessions_by_type']['all'] : 0;

    if ($specificSessionsLeft > 0 || $allSessionsLeft > 0) {
        $canBookThisSession = true;
    }
}

$canCancelClass = false;

if ($userRole === 'admin') {
    $canCancelClass = true;
} else if ($userRole === 'trainer') {
    $currentTrainerId = isset($_SESSION['trainer_id']) ? $_SESSION['trainer_id'] : null;

    if ($currentTrainerId == $session->trainer_id) {
        $canCancelClass = true;
    }
}

//$start_time = time();
//$status = 'canceled';

require_once __DIR__ . '/../classes/FormField.php';

?>
<!--session__card -->
<div class="<?= $cardClasses ?>" style="<?= $customStyleCard ?>" data-type="<?= $session->session_type ?>"
     data-trainer="<?= $session->trainer_id ?>">

    <!--    top are iconita si status-->
    <div class="session__top">
        <?php if (!$isSessionsPage): ?>
            <div class="session__icon-wrapper <?= "theme-" . $session->session_type ?>">
                <i class="<?= $iconClass ?>"></i>
            </div>
        <?php endif; ?>
        <div class="session__status-badge status-<?= $status ?>">
            <i class="<?= $currentStatusIcon ?>"></i> <?= htmlspecialchars(ucfirst($status)) ?>
        </div>

<!--        buton edit pt trainer si admin-->
        <?php if($userRole === 'trainer' || $userRole === 'admin'): ?>
        <button type="button"
                class="session__btn--edit  <?= $canCancelClass ? 'js-open-popup' : '' ?>"
                data-target="popupOverlay_editClass_<?= $session->session_id ?>"
                <?php if (!$canCancelClass): ?>
                    disabled
                    title="You can only edit your own classes"
                <?php endif; ?>
        >
            <i class="fa-solid fa-pen" style="margin-right: 5px; font-size: 0.85em;"></i> Edit
        </button>

        <?php
        $title = 'Edit Session';
        $submit = 'Save Changes';
        $action = '/kim/api/sessions/edit';
        $popupId = 'popupOverlay_editClass_' . $session->session_id;

        $infoText = null;

        $sessionIdField = FormField::create('', 'session_id')->type('hidden')->value($session->session_id);
        $trainerIdField = FormField::create('', 'session_trainer')->type('hidden')->value($session->trainer_id);

        $titleField = FormField::create('Title', 'session_title')
                ->type('text')
                ->value($session->title)
                ->required(true);


        $editRooms = models\Room::getAllActiveRoomsByType($session->session_type);
        $editRoomOptions = [];
        if($editRooms) {
            foreach($editRooms as $r) {
                $editRoomOptions[$r->id] = $r->name . ' (Capacity: ' . $r->capacity . ')';
            }
        }

        $roomField = FormField::create('Room', 'session_room')
                ->type('select')
                ->options($editRoomOptions)
                ->value($session->room_id)
                ->required(true);

        $formattedStart = date('Y-m-d\TH:i', strtotime($session->start_time));
        $formattedEnd = date('Y-m-d\TH:i', strtotime($session->end_time));

        $startTimeField = FormField::create('Start Time', 'start_time')
                ->type('datetime-local')
                ->value($formattedStart)
                ->required(true);

        $endTimeField = FormField::create('End Time', 'end_time')
                ->type('datetime-local')
                ->value($formattedEnd)
                ->required(true);

        $capacityField = FormField::create('Max Capacity', 'max_capacity')
                ->type('number')
                ->limits(1, 100)
                ->value($session->max_capacity)
                ->required(true);

        $formBody = [
                $sessionIdField,
                $trainerIdField,
                $titleField,
                $roomField,
                $startTimeField,
                $endTimeField,
                $capacityField
        ];
        ?>

        <?php include 'components/popup.php' ?>

        <?php endif;?>

    </div>

    <!--    titlu, data ora spatiu-->
    <div class="session__main">
        <h3 class="session__title"
                <?php if ($isSessionsPage): ?>
                    style="font-size: 1rem;"
                <?php endif; ?>
        ><?= htmlspecialchars($session->title) ?></h3>

        <div class="<?= $listClasses ?>">

            <?php if (!$isSessionsPage): ?>
                <div class="session__detail-item">
                    <i class="fa-regular fa-calendar"></i>
                    <span><?= date('d M Y', $start_time) ?></span>
                </div>
            <?php else: ?>
                <div class="session__detail-item">
                    <span><?= ucfirst($session->session_type) ?></span>
                </div>

            <?php endif; ?>

            <div class="session__detail-item">
                <i class="fa-regular fa-clock"></i>
                <span><?= date('H:i', $start_time) ?> - <?= date('H:i', $end_time) ?></span>
            </div>

            <div class="session__detail-item">
                <i class="fa-solid fa-location-dot"></i>
                <span><?= htmlspecialchars($session->room_name) ?></span>
            </div>

            <?php if ($isSessionsPage): ?>
                <div class="session__detail-item">
                    <i class="fa-solid fa-user"></i>
                    <span><?= htmlspecialchars($session->trainer_first_name . ' ' . $session->trainer_last_name) ?></span>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="session__divider"></div>

    <!--    cati partiicpanti, trainer ul daca utilziatorul e membru, buton de cancel-->
    <div class="session__bottom">

        <?php if (!$isSessionsPage): ?>
            <?php if ($userRole === 'member'): ?>
                <span class="person__label">Trainer</span>
                <p class="person__name"><?= htmlspecialchars($session->trainer_first_name . ' ' . $session->trainer_last_name) ?></p>
            <?php endif ?>
        <?php endif; ?>

        <div class="session__capacity">
            <div class="capacity__count">
                <i class="fa-solid fa-user-group"></i>
                <span class="count__numbers"><?= htmlspecialchars($session->booked_spots) ?>/<?= htmlspecialchars($session->max_capacity) ?></span>
            </div>
            <span class="capacity__label">participants</span>
        </div>

        <!--        LA TRAINER LA CARD TREBUIE SA ARATE CANCEL BUTON, IAR LA TRAINER TREBUIE VERIFICAT DACA EL ARE SESIUNEA-->
        <?php if ($userRole === 'trainer' || $userRole === 'admin'): ?>
            <div class="session__buttons-row">

                <?php

                $title = 'Session details';
                $submit = 'Close';
                $action = '';
                $popupId = 'popupOverlay_sessionDetails_' . $session->session_id;

                // CONSTRUIM LISTA DE PARTICIPANTI cu scroll vertical
                $participantsHtml = '<div style="max-height: 250px; overflow-y: auto; margin-bottom: 20px; padding-right: 10px;">';
                $participantsHtml .= '<h4 style="margin-bottom: 10px; font-size: 1rem;"><i class="fa-solid fa-users" style="color: var(--kim-dark-pink);"></i> Booked Members</h4>';

                if (isset($sessionParticipantsMap[$session->session_id]) && count($sessionParticipantsMap[$session->session_id]) > 0) {
                    $participantsHtml .= '<ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 10px;">';

                    foreach ($sessionParticipantsMap[$session->session_id] as $participant) {
                        $name = htmlspecialchars($participant->first_name . ' ' . $participant->last_name);
                        $email = htmlspecialchars($participant->email);

                        $participantsHtml .= '<li style="display: flex; align-items: center; gap: 15px; padding: 10px; background: white; border-radius: 8px;">';
                        $participantsHtml .= '<i class="fa-solid fa-circle-user" style="font-size: 2rem; color: gray"></i>';
                        $participantsHtml .= '<div style="display: flex; flex-direction: column;">';
                        $participantsHtml .= '<span style="font-weight: bold; font-size: 0.95rem;">' . $name . '</span>';
                        $participantsHtml .= '<span style="font-size: 0.85rem; color: gray;">' . $email . '</span>';
                        $participantsHtml .= '</div>';
                        $participantsHtml .= '</li>';
                    }
                    $participantsHtml .= '</ul>';
                } else {
                    $participantsHtml .= '<p style="color: gray; font-size: 0.9rem;">No members have booked this session yet.</p>';
                }
                $participantsHtml .= '</div>';

               $infoText = $participantsHtml;


                $formBody = [];
                ?>
                <!-- acelasi lucru cu verificare, poti vedea partiicpantii doar la clasa ta-->

                <?php include 'components/popup.php' ?>
                <button type="button" class="session__btn js-open-popup"
                        data-target="popupOverlay_sessionDetails_<?= $session->session_id ?>"
                    <?php if (!$canCancelClass): ?>
                        disabled
                        title="You can only see participants of your own classes"
                    <?php endif; ?>
                >
                    View participants
                </button>

                <?php if ($status === 'planned'): ?>

                    <button type="button"
                            class="session__btn session__btn--danger <?= $canCancelClass ? 'js-open-popup' : '' ?>"
                            data-target="popupOverlay_cancelClass_<?= $session->session_id ?>"

                            <?php if (!$canCancelClass): ?>
                                disabled
                                title="You can only cancel your own classes"
                            <?php endif; ?>
                    >

                        Cancel Class
                    </button>

                    <?php

                    $members = $session->booked_spots === 1 ? 'member has' : 'members have';
                    $title = 'Cancel Class';
                    $submit = 'Yes';
                    $action = '/kim/api/sessions/cancel';
                    $popupId = 'popupOverlay_cancelClass_' . $session->session_id;


                    $infoText = "Are you sure you want to cancel this class? $session->booked_spots $members already booked this session";

                    $sessionIdField = FormField::create('', 'session_id')->type('hidden')->value($session->session_id);

                    $formBody = [$sessionIdField];
                    ?>

                    <?php include 'components/popup.php' ?>

                <?php endif; ?>
            </div>

            <!--        LA MEMBER FIE AI CANCEL BOOKING DACA ESTI BOOKED, FIE BOOK NOW-->
        <?php else: ?>

            <?php if ($isUserAlreadyBooked): ?>
                <!--        APARE BUTONUL DOAR DACA STATUSUL E PLANNED SAU ONGOING-->
                <?php if ($status === 'planned' || $status === 'ongoing'): ?>

                    <?php

                    $title = 'Cancel Booking';
                    $submit = 'Yes';
                    $action = '/kim/api/bookings/cancel';
                    $popupId = 'popupOverlay_cancelBooking_' . $session->session_id;
                    $infoText = "Are you sure you want to cancel this class? ";
                    $infoText .= $start_time < strtotime('+24 hours') ? "Since there are less than 24 hours before this class, you will NOT get your session back" : "You will receive your session back on your membership.";

                    $sessionIdField = FormField::create('', 'session_id')->type('hidden')->value($session->session_id);


                    $formBody = [$sessionIdField];
                    ?>

                    <?php include 'components/popup.php' ?>


                    <button type="button" class="session__btn session__btn--danger js-open-popup"
                            data-target="popupOverlay_cancelBooking_<?= $session->session_id ?>"
                            <?php if (!($status === 'planned' || $status === 'ongoing')): ?>
                                disabled
                                title="Can't cancel this booking anymore"
                            <?php endif; ?>
                    >
                        Cancel Booking
                    </button>

                    <!--                END IF  LA CHECK STATUS-->
                <?php endif; ?>

                <!--            ELSE MA DUC PE RAMURA DACA NU E BOOKED-->
            <?php else: ?>

                <?php

                $title = 'Book session';
                $submit = 'Yes';
                $action = '/kim/api/bookings/book';
                $popupId = 'popupOverlay_book_' . $session->session_id;

                if (isset($activeSubscriptionData)) {
                    $typeSessionsLeft = $activeSubscriptionData['sessions_by_type'][$session->session_type] ?? 0;
                    $allTypesSessionsLeft = $activeSubscriptionData['sessions_by_type']['all'] ?? 0;

                    $infoText = "Are you sure you want to book this class? ";

                    if ($typeSessionsLeft > 0) {
                        $remaining = $typeSessionsLeft - 1;
                        $word = $remaining === 1 ? 'session' : 'sessions';
                        $infoText .= "You will have $remaining $word left on your {$session->session_type} membership.";

                    } else if ($allTypesSessionsLeft > 0) {
                        $remaining = $allTypesSessionsLeft - 1;
                        $word = $remaining === 1 ? 'session' : 'sessions';
                        $infoText .= "You will have $remaining $word left on your 'All' membership.";
                    }
                }


                $sessionIdField = FormField::create('', 'session_id')->type('hidden')->value($session->session_id);


                $formBody = [$sessionIdField];
                ?>

                <?php include 'components/popup.php' ?>


                <!--                    POTI DA BOOK DOAR DACA E PLANNED INCA-->
                <button type="button" class="session__btn js-open-popup"
                        data-target="popupOverlay_book_<?= $session->session_id ?>"
                        <?php if (!($status === 'planned' && $canBookThisSession && $session->booked_spots < $session->max_capacity)): ?>
                            disabled
                            title="<?php if ($status !== 'planned' || $session->booked_spots >= $session->max_capacity): ?>
Can't book this session anymore
<?php else: ?>
You need a <?= $session->session_type ?> membership with enough sessions to book this
<?php endif; ?>
"
                        <?php endif; ?>
                >
                    Book Now
                </button>

                <!-- END IF DACA E DEJA BOOKED SAU NU-->
            <?php endif; ?>

            <!--        END IF LA CAZ MEMBRU-->
        <?php endif; ?>
    </div>

</div>