<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <title>Document</title>
</head>

<body>
    <!-- header -->
    <nav>
        <div class="md:flex md:justify-around w-full bg-[#D73F09] shadow-xl">
            <!-- logo -->
            <div class="flex items-center justify-around cursor-pointer">
                <img class="h-20 md:h-[80%]" src="img/logo.svg" alt="">
                <span class="text-4xl md:hidden cursor-pointer">
                    <ion-icon name="menu-outline" onclick="Menu(this)"></ion-icon>
                </span>
            </div>

            <!-- link -->
            <ul class="absolute bg-[#262626] w-full h-full left-0 z-[-1] opacity-0 md:bg-[#D73F09] md:h-auto md:w-auto md:flex md:justify-center md:items-center  md:z-auto md:static md:w-auto md:opacity-100 duration-500">
                <!-- <li class="my-6 p-3 md:my-0 w-full hover:bg-[#969696] md:w-auto md:hover:bg-[#D73F09]  "><a class="ml-[100px] font-semibold text-2xl text-white md:text-[#262626] md:ml-0 md:hover:text-white duration-100 " href="#">Creating</a></li> -->
                <li class="my-6 p-3 md:my-0 w-full hover:scale-105 duration-100 md:w-auto md:hover:bg-[#D73F09]  "><a class="ml-[100px] font-semibold text-2xl text-white bg-[#D73F09] p-3 rounded-lg md:text-[#262626] md:ml-0 md:hover:text-white duration-100 " href="#">Creating</a></li>
                <li class="my-6 p-3 md:my-0 w-full hover:bg-[#969696] md:w-auto md:hover:bg-[#D73F09]  "><a class="ml-[100px] font-semibold text-2xl text-white md:text-[#262626] md:hover:text-white duration-100 " href="#">Meeting</a></li>
                <li class="my-6 p-3 md:my-0 w-full hover:bg-[#969696] md:w-auto md:hover:bg-[#D73F09]  "><a class="ml-[100px] font-semibold text-2xl text-white md:text-[#262626] md:hover:text-white duration-100 " href="#">Calendar</a></li>
                <hr>
                <li class="my-6 p-3 md:my-0 w-full hover:bg-[#969696] md:w-auto md:hover:bg-[#D73F09] md:hidden"><a class="ml-[100px] font-semibold text-2xl text-white" href="#">Message</a></li>
                <li class="my-6 p-3 md:my-0 w-full hover:bg-[#969696] md:w-auto md:hover:bg-[#D73F09] md:hidden"><a class="ml-[100px] font-semibold text-2xl text-white" href="#">Calendar</a></li>
                <hr>
                <li class="my-6 p-3 md:my-0 w-full hover:bg-[#969696] md:w-auto md:hover:bg-[#D73F09] md:hidden"><a class="ml-[100px] font-semibold text-2xl text-white" href="#">Profile</a></li>
                <li class="my-6 p-3 md:my-0 w-full hover:bg-[#969696] md:w-auto md:hover:bg-[#D73F09] md:hidden"><a class="ml-[100px] font-semibold text-2xl text-white" href="#">Log Out</a></li>

            </ul>

            <!-- profile -->
            <div class="hidden md:flex md:items-center">
                <img class="mr-[50px] md:h-[30%]" src="./img/message.svg" alt="">
                <button onclick="Dropdown()" class="relative flex justify-center items-center">
                    <img src="./img/profile.svg" alt="">
                    <div id="dropdown" class="absolute hidden top-full min-w-full w-max bg-white shadow-md mt-1 rounded">
                        <ul>
                            <li class="my-3 p-2 hover:bg-gray-100"><a class="text-md" href="">Profile</a></li>
                            <li class="my-3 p-2 hover:bg-gray-100"><a class="text-md" href="">Log Out</a></li>
                        </ul>
                    </div>
                </button>
            </div>

        </div>
    </nav>

    <main>