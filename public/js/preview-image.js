const placeholder =
    "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTw_HeSzHfBorKS4muw4IIeVvvRgnhyO8Gn8w&usqp=CAU";
const imageInput = document.getElementById('image');
const imagePreview = document.getElementById('preview');

imageInput.addEventListener('change', (e) => {
    const preview = imageInput.value ?? placeholder;
    imagePreview.setAttribute('src', preview);
});