<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resident Portal — MEDLINK</title>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <style type="text/tailwindcss">
        @theme {
            --font-sans: 'Inter', ui-sans-serif, system-ui, sans-serif;
        }
        body { font-family: 'Inter', sans-serif; }
    </style>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/js/medlink.js']); ?>
</head>
<body class="bg-gray-50 text-gray-900 antialiased min-h-screen">
    <div id="app">
        <resident-portal 
            :user="<?php echo e(auth()->user()->toJson()); ?>"
            :dashboard-data="<?php echo e(json_encode(compact('medicines', 'recentRequests', 'activeRequestsCount', 'completedCount', 'upcomingSchedulesCount', 'expiredMedicinesCount', 'announcements'))); ?>"
            :my-medicine-requests="<?php echo e(json_encode($myMedicineRequests)); ?>"
            :my-medicine-returns="<?php echo e(json_encode($myMedicineReturns ?? [])); ?>"
            medicine-request-store-url="<?php echo e($medicineRequestStoreUrl); ?>"
            home-service-store-url="<?php echo e($homeServiceStoreUrl); ?>"
            :booked-home-services="<?php echo e(json_encode($bookedHomeServices)); ?>"
            :my-schedules="<?php echo e(json_encode($mySchedules)); ?>"
            :booked-schedules="<?php echo e(json_encode($bookedSchedules)); ?>"
            schedule-store-url="<?php echo e($scheduleStoreUrl); ?>"
            :notifications="<?php echo e(json_encode($notifications)); ?>"
            :notification-summary="<?php echo e(json_encode($notificationSummary ?? [])); ?>"
            mark-read-url="<?php echo e($markReadUrl); ?>"
            profile-update-url="<?php echo e($profileUpdateUrl); ?>"
        ></resident-portal>
    </div>
</body>
</html>
<?php /**PATH C:\laragon\www\techyknowloadeia1\resources\views/dashboards/resident.blade.php ENDPATH**/ ?>