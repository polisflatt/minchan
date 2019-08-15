<head>
        <link rel="stylesheet" href="styles.css">
</head>

<?php
    require_once "boards/lib.php";
    require_once "settings.php";

    ini_set('display_errors', 1);


    $board = htmlspecialchars($_POST["board"]);
    $name = htmlspecialchars($_POST["name"]);
    $contents = htmlspecialchars($_POST["contents"]);

    if (strlen($contents) > $char_limit)
    {
	    die("<center><h1>Your post exceeds the char limit of: $char_limit</h1></center>");
    }

    $realanswer = $_POST["realanswer"];
    $captchaanswer = $_POST["answer"];

    if ($realanswer != $captchaanswer)
    {
        die("<center><h1>Incorrect Captcha! Make sure to round decimals UP or DOWN (because computers are bad at comparing floats!)!</h1></center>");
    }

    if (!board_exists($board))
    {
        die("<center><h1>Board does not exist</h1></center>");
    }


    $tripcode = get_tripcode_password($name);

    /* If tripcode is not false or zero, which is not actually just '== 1'. */
    /* get_tripcode_password returns false when there is no tripcode password present */

    if (!!$tripcode)
    {
	/* Make the name we post have a tripcode */
        $name = explode("#", $name)[0] . "!" . generate_tripcode(explode("#", $name)[0], $tripcode);
    }

    board_increment_count($board);

    $id = board_get_count($board);

    board_post_thread($board, $id, $contents, $name);

    print("Thread created. View it <a href='view_thread.php?board=$board&thread=$id'>here</a>");
?>
