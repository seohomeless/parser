<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Parser1Controller extends Controller
{
    // на регулярных выражениях

    public function index(Request $request) {  

        // links
        $uagent = "Opera/9.80 (Windows NT 6.1; WOW64) Presto/2.12.388 Version/12.14";
        if(isset($request->url)) {
          $url = $request->url;
        } else {
          $url = "http://ishtory.ru/zhalyuzi/lorem-ipsum-lorem.html";
        }
        $ch = curl_init( $url );

        // Создание запроса
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);   
        curl_setopt($ch, CURLOPT_HEADER, 0);            
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);    
        curl_setopt($ch, CURLOPT_ENCODING, "");         
        curl_setopt($ch, CURLOPT_USERAGENT, $uagent);   
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120); 
        curl_setopt($ch, CURLOPT_TIMEOUT, 120);       
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);   

        $content = curl_exec($ch);
        $header = curl_getinfo($ch);

        curl_close($ch);
   
        $header['content'] = $content;

        $urlist = preg_match('|<ul class="advanced-recent-posts">(.*)</ul>|', $content, $headers2);

        $regex = '/\b(http?|ishtory.ru):\/\/[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/i';
        preg_match_all($regex, $headers2[1], $matches);
        $urls = $matches[0];
        

      if(isset($request->namb)) {
          $number = $request->namb; 
        } else {
          $number = 1; 
        }
        

      return view('home', compact('urls', 'number'));
    } 
}
