//rectangle change color while clicking
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

// checkbox
var enable_upload = document.getElementById("enable_upload");
enable_upload.addEventListener("change", function() {
  var outter_require_upload = document.getElementById('outter_require_upload');
  if (this.checked) {
    outter_require_upload.classList.remove('hidden');
    outter_require_upload.classList.add('block');
  } else {
    outter_require_upload.classList.remove('block');
    outter_require_upload.classList.add('hidden');
  }
})

var enable_comment = document.getElementById('enable_comment');
enable_comment.addEventListener('change', function() {
  var outter_require_comment = document.getElementById('outter_require_comment');
  if (this.checked) {
    outter_require_comment.classList.remove('hidden');
    outter_require_comment.classList.add('block');
  } else {
    outter_require_comment.classList.remove('block');
    outter_require_comment.classList.add('hidden');
  }
})





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



document.querySelector('form').onsubmit = function() {
  // Get the rich text content from Quill
  var content = document.querySelector('input[name=content]');
  content.value = JSON.stringify(quill.getContents());
};


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
//   var topic = <?php echo json_encode($_SESSION['topic']) ?>;
  console.log(selected_time_array)
  console.log(topic)



  $.ajax({
    url: "next.php",
    type: "post",
    data: {
      selected_time_array: selected_time_array
    },
    success: function(response) {
      console.log("Sent successfully");
      window.location.href = "next.php";
    }
  });
})