function previewPhoto(event, originalImage) {
    const displayPhotoPreview = document.getElementById("display-photo-preview");

    try {
        displayPhotoPreview.src = URL.createObjectURL(event.target.files[0]);
        displayPhotoPreview.onload = () => {
            URL.revokeObjectURL(displayPhotoPreview.src)
        }
    } catch (e) {
        displayPhotoPreview.src = originalImage;
    }
}