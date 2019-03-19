@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Парсер</div>

                <div class="card-body">
                    <form method="POST" action="/get">
                        {{ csrf_field() }}
                        <label>Введите url для парсинга:</label>
                        <br><input type="text" name="url">
                        <br><label>Количество статей для сравнения:</label>
                        <br><input type="number" name="namb" min="1" max="5">
                        <p></p><input type="submit" class="btn">
                    </form>

                <table style="border: 1px solid silver; width: 100%; margin-top: 20px;">
                    <thead>
                        <th>Заголовок</th>
                        <th>заголовков h2</th>
                        <th>заголовков h3</th>
                        <th>фото</th>
                        <th>видео</th>
                        <th>символов</th>
                        <th>слов</th>
                    </thead>
                 <?php
                      $i = '';
                        foreach($urls as $url) 
                        {
                   
                          $uagent = "Opera/9.80 (Windows NT 6.1; WOW64) Presto/2.12.388 Version/12.14";
                          $url = $url;
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

                          $titleheader2 = preg_match('|<h1 class="title single-title">(.*)</h1>|', $content, $titleheader);

                          $titleheader = $titleheader[1];

                          preg_match('|<div class="post-single-content box mark-links">(.*)</index>|', $content, $headers);
                          
                          $countImg = preg_match_all('/<img[^>]+>/i', $headers[0], $imgToText);
                          $countH2 = preg_match_all('/<h2/i', $headers[0], $h2ToText);
                          $countH3 = preg_match_all('/<h3/i', $headers[0], $h3ToText);
                          $countVideo = preg_match_all('/<iframe[^>]+>/i', $headers[0], $videoToText);

                          // вырезаем рекламу и крошки
                          $contents = array("(adsbygoogle = window.adsbygoogle || []).push({});", "single_post\">", "Главная / Балкон / Шторы");
                          $contents = str_replace($contents, "", $headers[0]);
                           
                          $contentBynotTags = strip_tags($contents);
                          
                          // слов
                          $string = preg_replace('/\s+/', ' ', trim($contentBynotTags));
                          $words = explode(" ", $string);
                          $wordsCount = count($words);

                          $strLen = iconv_strlen($contentBynotTags);
                           
                          echo "<tr>";
                          echo "<td>$titleheader</td>";
                          echo "<td>$countH2</td>";
                          echo "<td>$countH3</td>";
                          echo "<td>$countImg</td>";
                          echo "<td>$countVideo</td>";
                          echo "<td>$strLen</td>";
                          echo "<td>$wordsCount</td>";
                          echo "</tr>";

                          DB::insert('insert into my_models (title, count_h2, count_h3, count_img, count_video, count_symbol, count_word) values (?, ?, ?, ?, ?, ?, ?)', [$titleheader, $countH2, $countH3, $countImg, $countVideo, $strLen, $wordsCount]);
                 
                          $i++;
                          if($i== $number) break;
                      }
                 ?>
                 </table>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
