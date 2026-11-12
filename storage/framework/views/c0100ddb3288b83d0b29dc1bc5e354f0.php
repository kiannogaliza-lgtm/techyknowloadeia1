<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BHW Portal — MEDLINK</title>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <style type="text/tailwindcss">
        body { font-family: 'Inter', sans-serif; }
    </style>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/js/medlink.js']); ?>
</head>
<body class="bg-gray-50 text-gray-900 antialiased min-h-screen">
    <script>
        window.__BHW_DATA__ = {
            user: <?php echo json_encode(auth()->user()); ?>,
            dashboardData: <?php echo json_encode($dashboardData); ?>,
            myAssignedServices: <?php echo json_encode($myAssignedServices); ?>,
            recentActivities: <?php echo json_encode($recentActivities); ?>,
            walkinStoreUrl: <?php echo json_encode($walkinStoreUrl); ?>,
            recentWalkins: <?php echo json_encode($recentWalkins); ?>,
            medicines: <?php echo json_encode($medicines); ?>,
            pendingHomeServices: <?php echo json_encode($pendingHomeServices); ?>,
            acceptedHomeServices: <?php echo json_encode($acceptedHomeServices); ?>,
            assignedHomeServices: <?php echo json_encode($assignedHomeServices); ?>,
            completedHomeServices: <?php echo json_encode($completedHomeServices); ?>,
            reportData: <?php echo json_encode($reportData); ?>,
            notifications: <?php echo json_encode($notifications); ?>,
            notificationSummary: <?php echo json_encode($notificationSummary ?? []); ?>,
            markReadUrl: <?php echo json_encode(route('bhw.notifications.read')); ?>,
            bhwConfirmScheduleUrl: <?php echo json_encode(url('/bhw/scheduling')); ?>,
            announcements: <?php echo json_encode($announcements); ?>,
            announcementsStoreUrl: <?php echo json_encode(route('announcements.store')); ?>,
            medicineRequests: <?php echo json_encode($medicineDeliveryRequests ?? []); ?>,
            medicineReturns: <?php echo json_encode($medicineReturns ?? []); ?>,
        };
    </script>
    <div id="app"></div>
</body>
</html>
<?php /**PATH C:\laragon\www\techyknowloadeia1\resources\views/dashboards/bhw.blade.php ENDPATH**/ ?>