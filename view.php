<html>
    <head>
        <link rel="stylesheet" href="styles.css">
    </head>

    <style>
        .thread
        {
            border: 2px black solid;
        }
    </style>

    <body>
        
        <?php
            require_once "boards/lib.php";
            require_once "captcha.php";

            ini_set('display_errors', 1);


            $board = $_GET["board"];

            if (!board_exists($board))
            {
                die("<center><h1>Board does not exist</h1></center>");
            }

            $board_json_information = board_get_json($board);

            print("<center>");
            print("<h1>/$board/ - {$board_json_information["board_full_name"]}</h1>");
            print("<p>{$board_json_information["description"]}</p>");
            print("</center>");
        ?>

        <center>
            <form action="post_thread.php" method="post" id="post_thread">
                <br>
                <input type="hidden" name="board" value="<?php echo $board; ?>">
                Name: 
                <br>
                <input type="text" name="name" value="Anonymous">
                <br>
                Contents 
                <br>
                <textarea form="post_thread" name="contents"></textarea>
                <br>
                <?php captcha(); ?>
                <br>
                <input type="submit" value="Post Thread">
            </form>
        </center>

        <?php
            /* Display threads */
            //$page = $_GET["page"];

            foreach (board_get_threads($board) as $thread)
            {
                $thread_json = board_get_thread_json($board, $thread);
                $time = date("Y/m/d H:i:s", $thread_json["time"]);

                print("<div class='thread'>");
                print("<span class='postername'>{$thread_json["author"]}</span> {$time} No.{$thread_json["id"]} Replies: {$thread_json["reply_count"]} <a href='view_thread.php?board=$board&thread=$thread'>View Thread</a>");
                print("<br>");

                greentext($thread_json["contents"]);

                print("</div>");

                print("<br>");
            }
        ?>

    </body>
</html>