<?php include 'inc/header.php'; ?>

<?php
$topic = $location = $method =  '';
$topicErr = $locationErr = '';

//form submit
if (isset($_POST['submit'])) {
    // validate topic
    if (empty($_POST['topic'])) {
        $topicErr = "Title is required!";
    } else {
        $topic = filter_input(INPUT_POST, 'topic', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }

    // validate location
    if (empty($_POST['location'])) {
        $locationErr = "Location is required!";
    } else {
        $location = filter_input(INPUT_POST, 'location', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }

    //assign value to method
    $method = filter_input(INPUT_POST, 'method', FILTER_SANITIZE_FULL_SPECIAL_CHARS);


    if (!empty($topic) && !empty($location) && !empty($method)) {
        // add to database
        $sql = "INSERT INTO info (topic, location, method) VALUES ('$topic', '$location', '$method')";
        if (mysqli_query($conn, $sql)) {
            // success
            header('Location: time.php');
        } else {
            // error
            echo 'Error: ' . mysqli_error($conn);
        }
    }
}
?>

<div class="relative">
    <!-- bg -->
    <div class=" min-h-screen bg-cover bg-no-repeat bg-fixed bg-center opacity-60" style="background-image: url('https://visitosu.oregonstate.edu/sites/visitosu.oregonstate.edu/files/0319_osufoundation_1153_0.jpg')"></div>

    <!-- info -->
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 space-y-10 w-[65%]">
        <!-- topic -->
        <div class="flex flex-col items-center space-y-3">
            <P class="text-[24px] font-bold text-orange">Topic</P>
            <div class="flex justify-center w-full">
                <input type="text" id="topic" name="topic" placeholder="Enter the topic..." class="peer p-2 grow">
                <select class="form-select bg-orange font-bold p-2" id="method" name="method">
                    <option value="in_person">In Person</option>
                    <option value="virtual">Virtual</option>
                </select>
            </div>
            <?php if (!empty($topicErr)) : ?>
                <p class="text-red-600 text-sm">Title is required!</p>
            <?php endif; ?>
        </div>
        <!-- location -->
        <div class="flex flex-col items-center space-y-3">
            <P class="text-[24px] font-bold text-orange">Location</P>
            <input class="p-2 w-full" type="text" id="location" name="location" placeholder="Enter your location or meeting link">
            <?php if (!empty($locationErr)) : ?>
                <p class="text-red-600 text-sm">Location is required!</p>
            <?php endif; ?>
        </div>

        <!-- button -->
        <div class="flex justify-center">
            <input class="p-3 bg-orange rounded-2xl px-10 cursor-pointer" type="submit" name="submit" value="Create"></input>
        </div>
    </form>
</div>






<?php include 'inc/footer.php'; ?>