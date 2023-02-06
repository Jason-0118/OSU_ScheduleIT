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

<!-- time.php -->
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

  //btn
  //find all the selected time and insert into array
  const btn = document.querySelector('.button');
  btn.addEventListener('click', function() {

    selected_time_array = []
    const selected_time = document.querySelectorAll('.bg-selected_orange')
    selected_time.forEach(function(timeslot) {
      value = timeslot.getAttribute('date-time')
      selected_time_array.push(value)
    })
    console.log(selected_time_array)

    $.ajax({
      url:"next.php",
      type: "post",
      data: {selected_time_array: selected_time_array},
      success:function(response){
        console.log("Sent successfully");
        window.location.href = "next.php";
      }
    });
  })
</script>
</body>

</html>