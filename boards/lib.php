<?php

    /* Return a boolean whether the board exists or not */
    function board_exists($board_name)
    {
        return is_dir("boards/$board_name/");
    }

    /* Get the current count (ID) of the board */
    function board_get_count($board_name)
    {
        return (int) file_get_contents("boards/$board_name/count");
    }

    /* Increment the current count (ID) of the board */
    function board_increment_count($board_name)
    {
        file_put_contents("boards/$board_name/count", board_get_count($board_name) + 1);
    }

    /* Return the JSON of the board */
    function board_get_json($board_name)
    {
        $json = json_decode(file_get_contents(
            "boards/$board_name/board.json"
        ), true);

        return $json;
    }

 

    function board_delete_thread($board_name, $thread)
    {
        
        unlink("boards/$board_name/threads/$thread/thread.json");
        unlink("boards/$board_name/threads/$thread/replies.json");
        
        rmdir("boards/$board_name/threads/$thread"); 
    }


    /* Post a thread to the board in question. Title is unused */
    function board_post_thread($board_name, $id, $contents, $author = "Anonymous")
    {
        mkdir("boards/$board_name/threads/$id");

        $json_thread_json = 
        [
            "id" => $id,
            "title" => "",
            "contents" => $contents,
            "author" => $author,
            "reply_count" => 0,
            "time" => time()
        ];

        file_put_contents("boards/$board_name/threads/$id/thread.json", json_encode($json_thread_json));
        file_put_contents("boards/$board_name/threads/$id/replies.json", "");
    }

    /* Return an array of all the thread ids */
    function board_get_threads($board)
    {
        return array_diff(scandir("boards/$board/threads/"), array('..', '.'));
    }

    /* Get the JSON of a thread in a board */
    /* A thread is defined by its ID */

    function board_get_thread_json($board, $thread)
    {
        $json = json_decode(file_get_contents(
            "boards/$board/threads/$thread/thread.json"
        ), true);

        return $json;
    }

    /* Returns boolean whether a thread on a given board exists */
    function board_thread_exists($board, $thread)
    {
        return is_dir("boards/$board/threads/$thread");
    }

    /* Return a JSON array of other JSON objects containing each reply */
    function board_get_thread_replies($board, $thread)
    {
        $json = json_decode(file_get_contents(
            "boards/$board/threads/$thread/replies.json"
        ), true);

        if (!$json)
        {
            return [ ];
        }

        return $json;
    }

    /* Increment the thread reply count */
    function board_increment_thread_reply_count($board, $thread)
    {
        $json = board_get_thread_json($board, $thread);
        $json["reply_count"]++;

        file_put_contents("boards/$board/threads/$thread/thread.json", json_encode($json));
    }

    /* Not to be confused with board_get_thread_replies function() */ 
    /* Get reply count on a thread */
    function board_get_thread_reply_count($board, $thread)
    {
        $json = board_get_thread_json($board, $thread);
        return (int) $json["reply_count"];
    }

    /* Post a reply on a thread */
    function board_post_thread_reply($board, $thread, $id, $author = "Anonymous", $contents)
    {
        $json = board_get_thread_replies($board, $thread);

        $json_thread_reply_json = 
        [
            "id" => $id,
            "contents" => $contents,
            "author" => $author,
            "time" => time()
        ];

        board_increment_thread_reply_count($board, $thread);
        array_push($json, $json_thread_reply_json);
        file_put_contents("boards/$board/threads/$thread/replies.json", json_encode($json));
    }


    /* Misleading function name. Originally, it was used to format a reply or a thread so that it would be able to add colour to greentexts. */
    /* However, it also can format comments so that it can point to other IDs */
    /* Sadly, it is hardcoded right now. */

    function greentext($contents)
    {
        foreach (explode("\n", $contents) as $line)
        {
            if (strStartsWith($line, "&gt;&gt;"))
            {
                $id = str_replace("&gt;&gt;", "", $line);
                print("<a href='#$id'>$line</a>");
            }
            else if (strStartsWith($line, "&gt;"))
            {
                print("<span class='greentext'>$line</span><br>");
            }
            else
            {
                print("<span>$line</span><br>");
            }
        }
    }


    /* https://thisinterestsme.com/php-check-string-starts-with-string/ */
    /* Not my code (I am this lazy to make my own string function), but I refurbished it a bit to make it look nicer. */
    /* I used to place my curly brackets in K&R format, but I realize now that it is obscenely ugly and disgusting. I write my curly braces normally now. */
    
    function strStartsWith($haystack, $needle, $caseSensitive = true)
    {
        /* Get the length of the needle. */
        $length = strlen($needle);
        
        /* Get the start of the haystack. */
        $startOfHaystack = substr($haystack, 0, $length);
        
        /* If we want our check to be case sensitive. */
        
        if($caseSensitive)
        {
            /* Strict comparison */
            if ($startOfHaystack === $needle)
                return true;
        } 
        else
        {
            /* Case insensitive. */
            /* If the needle and the start of the haystack are the same. */
            
            if (!strcasecmp($startOfHaystack, $needle))
                return true;
        }
        
        /* No matches. Return FALSE.*/
        return false;
    }

    /* Extract the tripcode password out of a username */
    function get_tripcode_password($username)
    {
        try
        {
            $tripcode = explode("#", $username)[1]; /* Obtain text after the #, which is the password */
            return $tripcode;
        }
        catch (Exception $e)
        {
            return false;
        }
    }

    /* Generate a tripcode on basis of username and password, hashed by SHA256 */
    /* SHA256 is utter overkill for something as simple as a tripcode, but it's funny since */
    /* QAnon's (conspiracy scary g-man guy) tripcode got cracked multiple times because most text/image boards use DES for their encryption method */
    /* Which is actually not that secure by today's standards. */

    function generate_tripcode($username, $password)
    {
        return hash("sha256", $username . $password, false);
    }
?>
