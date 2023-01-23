<?php include 'inc/header.php'; ?>

<!-- process bar -->
<div class="w-full bg-gray-200 rounded-full h-1.5 mb-4 dark:bg-gray-700">
    <div class="bg-gray h-1.5 rounded-full dark:bg-gray-500" style="width: 33%"></div>
</div>

<div class="flex flex-col justify-around space-y-24 mt-[100px] md:flex-row md:h-[300px] md:w-full">
    <!-- date range -->
    <div class="flex justify-center items-center space-x-5">
        <input type="date">
        <span class="font-bold">to</span>
        <input type="date">
    </div>

    <!-- duration -->
    <div class="flex flex-col items-center  space-y-3">
        <p class="text-[24px] font-bold text-orange">Timeslot Duration</p>
        <select class="bg-orange font-bold p-2" id="selector">
            <option value="in-person">1 Hr</option>
            <option value="virtual">30 mins</option>
            <option value="virtual">15 mins</option>
        </select>
    </div>

</div>

<!-- button -->
<div class="flex justify-center mt-[200px]">
    <button class="bg-orange rounded-2xl px-5 py-2">Next</button>
</div>


<?php include 'inc/footer.php'; ?>