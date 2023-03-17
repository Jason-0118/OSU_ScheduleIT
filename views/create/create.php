<?php
session_start();

ob_start();
$header_path = $footer_path = $_SERVER['DOCUMENT_ROOT'];
$header_path .= "/OSU_ScheduleIT/header.php";
$footer_path .= "/OSU_ScheduleIT/footer.php";
include_once($header_path);
?>

<?php
// get time slot array via ajax
if (isset($_POST['selected_time_array'])) {
  $_SESSION['selected_time_array'] = $_POST['selected_time_array'];
}

// get text content via ajax
if (isset($_POST['description'])) {
  $_SESSION['description'] = $_POST['description'];
}


// get checkbox value via ajax
if (isset($_POST['allowComment'])) {
  $_SESSION['allowComment'] = $_POST['allowComment'];
}

// get checkbox value via ajax
if (isset($_POST['allowUpload'])) {
  $_SESSION['allowUpload'] = $_POST['allowUpload'];
}


if (isset($_POST['submit'])) {
  //--------------------------- users table --------
  $onid = "zhangxin2";
  $lastName = "Zhang";
  $firstName = "Xin";
  $hashUsers = substr(md5($onid), 0, 8);

  //---------------------------  event data--------
  $topic = $_SESSION['topic'];
  $location = $_SESSION['location'];
  $method = $_SESSION['method'];
  //0 = not able, 1 = able, 2 = require

  $allowComment = empty($_SESSION['allowComment']) ? 0 : $_SESSION['allowComment'];
  $allowUpload = empty($_SESSION['allowUpload']) ? 0 : $_SESSION['allowUpload'];
  $description = empty($_SESSION['description']) ? "N/A" : $_SESSION['description'];

  //---------------------------  options data--------
  $duration = $_SESSION['time_duration'];
  $selected_time_array = $_SESSION['selected_time_array'];

  //for graph
  $start_date = $_SESSION['start_date'];
  $end_date = $_SESSION['end_date'];


  //check if users already exist
  $sql_check_user = "SELECT onid FROM users WHERE onid = '$onid' ";
  $result = mysqli_query($conn, $sql_check_user);
  $row = mysqli_fetch_assoc($result);
  if(!empty($row['onid'])) $user_onid = $row['onid'];

  //insert to users table if user not exist
  if (!empty($hashUsers) && !empty($onid) && !empty($lastName) && !empty($firstName) && empty($user_onid)) {
    $sql_users = "INSERT INTO users(hashUsers, onid, lastName, firstName) VALUES ('$hashUsers', '$onid', '$lastName', '$firstName')";
    mysqli_query($conn, $sql_users);
  }

  echo $hashUsers . "-" . $topic . "-" . $location . "-" . $method . "-" . $allowComment . "-" . $allowUpload . "-" . $description;
  //insert event table
  if (!empty($hashUsers) && !empty($topic) && !empty($location) && !empty($method)) {
    $sql_event = "INSERT INTO event(hashUsers, topic, location, method, allowComment, allowUpload, description) VALUES ('$hashUsers', '$topic', '$location', '$method', '$allowComment', '$allowUpload', '$description')";
    if (mysqli_query($conn, $sql_event)) {
      $sql_event_id = "SELECT idEvent FROM event where hashUsers = '$hashUsers' AND topic = '$topic' AND location = '$location' AND description = '$description' ";
      $result = mysqli_query($conn, $sql_event_id);
      $row = mysqli_fetch_assoc($result);
      $idEvent = intval($row['idEvent']);
      //hash id into 8 chars string
      $hashEvent = substr(md5($idEvent), 0, 8);
      $sql_update_event = "UPDATE event SET hashEvent = '$hashEvent' WHERE idEvent = '$idEvent' ";
      mysqli_query($conn, $sql_update_event);
      //store id for next page
      $_SESSION['idEvent'] = $idEvent;

      // insert options table
      foreach ($selected_time_array as $date) {
        $dateStamp = date('Y-m-d H:i:s', $date);
        $sql_options = "INSERT INTO options(idEvent, duration, date) VALUES ('$idEvent', '$duration', '$dateStamp') ";
        mysqli_query($conn, $sql_options);
      }
      header('Location: /OSU_ScheduleIT/views/create/summary.php?id=' . $hashEvent);
    }
  }
}
?>




