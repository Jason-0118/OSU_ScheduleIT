</main>





<!-- index.php -->
<script>
  function Menu(self) {
    const list = document.querySelector("ul");
    self.name === 'menu-outline' ? (self.name = "close-outline", list.classList.add("top-[80px]"), list.classList.add("opacity-100"), list.classList.add("z-[100]")) :
      (self.name = "menu-outline", list.classList.remove("top-[80px]"), list.classList.remove("opacity-100"), list.classList.remove("z-[100]"))
  }


  function Dropdown() {
    const list = document.getElementById("dropdown");
    list.classList.toggle("hidden");
  }
</script>

<!-- create.php -->
<script>
  //timeslot
  const rectangles = document.querySelectorAll('.rectangle');
  rectangles.forEach(function(rectangle) {
    rectangle.addEventListener('click', function() {
      if (rectangle.classList.contains('bg-gray')) {
        rectangle.classList.remove('bg-gray');
        rectangle.classList.add('bg-selected_orange');
      } else {
        rectangle.classList.remove('bg-selected_orange')
        rectangle.classList.add('bg-gray')
      }
    })
  });

  // rich editor
  var toolbarOptions = [
    ['bold', 'italic', 'underline', 'strike'], // toggled buttons
    ['blockquote', 'code-block'],

    [{
      'header': 1
    }, {
      'header': 2
    }], // custom button values
    [{
      'list': 'ordered'
    }, {
      'list': 'bullet'
    }],
    [{
      'script': 'sub'
    }, {
      'script': 'super'
    }], // superscript/subscript
    [{
      'indent': '-1'
    }, {
      'indent': '+1'
    }], // outdent/indent
    [{
      'direction': 'rtl'
    }], // text direction

    [{
      'size': ['small', false, 'large', 'huge']
    }], // custom dropdown
    [{
      'header': [1, 2, 3, 4, 5, 6, false]
    }],

    [{
      'color': []
    }, {
      'background': []
    }], // dropdown with defaults from theme
    [{
      'font': []
    }],
    [{
      'align': []
    }],

    ['clean'] // remove formatting button
  ];

  var quill = new Quill('#editor', {
    modules: {
      toolbar: toolbarOptions
    },
    theme: 'snow'
  });


  // checkbox
  var enable_upload = document.getElementById("enable_upload");
  enable_upload.addEventListener("click", function() {
    var require_upload = document.getElementById('outter_require_upload');
    if (this.checked) {
      require_upload.classList.remove('hidden');
      require_upload.classList.add('block');
    } else {
      require_upload.classList.remove('block');
      require_upload.classList.add('hidden');
    }
  })

  var enable_comment = document.getElementById('enable_comment');
  enable_comment.addEventListener('click', function() {
    var outter_require_comment = document.getElementById('outter_require_comment');
    if (this.checked) {
      outter_require_comment.classList.remove('hidden');
      outter_require_comment.classList.add('block');
    } else {
      outter_require_comment.classList.remove('block');
      outter_require_comment.classList.add('hidden');
    }
  })


  //btn
  const btn = document.querySelector('.button');
  btn.addEventListener('click', function() {
    //find all the selected time and insert into array
    selected_time_array = []
    const selected_time = document.querySelectorAll('.bg-selected_orange')
    selected_time.forEach(function(timeslot) {
      value = timeslot.getAttribute('date-time')
      selected_time_array.push(value)
    })
    //
    console.log(selected_time_array)
    var textContent = quill.getText();
    console.log(quill.getText())

    //1: enable_upload, 2:enable_comment, 3:require_upload, 4:require_comment
    var pem_array = [0, 0, 0, 0];

    var enable_upload_id = document.getElementById('enable_upload');
    if (enable_upload_id.checked) {
      pem_array[0] = 1;
      var require_upload_id = document.getElementById('require_upload');
      if (require_upload_id.checked) {
        pem_array[2] = 1;
      }
    }
    var enable_comment_id = document.getElementById('enable_comment');
    if (enable_comment_id.checked) {
      pem_array[1] = 1;
      var require_comment_id = document.getElementById('require_comment');
      if (require_comment_id.checked) {
        pem_array[3] = 1;
      }
    }



    $.ajax({
      url: "create.php",
      type: "POST",
      data: {
        selected_time_array: selected_time_array,
        textContent: textContent,
        pem_array: pem_array,
      },
      success: function(response) {
        console.log("Sent successfully");
      }
    });
  })
</script>

</body>

</html>