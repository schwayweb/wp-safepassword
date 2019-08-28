<?php

class spcmsMenu {
  
    function __construct(){
    }
    
    /*
     *  Backend CMS
     */ 
    function load() {
        global $spcms;
        global $spsafepwd;
        global $sptext;
        global $spclasses;
      
        // Wordpress
        if($spcms['type'] == 'wordpress') {
          
            if (function_exists('add_options_page')){
              
                if($spcms['role'] == 'admin') {
                    add_menu_page($sptext['title'], $sptext['title'], 'manage_options', 'spsafepwd-connection', array(&$spsafepwd->main, 'display'), 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/PjxzdmcgaGVpZ2h0PSIzMnB4IiB2ZXJzaW9uPSIxLjEiIHZpZXdCb3g9IjAgMCAzMiAzMiIgd2lkdGg9IjMycHgiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6c2tldGNoPSJodHRwOi8vd3d3LmJvaGVtaWFuY29kaW5nLmNvbS9za2V0Y2gvbnMiIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIj48dGl0bGUvPjxkZXNjLz48ZGVmcy8+PGcgZmlsbD0ibm9uZSIgZmlsbC1ydWxlPSJldmVub2RkIiBpZD0iUGFnZS0xIiBzdHJva2U9Im5vbmUiIHN0cm9rZS13aWR0aD0iMSI+PGcgZmlsbD0iIzkyOTI5MiIgaWQ9Imljb24tMTE4LWxvY2stcm91bmRlZCI+PHBhdGggZD0iTTE2LDIxLjkxNDY0NzIgTDE2LDI0LjUwODk5NDggQzE2LDI0Ljc4MDE2OTUgMTYuMjMxOTMzNiwyNSAxNi41LDI1IEMxNi43NzYxNDI0LDI1IDE3LDI0Ljc3MjExOTUgMTcsMjQuNTA4OTk0OCBMMTcsMjEuOTE0NjQ3MiBDMTcuNTgyNTk2MiwyMS43MDg3MjkgMTgsMjEuMTUzMTA5NSAxOCwyMC41IEMxOCwxOS42NzE1NzI4IDE3LjMyODQyNzIsMTkgMTYuNSwxOSBDMTUuNjcxNTcyOCwxOSAxNSwxOS42NzE1NzI4IDE1LDIwLjUgQzE1LDIxLjE1MzEwOTUgMTUuNDE3NDAzOCwyMS43MDg3MjkgMTYsMjEuOTE0NjQ3MiBMMTYsMjEuOTE0NjQ3MiBaIE05LDE0LjAwMDAxMjUgTDksMTAuNDk5MjM1IEM5LDYuMzU2NzA0ODUgMTIuMzU3ODY0NCwzIDE2LjUsMyBDMjAuNjMzNzA3MiwzIDI0LDYuMzU3NTIxODggMjQsMTAuNDk5MjM1IEwyNCwxNC4wMDAwMTI1IEMyNS42NTkxNDcxLDE0LjAwNDc0ODggMjcsMTUuMzUwMzE3NCAyNywxNy4wMDk0Nzc2IEwyNywyMiBDMjcsMjYuNDA5Mjg3NyAyMy40MTg2NzgyLDMwIDE5LjAwMDg5MzksMzAgTDEzLjk5OTEwNjEsMzAgQzkuNTg2MTY3NzEsMzAgNiwyNi40MTgyNzggNiwyMiBMNiwxNy4wMDk0Nzc2IEM2LDE1LjMzOTU4MSA3LjM0MjMzMzQ5LDE0LjAwNDcxNTIgOSwxNC4wMDAwMTI1IEw5LDE0LjAwMDAxMjUgTDksMTQuMDAwMDEyNSBaIE0xMiwxNCBMMTIsMTAuNTAwODUzNyBDMTIsOC4wMDkyNDc4IDE0LjAxNDcxODYsNiAxNi41LDYgQzE4Ljk4MDIyNDMsNiAyMSw4LjAxNTEwMDgyIDIxLDEwLjUwMDg1MzcgTDIxLDE0IEwxMiwxNCBMMTIsMTQgTDEyLDE0IFoiIGlkPSJsb2NrLXJvdW5kZWQiLz48L2c+PC9nPjwvc3ZnPg==');
                }
            }
        }
    }
}