<form method="POST" action="create.php" enctype="multipart/form-data">
  <!-- display html -->
  <?php
  $getDay = array('Sun', 'Mon', 'Tue', 'Wed', 'Thur', 'Fri', 'Sat');
  //get previous variable
  $start_date =  $_SESSION['start_date'];
  $end_date =  $_SESSION['end_date'];
  $time_duration = $_SESSION['time_duration'];
  //
  $day_head = new DateTime($start_date);
  $day_tail = new DateTime($end_date);
  //include selected date
  $day_tail->add(new DateInterval('P1D'));
  $interval_day = new DateInterval('P1D'); // 1 day interval
  $day_range = new DatePeriod($day_head, $interval_day, $day_tail);


  echo "<div class='overflow-x-scroll p-2 flex flex-col md:items-center'>";
  //print date head
  echo "<div class='flex w-max'> 
            <div class='h-[20px] w-[60px]'>  </div>";
  foreach ($day_range as $date) {
    echo "<div class='flex flex-col items-center w-[50px] h-[60px]'>" .
      "<div class='text-sm'>" . $date->format('M') . " " .  ltrim(date("d", strtotime($date->format('Y-m-d'))), '0') . "</div>" .
      "<div class='font-bold text-md'>" . $getDay[$date->format('w')] . "</div>" .
      "</div>";
  }
  echo "</div>";

  $hour_head = new DateTime('8:00 AM');
  $hour_tail = new DateTime('5:00 PM');
  $interval_hour = new DateInterval($time_duration); // time duration
  $hour_range = new DatePeriod($hour_head, $interval_hour, $hour_tail);

  echo "<div class='w-max '>";
  foreach ($hour_range as $row => $time) {
    echo "<div class='flex'>";
    //print time col
    if ($time_duration == "PT15M" && $row % 4 == 0) {
      echo "<div class='text-sm h-[20px] w-[80px] sticky left-[-8px] bg-white/50 '>" . $time->format('g:i A') . "</div>";
    } else if ($time_duration == "PT30M" && $row % 2 == 0) {
      echo "<div class='text-sm h-[20px] w-[80px] sticky left-[-8px] bg-white/50 '>" . $time->format('g:i A') . "</div>";
    } else if ($time_duration == "PT60M") {
      echo "<div class='text-sm h-[20px] w-[80px] sticky left-[-8px] bg-white/50 '>" . $time->format('g:i A') . "</div>";
    } else {
      echo "<div class='text-sm h-[20px] w-[80px] sticky left-[-8px] bg-white/50 '> </div>";
    }

    //print time block
    echo "<div class='flex '>";
    foreach ($day_range as $col => $date) {
      $day = $date->format('Y-m-d');
      $hour = $time->format('g:i A');
      //UTC to PST 8 hours difference
      echo "<div id='YouTime" . strtotime("$day $hour") . "'  class='rectangle w-[50px] h-[20px] bg-gray border-[.5px] ' data-row=" . $row . " data-col=" . $col . " date-time=" . strtotime("$day $hour") . "> </div>";
    }
    echo "</div>";
    echo "</div>";
  }
  echo "</div>";

  echo "</div>";
  ?>

  <!-- description -->
  <div class="flex flex-col items-center space-y-3 mt-3 p-3">
    <h2 class="text-2xl font-bold text-orange">Description</h2>
    <div id="editor" class="h-[150px] w-full"> </div>
  </div>

  <!-- file upload -->
  <div class="p-3">
    <div class="p-3 border-[0.5px] border-gray space-y-3">
      <h2>File Download For Attendees</h2>
      <p class="text-gray text-sm">You may only upload a single file. Uploading a second file will overwrite the first. If you need to upload multiple files combine them into a single zip file. Allowed file types: txt, zip, pdf, docx, xlsx, pptx</p>
      <input type="file" name="file">
    </div>
  </div>

  <!-- checkbox -->
  <div class="flex flex-col p-3">
    <div class="md:grid md:grid-cols-4">
      <div><input type="checkbox" id="enable_upload" name="enable_upload"> Enable Attendees to Upload Files</input></div>
      <div id="outter_require_upload" class="hidden text-orange ml-5"><input type="checkbox" id="require_upload" name="require_upload"> Require Attendees to Upload Files</input></div>
    </div>
    <div class="md:grid md:grid-cols-4">
      <div><input type="checkbox" id="enable_comment" name="enable_comment"> Enable Attendees to Comment</input></div>
      <div id="outter_require_comment" class="hidden text-orange ml-5"><input type="checkbox" id="require_comment" name="require_comment"> Require Attendees to Comment</input></div>
    </div>
  </div>

  <!-- button -->
  <div class="flex justify-center mt-[50px] mb-[50px]">
    <input class="create_button p-3 bg-orange rounded-2xl px-10 cursor-pointer" type="submit" name="submit" value="Submit"></input>
  </div>
</form>





<?php include_once($footer_path); ?>
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


  //create_button
  const create_button = document.querySelector('.create_button');
  create_button.addEventListener('click', function() {
    //find all the selected time and insert into array
    selected_time_array = []
    const selected_time = document.querySelectorAll('.bg-selected_orange')
    selected_time.forEach(function(timeslot) {
      value = timeslot.getAttribute('date-time')
      selected_time_array.push(value)
    })
    //
    console.log(selected_time_array)
    var description = quill.getText();
    console.log(quill.getText())

    var allowComment = 0;
    var allowUpload = 0;

    var enable_upload_id = document.getElementById('enable_upload');
    if (enable_upload_id.checked) {
      allowUpload = 1;
      var require_upload_id = document.getElementById('require_upload');
      if (require_upload_id.checked) {
        allowUpload = 2;
      }
    }
    var enable_comment_id = document.getElementById('enable_comment');
    if (enable_comment_id.checked) {
      allowComment = 1;
      var require_comment_id = document.getElementById('require_comment');
      if (require_comment_id.checked) {
        allowComment = 2;
      }
    }

    $.ajax({
      url: window.location.href,
      type: "POST",
      data: {
        selected_time_array: selected_time_array,
        description: description,
        allowComment: allowComment,
        allowUpload: allowUpload,

      },
      success: function(response) {
        console.log("Sent successfully");
      }
    });
  })
</script>