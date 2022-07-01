<form action="../src/Process.php" method="POST">
    <div>
        <label for="title">title</label>
        <input type="text" name="title" id="title" <?php if (isset($_SESSION['title'])) echo "class='has-error'"; ?> >

        <?php if (isset($_SESSION['title'])) echo "<div class='alert'>" . $_SESSION['title'] . "</div>"; ?>
    </div>

    <div>
        <label for="description">Description</label>
        <textarea name="description" id="description" cols="30" rows="10" <?php if (isset($_SESSION['description'])) echo "class='has-error'"; ?>></textarea>

        <?php if (isset($_SESSION['description'])) echo "<div class='alert'>" . $_SESSION['description'] . "</div>"; ?>
    </div>

    <div class="check">
        <input type="checkbox" name="normalize" id="normalize" value="1">
        <label for="description">normalize</label>
    </div>

    <div class="btns">
        <button type="submit"> Save </button>
    </div>
</form>