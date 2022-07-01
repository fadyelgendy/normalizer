<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Normalizer</title>
    <link rel="stylesheet" href="views/css/style.css" type="text/css">
</head>

<body>
    <h1>Database Normalizer</h1>
    <?php
    if (isset($_SESSION['success']))
        echo "<div class='success'>" . $_SESSION['success'] . "</div>";
    ?>

    <main>
        <section>
            <h3>Insert New Offer</h3>
            <?php include __DIR__ . "/form.php"; ?>
        </section>

        <section>
            <h3>offers</h3>

            <div class="table">
                <table>
                    <thead>
                        <th>#</th>
                        <th>title</th>
                        <th>desccription</th>
                    </thead>

                    <tbody>
                        <?php foreach ($data as $item) : ?>
                            <tr>
                                <td> <?= $item['id']; ?></td>
                                <td> <?= $item['title']; ?></td>
                                <td> <?= $item['description']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="normalize-btn">
                <a href="../src/Process.php?target=normalize"> normalize </a>
                <a href="../src/Process.php?target=load-data"> load test data </a>
            </div>

        </section>
    </main>
</body>

</html>