<?php
    function captcha()
    {
        $number = rand(1, 10);
        $number_two = rand(1, 10);

        $operation = rand(1, 4);
        $answer = 0;

        /* I had way too much fun with the captcha operations */
        /* The other functions can be used for April Fools, or something */

        switch ($operation)
        {
            case 1:
                print("<h4>$number + $number_two = ?</h4>");
                $sum = $number + $number_two;
                $answer = $sum;
                break;
            
            case 2:
                print("<h4>$number - $number_two = ?</h4>");
                $difference = $number - $number_two;
                $answer = $difference;
                break;
            
            case 3:
                print("<h4>$number * $number_two = ?</h4>");
                $product = $number * $number_two;
                $answer = $product;
                break;
            
            case 4:
                print("<h4>$number / $number_two = ?<br>(Round floats)</h4>");
                $quotient = $number / $number_two;
                $answer = round($quotient);
                break;

            case 5:
                print("<h4>√$number = ?<br>(Round floats)</h4>");
                $sqrt = sqrt($number);
                $answer = round($sqrt);
                break;
            
            case 6:
                print("<h4>sin(1/$number) = ?<br>(Round floats) (Put NAN if Error) (In degrees)</h4>");
                $answer = round(sin(1/$number));
                break;
            
            case 7:
                print("<h4>cos(1/$number) = ?<br>(Round floats) (Put NAN if Error) (In radians)</h4>");
                $answer = round(cos(1/$number));
                break;

            case 8:
                print("<h4>tan(1/$number) = ?<br>(Round floats) (Put NAN if Error) (In radians)</h4>");
                $answer = round(tan(1/$number));
                break;
            
            case 9:
                print("<h4>$number % $number_two) = ?<br>(Round floats)</h4>");
                $answer = round($number % $number_two);
                break;

            case 10:
                print("<h4>$number<sup>$number_two</sup> = ?<br></h4>");
                $answer = pow($number, $number_two);
                break;
            
            case 11:
                print("<h4>tan($number)<sup>-1</sup> = ?<br>(Round floats) (Put NAN if Error) (In degrees)</h4>");
                $answer = round(atan($number));
                break;

            case 12:
                print("<h4>sin($number)<sup>-1</sup> = ?<br>(Round floats) (Put NAN if Error) (In radians)</h4>");
                $answer = round(asin($number));
                break;

            case 13:
                print("<h4>cos($number)<sup>-1</sup> = ?<br>(Round floats) (Put NAN if Error) (In degrees)</h4>");
                $answer = round(acos($number));
                break;
        }

        print("<input type='text' name='answer'>");
        print("<input type='hidden' name='realanswer' value='$answer'>");
    }
?>

