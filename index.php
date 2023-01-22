<?php include 'inc/header.php'; ?>

<!-- content -->


<div class="relative">
    <!-- bg -->
    <div class=" min-h-screen bg-cover bg-no-repeat bg-fixed bg-center opacity-50" style="background-image: url('https://visitosu.oregonstate.edu/sites/visitosu.oregonstate.edu/files/0319_osufoundation_1153_0.jpg')"></div>

    <!-- info -->
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 space-y-10 w-[65%]">
        <!-- topic -->
        <div class="flex flex-col items-center space-y-3">
            <P class="text-[24px] font-bold text-orange">Topic</P>
            <div class="flex justify-center w-full">
                <input type="text" class="p-2 grow">
                <select class="form-select bg-orange font-bold p-2" id="selector">
                    <option value="in-person">In Person</option>
                    <option value="virtual">Virtual</option>
                </select>
            </div>
        </div>
        <!-- location -->
        <div class="flex flex-col items-center space-y-3">
            <P class="text-[24px] font-bold text-orange">Location</P>
            <input class="p-2 w-full" type="text">
        </div>

        <!-- button -->
        <div class="flex justify-center">
            <button class="p-3 bg-orange rounded-2xl px-10">Create</button>
        </div>
    </div>
</div>






<?php include 'inc/footer.php'; ?>