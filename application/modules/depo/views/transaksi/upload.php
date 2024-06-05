<input type="file" id="file_view_foto" onchange="previewFile('view_foto','file_view_foto')"><br>
<img src="" id="view_foto" height="200" alt="Image preview..."><br>
<input type="file" id="file_view_foto2" onchange="previewFile('view_foto2','file_view_foto2')"><br>
<img src="" id="view_foto2" height="200" alt="Image preview...">
<script>
function previewFile(view_foto,file_view_foto) {
  // const preview = document.querySelector('img');
  // const file = document.querySelector('input[type=file]').files[0];
  const preview = document.querySelector('#'+view_foto);
  const file = document.querySelector('#'+file_view_foto).files[0];
  const reader = new FileReader();

  reader.addEventListener("load", function () {
    // convert image file to base64 string
    preview.src = reader.result;
  }, false);

  if (file) {
    reader.readAsDataURL(file);
  }
}
</script>
