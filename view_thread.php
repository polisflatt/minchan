<html>
    <head>
        <link rel="stylesheet" href="styles.css">
    </head>

    <body>
        
        <?php
            require_once "boards/lib.php";
            require_once "captcha.php";

            
            ini_set('display_errors', 1);


            $board = $_GET["board"];
            $thread = $_GET["thread"];

	    /* Board doesn't exist */
	    if (!board_exists($board))
            {
                die("<center><h1>Board does not exist</h1></center>");
            }

	    /* Thread doesn't exist */
	    /* After a while, I thought maybe I should have a function 'board_thread_does_not_exist()' because I would be able to treat true */
	    /* as the board not existing, because that is used more often. Either approach is fine */

            if (!board_thread_exists($board, $thread))
            {
                die("<center><h1>Thread does not exist</h1></center>");
            }

            $board_json_information = board_get_json($board);

	    /* Display information about a board */
            print("<center>");
	    print("<h1>/$board/ - {$board_json_information["board_full_name"]}</h1>");
	    print("<p>{$board_json_information["description"]}</p>");
            print("</center>");


            $thread_json = board_get_thread_json($board, $thread);
            $time = date("Y/m/d H:i:s", $thread_json["time"]);

	    /* Display the actual thread post */
            print("<div class='thread'>");
            print("<span class='postername'>{$thread_json["author"]}</span> {$time} No.{$thread_json["id"]} Replies: {$thread_json["reply_count"]}");
            print("<br>");
            
            greentext($thread_json["contents"]);

            print("</div>");
        ?>

        <center><h3>Replies</h3></center>

	<?php

	    /* Display the replies */
            foreach (board_get_thread_replies($board, $thread) as $reply)
            {
                $time = date("Y/m/d H:i:s", $reply["time"]);

                print("<div class='reply' id='{$reply["id"]}'>");
                print("<span class='postername'>{$reply["author"]}</span> {$time} No.{$reply["id"]}");
                print("<br>");
                
                greentext($reply["contents"]);

		print("</div>");
		printf("<br>");
            }
        ?>

        <h4>Post Reply</h4>
        
        <center>
            <form action="post_thread_reply.php" method="post" id="reply">
                <input type="hidden" name="board" value="<?php echo $board; ?>">
                <input type="hidden" name="thread" value="<?php echo $thread; ?>">
                Name: 
                <br>
                <input type="text" name="name" value="Anonymous" size="50">
                <br>
                Comment:
                <br>
                <textarea form="reply" name="contents"></textarea>
                <br>
                <?php captcha(); ?>
                <br>
                <input type="submit" value="Post Reply">
            </form>
        </center>
    </body>
