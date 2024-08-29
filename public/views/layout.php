<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Vending Machine System'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Ensure the html and body take up full height */
        html, body {
            height: 100%;
            margin: 0;
        }
        /* Flexbox container to push footer to the bottom */
        .page-container {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .content-wrap {
            flex: 1;
        }
        .font-bold {
            font-weight: 700; /* Ensure text is bold */
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="page-container">
        <!-- Include Header Partial -->
        <?php include __DIR__ . '/partials/header.php'; ?>

        <div class="content-wrap">
            <!-- Main content -->
            <main>
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    <?php echo $content; ?>
                </div>
            </main>
        </div>

        <!-- Include Footer Partial -->
        <?php include __DIR__ . '/partials/footer.php'; ?>
    </div>
</body>

</html>
