<html>
<head>
  <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
  <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
</head>
<body>
<input type="checkbox" id="checkbox1"> Show Checkbox 2
<br>
<br>
<div id="checkbox2" style="display: none;">
  <input type="checkbox" id="innerCheckbox"> Checkbox 2
</div>
<script>
  document.getElementById("checkbox1").addEventListener("change", function() {
  var checkbox2 = document.getElementById("checkbox2");
  if (this.checked) {
    checkbox2.style.display = "block";
  } else {
    checkbox2.style.display = "none";
  }
});

</script>
</body>
</html>
