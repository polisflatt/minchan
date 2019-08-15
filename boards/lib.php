<?php

    function board_exists($board_name)
    {
        return is_dir("boards/$board_name/");
    }

    function board_get_count($board_name)
    {
        return (int) file_get_contents("boards/$board_name/count");
    }

    function board_increment_count($board_name)
    {
        file_put_contents("boards/$board_name/count", board_get_count($board_name) + 1);
    }

    function board_get_json($board_name)
    {
        $json = json_decode(file_get_contents(
            "boards/$board_name/board.json"
        ), true);

        return $json;
    }

    function board_post_thread($board_name, $id, $title, $contents, $author = "Anonymous")
    {
        mkdir("boards/$board_name/threads/$id");

        $json_thread_json = 
        [
            "id" => $id,
            "title" => $title,
            "contents" => $contents,
            "author" => $author,
            "reply_count" => 0,
            "time" => time()
        ];

        file_put_contents("boards/$board_name/threads/$id/thread.json", json_encode($json_thread_json));
        file_put_contents("boards/$board_name/threads/$id/replies.json", "");
    }

    function board_get_threads($board)
    {
        return array_diff(scandir("boards/$board/threads/"), array('..', '.'));
    }

    function board_get_thread_json($board, $thread)
    {
        $json = json_decode(file_get_contents(
            "boards/$board/threads/$thread/thread.json"
        ), true);

        return $json;
    }

    function board_thread_exists($board, $thread)
    {
        return is_dir("boards/$board/threads/$thread");
    }

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

    function board_increment_thread_reply_count($board, $thread)
    {
        $json = board_get_thread_json($board, $thread);
        $json["reply_count"]++;

        file_put_contents("boards/$board/threads/$thread/thread.json", json_encode($json));
    }

    /* Not to be confused with board_get_thread_replies function() */ 
    function board_get_thread_reply_count($board, $thread)
    {
        $json = board_get_thread_json($board, $thread);
        return (int) $json["reply_count"];
    }

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
                print("<p class='greentext'>$line</p>");
            }
            else
            {
                print("<p>$line</p>");
            }
        }
    }


    /* https://thisinterestsme.com/php-check-string-starts-with-string/ */
    function strStartsWith($haystack, $needle, $caseSensitive = true)
    {
        //Get the length of the needle.
        $length = strlen($needle);
        //Get the start of the haystack.
        $startOfHaystack = substr($haystack, 0, $length);
        //If we want our check to be case sensitive.
        if($caseSensitive){
            //Strict comparison.
            if($startOfHaystack === $needle){
                return true;
            }
        } else{
            //Case insensitive.
            //If the needle and the start of the haystack are the same.
            if(strcasecmp($startOfHaystack, $needle) == 0){
                return true;
            }
        }
        //No matches. Return FALSE.
        return false;
    }

    function get_tripcode_password($username)
    {
        $tripcode = explode("#", $username)[1];
        return $tripcode;
    }

    function generate_tripcode($username, $password)
    {
        return hash("sha256", $username . $password, false);
    }
?>