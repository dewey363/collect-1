<?php
/**
 * @Author: anchen
 * @Date:   2016-03-20 20:11:44
 * @Last Modified by:   anchen
 * @Last Modified time: 2016-03-20 22:39:47
 */
set_time_limit(0);
header("content-type:text/html;charset=utf-8");
function caiji($page_url,$page,$file_name){
    if ($page != 1) {
        $new_page_url = $page_url."?page=".$page;
    }else{
        $new_page_url = $page_url;
    }
    echo $new_page_url;
    echo "<br>";
    $page_content = file_get_contents($new_page_url);
    $page_count = preg_match_all('/<td class=\"span2\"><strong>(.*)<\/strong><\/td>/', $page_content, $page_list);
    if($page_count>0){
        $page++;
        foreach ($page_list[1] as $k3 => $v3) {
            file_put_contents(iconv('UTF-8','gbk',$file_name.'.txt'), $v3."\r\n", FILE_APPEND);
        }
        caiji($page_url,$page,$file_name);
    }

}
//caiji('http://www.shanbay.com/wordlist/34/63685/?page=11');die();
$site_url = 'http://www.shanbay.com';
$url = "http://www.shanbay.com/wordbook/category/10/";
$content = file_get_contents($url);
//$count = preg_match_all('/class="wordbook-title">\s[^_]+<div class="wordbook-owner">/', $content, $url_list);
$count = preg_match_all('/<a href=\"(\/wordbook\/\d+)\/">([^<img][^更多].*)<\/a>/', $content, $url_list);
//$count = preg_match_all('/<a href=\"(.*)\/\"><img src/', $content, $url_list);
foreach ($url_list[1] as $k => $v) {
    if ($k <= 78) {
        continue;
    }
    $dirname = $url_list[2][$k];
    mkdir(iconv('utf-8','gbk',$dirname));
    echo "book url";
    echo "<br>";
    echo $site_url.$v;
    echo "<br>";
    $book_content = file_get_contents($site_url.$v);
    $book_count = preg_match_all('/<a href=\"(\/wordlist\/\d+\/\d+\/)\">(.*)<\/a>/', $book_content, $book_list);
    foreach ($book_list[1] as $k2 => $v2) {
        $page_content = file_get_contents($site_url.$v2);
        $page = 1;
        $file_name = $dirname.'/'.$book_list[2][$k2];
        echo "page url";
        echo "<br>";
        caiji($site_url.$v2,$page,$file_name);
    }
}