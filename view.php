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
            require_once "settings.php";

	    
	    /* For debugging purposes */
	    //ini_set('display_errors', 1);


            $board = htmlspecialchars($_GET["board"]);

	    /* Kill ourself if the board doesn't exist */
            if (!board_exists($board))
            {
                die("<center><h1>Board does not exist</h1></center>");
            }

            $board_json_information = board_get_json($board);

	    
	    /* Print the title of the board and other information about it in a friendly way */

	    print("<center>");
	    
	    /* Security vulnerability, even though people won't be able to reach it if the board doesn't exist */
	    /* $board is escaped now */
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
		<?php captcha(); /* Display captcha */ ?>
                <br>
                <input type="submit" value="Post Thread">
            </form>
        </center>

	<?php
            /* Display threads */

	    /* For when we add a page number system so that viewing the boards won't crash your browser */
	    /* I don't know how to implement this yet. I was thinking of having the page number be some */
	    /* kind of fraction that I'd use to get a certain range of threads from an array, but I don't know */

	    //$page = $_GET["page"];

	    /* Get all threads */
            foreach (board_get_threads($board) as $thread)
            {
                $thread_json = board_get_thread_json($board, $thread);
		        $time = date("Y/m/d H:i:s", $thread_json["time"]);

                print("<div class='thread'>");
                print("<span class='postername'>{$thread_json["author"]}</span> {$time} No.{$thread_json["id"]} Replies: {$thread_json["reply_count"]} <a href='view_thread.php?board=$board&thread=$thread'>View Thread</a>");
                print("<br>");

                /* Delete threads that have over max replies */

                if (board_get_thread_reply_count($board, $thread) > $reply_limit)
                {
                    board_delete_thread($board, $thread);
                }


                greentext($thread_json["contents"]);

                print("</div>");

                print("<br>");
            }
        ?>

    </body>
</html>
