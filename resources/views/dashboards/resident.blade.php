<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resident Portal — MEDLINK</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <style type="text/tailwindcss">
        @theme {
            --font-sans: 'Inter', ui-sans-serif, system-ui, sans-serif;
        }
        body { font-family: 'Inter', sans-serif; }
    </style>
    @vite(['resources/js/medlink.js'])
</head>
<body class="bg-gray-50 text-gray-900 antialiased min-h-screen">
    <div id="app">
        <resident-portal 
            :user="{{ auth()->user()->toJson() }}"
            :dashboard-data="{{ json_encode(compact('medicines', 'recentRequests', 'activeRequestsCount', 'completedCount', 'upcomingSchedulesCount', 'expiredMedicinesCount', 'announcements')) }}"
            :my-medicine-requests="{{ json_encode($myMedicineRequests) }}"
            :my-medicine-returns="{{ json_encode($myMedicineReturns ?? []) }}"
            medicine-request-store-url="{{ $medicineRequestStoreUrl }}"
            home-service-store-url="{{ $homeServiceStoreUrl }}"
            :booked-home-services="{{ json_encode($bookedHomeServices) }}"
            :my-schedules="{{ json_encode($mySchedules) }}"
            :booked-schedules="{{ json_encode($bookedSchedules) }}"
            schedule-store-url="{{ $scheduleStoreUrl }}"
            :notifications="{{ json_encode($notifications) }}"
            :notification-summary="{{ json_encode($notificationSummary ?? []) }}"
            mark-read-url="{{ $markReadUrl }}"
            profile-update-url="{{ $profileUpdateUrl }}"
        ></resident-portal>
    </div>
</body>
</html>
