function updateLabel() {
  let input = document.getElementById('file');
  let label = document.querySelector('.Upload-Here');

  if (input.files.length > 0) {
      label.textContent = input.files[0].name;
  } else {
      label.textContent = 'Upload file here';
  }
}
