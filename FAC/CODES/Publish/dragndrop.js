
document.addEventListener('DOMContentLoaded', function () {
  let dropArea = document.getElementById('drop-area');

  dropArea.addEventListener('dragover', function (e) {
      e.preventDefault();
      dropArea.classList.add('drag-over');
  });

  dropArea.addEventListener('dragleave', function () {
      dropArea.classList.remove('drag-over');
  });

  dropArea.addEventListener('drop', function (e) {
      e.preventDefault();
      dropArea.classList.remove('drag-over');

      let input = document.getElementById('file');
      let files = e.dataTransfer.files;

      if (files.length > 0) {
          input.files = files;
          updateLabel();
      }
  });
});
