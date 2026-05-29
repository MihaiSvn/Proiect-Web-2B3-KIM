<?php
/**
 * @var string $title -> title trebuie sa fie titlul form-ului din header, de exemplu 'Edit'
 * @var string $action -> action trebuie sa fie ce endpoint apeleaza din POST, exemplu '/kim/login'
 * @var string $submit -> ce text este pe butonul de submit
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
 *      $formBody = [
 *          new FormField('Email', 'email', 'email', true, 'john.doe@kim.com', 'john.doe@gmail.com'),
 *          new FormField('First name', 'first_name', 'text', true, 'John')
 *      ];
 *      $popupId = 'popupOverlay_' . $id -> unde id este o variabila luata din baza de date, id-ul utilizatorului sau ce ar mai ajuta
 * ?>
 *
 * SI APOI APELEZ COMPONENTA CU
 *
 * <?php include 'components/popup.php'; ?>
 */
?>

<div class="popup__overlay popup__hidden" id="<?= $popupId?>">
    <div class="popup_window">
        <div class="popup__header">
            <p class="popup__title"><?= $title ?></p>
            <button class="popup__closebutton">
                <i class="fa-solid fa-x"></i>
            </button>
        </div>
        <form action="<?= $action ?>" method="POST" class="form__body" >

            <?php foreach ($formBody as $field): ?>
                <div class="form__group">
                    <label class="form__label" for="<?= $field->id ?>"><?= $field->label ?></label>
                    <input type="<?= $field->type ?>"
                           name="<?= $field->id ?>"
                           id="<?= $field->id ?>"
                           value="<?= $field->value ?>"
                           placeholder="<?= $field->placeholder ?>"
                           <?= $field->required ? 'required' : '' ?>

                            <?= isset($field->min) ? 'min="' . $field->min . '"' : '' ?>
                            <?= isset($field->max) ? 'max="' . $field->max . '"' : '' ?>

                    >
                </div>
            <?php endforeach; ?>
            <button type="submit"><?= $submit ?></button>
        </form>

    </div>
</div>