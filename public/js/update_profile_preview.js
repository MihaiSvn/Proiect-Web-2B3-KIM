//input tip file
const fileInput = document.getElementById('profile_upload');
//id din profile pic circle component
const imagePreview = document.getElementById('avatar_preview');

fileInput.addEventListener('change', function (event){
    //luam primul fisier
    const file = event.target.files[0];

    if(file){
        //cream un url temporar cu path ul fisierului
        const tempUrl = URL.createObjectURL(file);
        imagePreview.src = tempUrl;
    }
});