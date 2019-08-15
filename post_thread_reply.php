<?php
    require_once "boards/lib.php";
    ini_set('display_errors', 1);

    $board = $_POST["board"];
    $thread = $_POST["thread"];
    
    $realanswer = $_POST["realanswer"];
    $captchaanswer = $_POST["answer"];

    if ($realanswer != $captchaanswer)
    {
        die("<center><h1>Incorrect Captcha! Make sure to round decimals UP or DOWN (because computers are bad at comparing floats!)!</h1></center>");
    }


    $name = htmlspecialchars($_POST["name"]);
    $contents = htmlspecialchars($_POST["contents"]);

    $tripcode = get_tripcode_password($name);

    if (!!$tripcode)
    {
        $name = explode("#", $name)[0] . "!" . generate_tripcode(explode("#", $name)[0], $tripcode);
    }

    board_increment_count($board);
    board_post_thread_reply($board, $thread, board_get_count($board), $name, $contents);

    print("<h1> Reply posted, go back and refresh to see your comment </h1>");

?>