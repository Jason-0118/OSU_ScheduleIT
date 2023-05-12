<?php
include 'config/database.php';
date_default_timezone_set('America/Los_Angeles');

// UNTESTED FUNCTIONALITY
// Define a function to handle a 404 error
// function page_not_found() {
//     http_response_code(404);
//     echo "404 - Page not found";
// }

// // Define an array of routes that maps URLs to function names and filenames
// // $routes = [
// //     '/index.php' => ['function_name' => 'about_page', 'file_name' => 'views/about.php']
// //     '/about' => ['function_name' => 'about_page', 'file_name' => 'views/about.php']
// //     '/profile' => ['function_name' => 'profile_page', 'file_name' => 'views/profile.php']
// // ];

// // Get the requested URL from the browser
// $request_uri = $_SERVER['REQUEST_URI'];

// // Check if the requested URL is in the array of routes
// if (array_key_exists($request_uri, $routes)) {
//     // If it is, get the function name and file name
//     $function_name = $routes[$request_uri]['function_name'];
//     $file_name = $routes[$request_uri]['file_name'];

//     // Check if the file exists
//     if (file_exists($file_name)) {
//         // If it does, include the file and call the function
//         include $file_name;
//         if (function_exists($function_name)) {
//             $function_name();
//         } else {
//             page_not_found();
//         }
//     } else {
//         // If the file does not exist, call the 404 error function
//         page_not_found();
//     }
// } else {
//     // If the URL is not in the array of routes, call the 404 error function
//     page_not_found();
// }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- for ajax in timeslot.php -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- rich text editor -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        gray: '#969696', //mobile size hover bg color
                        darkGray: '#262626', //mobile size bg color
                        orange: '#D73F09',
                        selected_orange: '#D73F09',
                    }
                }
            }
        }
    </script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <title>Document</title>
</head>

<body>
    <!-- header -->
    <nav>
        <div class="md:flex md:justify-around w-full bg-orange shadow-xl">
            <!-- logo -->
            <div class="flex items-center justify-around cursor-pointer">
                <a href="/OSU_ScheduleIT"><img class="h-20 md:h-[80%]" src="/OSU_ScheduleIT/img/logo.svg" alt=""></a>
                <span class="text-4xl sm:hidden cursor-pointer">
                    <ion-icon name="menu-outline" onclick="Menu(this)"></ion-icon>
                </span>
            </div>

            <!-- link -->
            <ul class="absolute bg-darkGray w-full h-full left-0 z-[-1] opacity-0 md:bg-orange md:h-auto md:w-auto md:flex md:justify-center md:items-center  md:z-auto md:static md:w-auto md:opacity-100 duration-500">
                <!-- <li class="my-6 p-3 md:my-0 w-full hover:bg-gray md:w-auto md:hover:bg-orange  "><a class="ml-[100px] font-semibold text-2xl text-white md:text-darkGray md:ml-0 md:hover:text-white duration-100 " href="#">Creating</a></li> -->
                <li class="my-6 p-3 md:my-0 w-full hover:scale-105 duration-100 md:w-auto md:hover:bg-orange  "><a class="ml-[100px] font-semibold text-2xl text-white bg-orange p-3 rounded-lg md:text-darkGray md:ml-0 md:hover:text-white duration-100 " href="/OSU_ScheduleIT/index.php">Creating</a></li>
                <li class="my-6 p-3 md:my-0 w-full hover:bg-gray md:w-auto md:hover:bg-orange  "><a class="ml-[100px] font-semibold text-2xl text-white md:text-darkGray md:hover:text-white duration-100 " href="/OSU_ScheduleIT/views/meeting/meeting.php">Meeting</a></li>
                <li class="my-6 p-3 md:my-0 w-full hover:bg-gray md:w-auto md:hover:bg-orange  "><a class="ml-[100px] font-semibold text-2xl text-white md:text-darkGray md:hover:text-white duration-100 " href="/OSU_ScheduleIT/views/calendar/calendarPage.php">Calendar</a></li>
                <hr>
                <li class="my-6 p-3 md:my-0 w-full hover:bg-gray md:w-auto md:hover:bg-orange md:hidden"><a class="ml-[100px] font-semibold text-2xl text-white" href="#">Message</a></li>
                <li class="my-6 p-3 md:my-0 w-full hover:bg-gray md:w-auto md:hover:bg-orange md:hidden"><a class="ml-[100px] font-semibold text-2xl text-white" href="/OSU_ScheduleIT/views/calendar/calendarPage.php">Calendar</a></li>
                <hr>
                <li class="my-6 p-3 md:my-0 w-full hover:bg-gray md:w-auto md:hover:bg-orange md:hidden"><a class="ml-[100px] font-semibold text-2xl text-white" href="/OSU_ScheduleIT/views/profile.php">Profile</a></li>
                <li class="my-6 p-3 md:my-0 w-full hover:bg-gray md:w-auto md:hover:bg-orange md:hidden"><a class="ml-[100px] font-semibold text-2xl text-white" href="#">Log Out</a></li>

            </ul>

            <!-- profile -->
            <div class="hidden md:flex md:items-center z-[1]">
                <a href="/OSU_ScheduleIt/views/notifications.php"><img class="mr-[50px] md:h-[30%] cursor-pointer" src="/OSU_ScheduleIT/img/message.svg" alt=""></a>
                <button onclick="Dropdown()" class="relative flex justify-center items-center">
                    <img src="/OSU_ScheduleIT/img/profile.svg" alt="">
                    <div id="dropdown" class="absolute hidden top-full min-w-full w-max bg-white shadow-md mt-1 rounded">
                        <ul>
                            <li class="my-3 p-2 hover:bg-gray"><a class="text-md" href="/OSU_ScheduleIT/views/profile.php">Profile</a></li>
                            <li class="my-3 p-2 hover:bg-gray"><a class="text-md" href="">Log Out</a></li>
                        </ul>
                    </div>
                </button>
            </div>

        </div>
    </nav>

    <main>