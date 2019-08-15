# Minchan - Minimal textboard in PHP
## About
![enter image description here](https://i.ibb.co/FH2fZfr/2019-08-15-121249-1600x714-scrot.png)
Minchan is a project developed to offer a very minimal textboard, in PHP, with code that anyone can use for their own advances. It is very simple and minimal, so it is easy to understand (although, right now, it's not very commented; it will be in the future). It uses no SQL database system, nor does it require any server scheduled tasks. You don't need a dedicated server. All you need is simple hosting with PHP support. Minchan was written completely from scratch, with no support from other text/image boards (however, the CSS it uses is a common one among many image/text boards). 

You can view minchan's internal system of managing boards, threads, and posts in *boards/lib.php* where all of the functions lie. It acts like procedural code, so there are no classes, but that is what makes this software minimal.

This project is in very early development, so there are a surplus of bugs right now. For instance, there is a huge possibility of the website being filled with spam, due to no protection on the amount of replies and threads one can make. It does have a captcha, but it is math-based and it can be solved using a bot nonetheless. Other bugs exist, too, but they will be fixed in later releases.

I would not recommend to host Minchan on anything else but a home server which is not publicly available due to the reasons aforementioned. This advisory will change in the future. However, I am hosting here at: [https://minchan.000webhostapp.com](https://minchan.000webhostapp.com/). 

