<?php
/**
 * @var string $title -> title trebuie sa fie titlul form-ului din header, de exemplu 'Edit'
 * @var string $action -> action trebuie sa fie ce endpoint apeleaza din POST, exemplu '/kim/login'
 * @var string $submit -> ce text este pe butonul de submit
 * @var string $infoText -> daca vreau ca popup-ul sa mi scrie niste text, poate fi null
 * @var array<FormField> $formBody -> array de instante de FormField, va arata de exemplu
 * @var string $popupId -> string pentru a putea genera mai multe popup-uri, va fi de forma popupOverlay_<id>
 * CE TREBUIE SA AI IN HEAD LA FISIERUL HTML/PHP?
 *     * <link rel="stylesheet" href="/kim/public/css/global.css?v=1.1"> -> pt style
 *     * <script src="/kim/public/js/popup.js" defer></script>  -> pt logica de inchidere
 *
 * CE TREBUIE SA AI FIX INAINTE DE A APELA ACEASTA COMPONENTA? Exemplu:
 * <?php
 *      require_once __DIR__ . '/../classes/FormField.php';
 *      $title = 'Title';
 *      $submit = 'Submit';
 *      $action = '/kim/login';
 *      $daysField = FormField::create('Days to suspend', 'suspend_days')
 *              ->type('number')
 *              ->required(true)
 *              ->limits(1,$subscription->suspending_days_left);
 *
     * $trainerField = FormField::create('Trainer', 'session_trainer')->type('select')       -> select care da un dropdown
     * ->required(true)->placeholder('Select a trainer')->options($trainerOptions);
     *
 *      $formBody = [
 *          $daysField,
 *          $trainerField
 *      ];
 *      $popupId = 'popupOverlay_' . $id -> unde id este o variabila luata din baza de date, id-ul utilizatorului sau ce ar mai ajuta
 *
 *
 * ?>
 *
 *   BUTONUL PE CARE DESCHIZI POPUP-UL TREBUIE SA AIBA UN ATRIBUT
 *      data-target="popupOverlay_<?= id ?>" unde id e din db
 *
 * SI APOI APELEZ COMPONENTA CU
 *
 * <?php include 'components/popup.php'; ?>
 */
?>

<div class="popup__overlay popup__hidden" id="<?= $popupId ?>">
    <div class="popup_window">
        <div class="popup__header">
            <p class="popup__title"><?= $title ?></p>
            <button class="popup__closebutton">
                <i class="fa-solid fa-x"></i>
            </button>
        </div>
        <div class="form__container">
            <?php if (isset($infoText)): ?>
                <div class="form__textbox">
                    <?= $infoText ?>
                </div>

            <?php endif; ?>
            <form action="<?= $action ?>" method="POST" class="form__body">

                <?php foreach ($formBody as $field): ?>
                    <div class="form__group" <?= $field->type === 'hidden' ? 'style="display: none;"' : '' ?>>

                        <?php if ($field->type !== 'hidden'): ?>
                            <label class="form__label" for="<?= $field->id ?>"><?= $field->label ?></label>
                        <?php endif; ?>

<!--                        daca e dropdown -->
                        <?php if ($field->type === 'select'): ?>
                            <select name="<?= $field->id ?>"
                                    id="<?= $field->id ?>"
                                    <?= $field->required ? 'required' : '' ?>>

                                <option value="" disabled <?= $field->value === null ? 'selected' : '' ?>>
                                    <?= $field->placeholder ?: 'Select an option' ?>
                                </option>

                                <?php foreach ($field->options as $val => $text): ?>
                                    <option value="<?= htmlspecialchars($val) ?>" <?= ($field->value !== null && $field->value == $val) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($text) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>

                        <?php else: ?>
                            <input type="<?= $field->type ?>"
                                   name="<?= $field->id ?>"
                                   id="<?= $field->id ?>"
                                   value="<?= $field->value ?>"
                                   placeholder="<?= $field->placeholder ?>"
                                    <?= $field->required ? 'required' : '' ?>

                                    <?= isset($field->min) ? 'min="' . $field->min . '"' : '' ?>
                                    <?= isset($field->max) ? 'max="' . $field->max . '"' : '' ?>
                            >
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>


                <?php if (!empty($submit)): ?>
                    <button type="submit" class="form__submit"><?= $submit ?></button>
                <?php endif; ?>

            </form>
        </div>

    </div>
</div>