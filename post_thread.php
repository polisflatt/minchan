<head>
        <link rel="stylesheet" href="styles.css">
</head>

<?php
    require_once "boards/lib.php";
    ini_set('display_errors', 1);


    $board = $_POST["board"];
    $name = htmlspecialchars($_POST["name"]);
    $contents = htmlspecialchars($_POST["contents"]);

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

    if (!!$tripcode)
    {
        $name = explode("#", $name)[0] . "!" . generate_tripcode(explode("#", $name)[0], $tripcode);
    }

    board_increment_count($board);
    board_post_thread($board, board_get_count($board), "", $contents, $name);

?>