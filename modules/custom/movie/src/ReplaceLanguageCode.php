<?php

namespace Drupal\movie;
use Drupal\paragraphs\Entity\Paragraph;
use voku\helper\HtmlDomParser;
use Drupal\Component\Serialization\Json;
use Drupal\pathauto\PathautoState;
use Drupal\taxonomy\Entity\Term;
use Drupal\Core\File\FileSystemInterface;
use GuzzleHttp\Exception\ClientException;

class ReplaceLanguageCode {

  public static function termcode($tid, &$context){
    $term = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($tid);
    if (!$term->get('path')->isEmpty()) {
        $term_alias = str_replace('genre','category',$term->get('path')->alias);
        
       
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_HEADER, false);
        //curl_setopt($curl, CURLOPT_HTTPPROXYTUNNEL, 0);
        //curl_setopt($curl, CURLOPT_PROXY, '13.91.243.29:3128');
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_URL, 'https://www.watch-movies.com.pk'.$term_alias);
        curl_setopt($curl, CURLOPT_REFERER, 'https://www.watch-movies.com.pk/');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:88.0) Gecko/20100101 Firefox/88.0");
        $str = curl_exec($curl);
        curl_close($curl);
        // print $str;
        // exit;
          $movie = [];
          $dom = HtmlDomParser::str_get_html($str);
        
          $list = array();
        
          
        
          $term->field_title->value = $dom->findOne("title")->text();
         // print $dom->findOne("title")->text();
          
          $items = $dom->find('meta');

      //   print $dom->find('meta', 3)->getAttribute('content');
          
       if($dom->find('meta', 3)->getAttribute('name')=='description'){
        $term->field_description->value = $dom->find('meta', 3)->getAttribute('content');
       }
        
          $results[] = $term->save();
    }
   
   }

public static function replaceLangcode3($nid, &$context){
  $node = \Drupal::entityTypeManager()->getStorage('node')->load($nid);

  

   $results[] = $term->delete();
 }

 //image save
   public static function replaceLangcode2($nid, &$context){
    $node = \Drupal::entityTypeManager()->getStorage('node')->load($nid);
    $message = 'Replacing langcode(und to de)...';
    if($node->field_image_urls->value && $node->field_image_urls->value!='https://www.watch-movies.com.pk/wp-content/uploads/2014/12/Mad_About_Dance_Official_Poster.jpg'){
     
     try {
      $http = \Drupal::httpClient();
      $options = [
       'headers' => [
         'User-Agent' => 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:88.0) Gecko/20100101 Firefox/88.0',
         'Referer' => 'https://www.watch-movies.com.pk/'
         ]
     ];
      $result = $http->request('get',$node->field_image_urls->value, $options);
       if ($result->getStatusCode() == 200) {
           
      $body_data = $result->getBody()->getContents();
      
       $filepath = $node->field_image_urls->value;
       $image_url = $node->field_image_urls->value;
       $directory = dirname(str_replace("https://www.watch-movies.com.pk/","",$image_url));
       $directory = 'public://'.$directory;

 $file_system = \Drupal::service('file_system');
 $file_system->prepareDirectory($directory, FileSystemInterface:: CREATE_DIRECTORY | FileSystemInterface::MODIFY_PERMISSIONS);
 $fileRepository = \Drupal::service('file.repository');
 $fileRepository->writeData($body_data, $directory . '/' . basename($filepath), FileSystemInterface::EXISTS_REPLACE);
                }
              }
 catch (ClientException $e) {
         \Drupal::logger('image_resize_filter')->error('File %src was not found on remote server.', ['%src' => $node->field_image_urls->value]);
         }
       }
 
 $results[] = '1';
   }

  public static function replaceLangcode($nid, &$context){
    $node = \Drupal::entityTypeManager()->getStorage('node')->load($nid);
    $message = 'Replacing langcode(und to de)...';
    $load ='';
    if($node->field_season->value=='' && $node->field_subjecttype->value=='2')
    {
      $load =1;
    }
    if($node->field_subjecttype->value=='2'){
    if(!$node->field_load_time->value){
      $load =1;
    }
    if(strtotime("+1 days", $node->field_load_time->value) < time()){
      $load =1;
    }
  }
  
  //$load ='';
 //  $load =1;
  //  print $load;
  //   exit;
    if($load==''){ return true; }
//  print $node->field_url->value;
// var_export($node->field_subjectid->value);
//  exit;
   $message2 = getmoviebox_detail_session($node->field_detailpath->value,$node->field_subjectid->value);
 // $message2 = getmoviebox_detail_session_old($node->field_detailpath->value,$node->field_subjectid->value);
 
  
// print_r($message2);
 
//    exit;
  
    $results = array();

   //////////////////////////////////////////////
  
   if (@$message2['field_season']) {
    $node->field_season->value = $message2['field_season'];
    $node->field_load_time->value = time();
    }
    
    // if ($message2['field_description']) {
    //   $node->field_description->value = $message2['field_description'];
    //   }
    
   
    $results[] = $node->save();
    
  }

 public static function replaceLangcodeFinishedCallback($success, $results, $operations) 
 {
    // The 'success' parameter means no fatal PHP errors were detected. All
    // other error management should be handled using 'results'.
    if ($success) {
      $message = \Drupal::translation()->formatPlural(
        count($results),
        'One post processed.', '@count posts processed.'
      );
    }
    else {
      $message = t('Finished with an error.');
    }
    \Drupal::messenger()->addMessage($message);
  }

  public static function getmovie2($i, &$context)
{
  $message = 'Replacing langcode(und to de)...';
     $results = array();
  //  $new_var = theme_get_setting('new_domain_name');
  //     $oldStr = theme_get_setting('old_domain_name');
  //     $oldStr = explode(",", $oldStr);
   
  // $url = str_replace($oldStr, $new_var, $url );
  $curl = curl_init();
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($curl, CURLOPT_HEADER, false);
//curl_setopt($curl, CURLOPT_HTTPPROXYTUNNEL, 0);
//curl_setopt($curl, CURLOPT_PROXY, '13.91.243.29:3128');
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
if($i==1){
  curl_setopt($curl, CURLOPT_URL, 'https://www.watch-movies.com.pk/');
  //exit;
}else{
  curl_setopt($curl, CURLOPT_URL, 'https://www.watch-movies.com.pk/page/'.$i.'/');
}

curl_setopt($curl, CURLOPT_REFERER, 'https://www.watch-movies.com.pk/');
curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:88.0) Gecko/20100101 Firefox/88.0");
$str = curl_exec($curl);
curl_close($curl);
// print $str;
// exit;
  $movie = [];
  $dom = HtmlDomParser::str_get_html($str);

  $list = array();

  $items = $dom->find('#hpost .postbox');
    $t=1;
      foreach($items as $post) {
         
      // $list[] = array(
      //   'title'=>$post->findOne(".boxtitle h2 a")->getAttribute('title'),
      //   'href'=>$post->findOne(".boxtitle h2 a")->getAttribute('href'),
      //   'img'=>$post->findOne(".boxtitle a img")->getAttribute('data-src'),
      //   'view'=>$post->findOne(".boxmetadata .views")->text(),

      //   );

      $title=$post->findOne(".boxtitle h2 a")->getAttribute('title');
        $href=$post->findOne(".boxtitle h2 a")->getAttribute('href');
        $img=$post->findOne(".boxtitle a img")->getAttribute('data-src');
        $view=$post->findOne(".boxmetadata .views")->text();
        $healthy = ["Watch", "Online", "HD", "Download", "Free","Print"];
        $title=str_replace($healthy,'',$title);
        $img=str_replace("-200x200",'',$img);
        $view=str_replace(",",'',$view);
        $view = preg_replace('/\D/', '', $view);
        $nodes = \Drupal::entityTypeManager()
        ->getStorage('node')
        ->loadByProperties(['title' => $title]);
      // Load the first node returned from the database.
        $node = reset($nodes);
       if($t<=12){
       
       if(@$node->title->value){
        $node->field_tag = [5];
        $node->field_views->value = $node->field_views->value+1;
        $results[] = $node->save();
        
       }else{
        $node = \Drupal::entityTypeManager()->getStorage('node')->create([
          'type'       => 'movie',
          'field_url' => $href,
          'title'      => $title,
          'field_image_urls' => $img,
          'field_views' => $view,
          'field_tag' => '5',

        ]);
        $results[] = $node->save();
      }
        

       }else {
        if(@$node->title->value==''){
          $node = \Drupal::entityTypeManager()->getStorage('node')->create([
            'type'       => 'movie',
            'field_url' => $href,
            'title'      => $title,
            'field_image_urls' => $img,
            'field_views' => $view
  
          ]);
          $results[] = $node->save();
        }
       
      }
        $t++;
  
  }
 
//////////////////////////////////////////////////////////
// print "<pre>";
//     print_r($list);
//     print "</pre>";
//     exit;


}

 public static function getmoviebox($i,$platform,$month,$ranking_id,$block_id,$channel_id,$api,$post, &$context)
{
//   var_export($channel_id);
//  exit;
  //https://h5.inmoviebox.com/wefeed-h5-bff/web/subject/play?subjectId=7415754612038583632&se=1&ep=1
 $message = 'Replacing langcode(und to de)...';
    $results = array();
  // 
  if($platform!=''){
    $data = curlgetmoviebox_platform($i,$platform,$month);
    $items = $data['data']['subjects'];
  
      save_movie_box($items,$platform,$month); 
     
      
       }elseif($ranking_id && $block_id){
       // exit;
        $data = curlgetmoviebox_ranking($i,$ranking_id);
        $items = $data['data']['subjectList'];
        // var_export($items);
        //   exit;
        if($i==1){
   
    $block = \Drupal\block_content\Entity\BlockContent::load($block_id);
    $block->get('field_movie')->setValue(NULL);
    $block->save();
  }
        save_movie_box($items,'','',$block_id,$i);
           }else{
    $data = curlgetmoviebox($i,$api,$post);
    $items = $data['data']['items'];
    // var_export($data['data']);
    //   exit;
    save_movie_box($items);
       }
}
}

function curlgetmoviebox($i,$api,$post){
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
  curl_setopt($curl, CURLOPT_HEADER, false);
  curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
  
 // curl_setopt($curl, CURLOPT_URL, 'https://prmovies.world/test.php?page='.$i.'&perPage=24&channelId='.$channel_id);
  //curl_setopt($curl, CURLOPT_URL, 'https://api6.aoneroom.com/wefeed-mobile-bff/subject-api/list');
  curl_setopt($curl, CURLOPT_URL, $api);
  
  //curl_setopt($curl, CURLOPT_URL, 'https://h5.inmoviebox.com/wefeed-h5-bff/web/class-month');
  
   curl_setopt($curl, CURLOPT_POST, 1);
  // curl_setopt($curl, CURLOPT_POSTFIELDS, "page=".$i."&channelId=".$channel_id);
  curl_setopt($curl, CURLOPT_POSTFIELDS, "page=".$i.$post);
 //curl_setopt($curl, CURLOPT_POSTFIELDS, "page=".$i."&perPage=12&channelId=".$channel_id."&genre=All&country=India&classify=All&sort=Latest&year=All");
   
  //curl_setopt($curl, CURLOPT_POSTFIELDS, "page=".$i."&perPage=24&platform=Netflix");
  //curl_setopt($curl, CURLOPT_REFERER, 'https://watch23.shop/');
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
  //curl_setopt($curl, CURLOPT_USERAGENT, "com.community.oneroom/50020038 (Linux; U; Android 7.1.2; hi_IN; SM-N976N; Build/QP1A.190711.020; Cronet/136.0.7064.0)");
  // curl_setopt($curl, CURLOPT_HTTPHEADER , array(
  //   'Referer: https://api6.aoneroom.com',
  //   'Origin: https://api6.aoneroom.com',
  //   'authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1aWQiOjMzNzA3MTI1MDUwNTkzNjMwMDgsImV4cCI6MTc1MjE0NDI1MCwiaWF0IjoxNzQ0MzY3OTUwfQ.sedWQ7HL-5WPOqWceQXoR4fnaGg6y3xmqT6GzcVCyGU',
  //   'Host: api6.aoneroom.com',
  //   'x-tr-signature: 1744448656252|2|G2yxmgowWJjgahvtpBXArw==',
  //   'user-agent:: com.community.oneroom/50020038 (Linux; U; Android 7.1.2; hi_IN; SM-N976N; Build/QP1A.190711.020; Cronet/136.0.7064.0)',
  //   //'x-client-info: {"package_name":"com.community.oneroom","version_name":"3.0.01.0411.03","version_code":50020038,"os":"android","os_version":"7.1.2","install_ch":"ps","device_id":"ce2435d7e22e3fb3dc80710311df803a","install_store":"ps","gaid":"ddf9ce6c-fed8-4704-abb4-d79915482cc7","brand":"samsung","model":"SM-N976N","system_language":"hi","net":"NETWORK_WIFI","region":"IN","timezone":"Asia/Calcutta","sp_code":"40416","X-Play-Mode":"2"}'
  //   'x-client-info: {"package_name":"com.community.oneroom","version_name":"3.0.01.0411.03","version_code":50020038,"os":"android","os_version":"7.1.2","install_ch":"ps","device_id":"ce2435d7e22e3fb3dc80710311df803a","install_store":"ps","gaid":"ddf9ce6c-fed8-4704-abb4-d79915482cc7","brand":"samsung","model":"SM-N976N","system_language":"tl","net":"NETWORK_WIFI","region":"PH","timezone":"Asia/Calcutta","sp_code":"51502","X-Play-Mode":"2"}'

  // ));
  curl_setopt($curl, CURLOPT_HTTPHEADER , array(
    'referer: https://h5.inmoviebox.com/',
    'origin: https://h5.inmoviebox.com',
    'accept: application/json',
    'accept-language: en-US,en;q=0.9',
    'content-type: application/json',
    'Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1aWQiOjYzODI5MzIyNjA1NTk1MDQ4MTYsImF0cCI6MywiZXh0IjoiMTc2NDg0OTY1MCIsImV4cCI6MTc3MjYyNTY1MCwiaWF0IjoxNzY0ODQ5MzUwfQ.2Ch9oAezqOUskmFoEMrlD4dCCsWJx7r46L0ewDEItQM',
    'Connection: keep-alive',
    'x-request-lang: en',
    'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36',
    'x-client-info: {"timezone":"Asia/Calcutta"}'
    //'x-client-info: {"package_name":"com.community.oneroom","version_name":"3.0.01.0411.03","version_code":50020038,"os":"android","os_version":"7.1.2","install_ch":"ps","device_id":"ce2435d7e22e3fb3dc80710311df803a","install_store":"ps","gaid":"ddf9ce6c-fed8-4704-abb4-d79915482cc7","brand":"samsung","model":"SM-N976N","system_language":"tl","net":"NETWORK_WIFI","region":"PH","timezone":"Asia/Calcutta","sp_code":"51502","X-Play-Mode":"2"}'
  ));
  $str = curl_exec($curl);
  curl_close($curl);
 // print $str;
  //exit;
 print "<pre>";
 print_r($str); exit;
  
   $data = json_decode($str,true);
   return $data;
   
}

function curlgetmoviebox_ranking($i,$ranking_id){
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
  curl_setopt($curl, CURLOPT_HEADER, false);
  curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
  
  
  curl_setopt($curl, CURLOPT_URL, "https://h5.inmoviebox.com/wefeed-h5-bff/web/ranking-list/content?id=".$ranking_id."&page=".$i."&perPage=20");
  
  //curl_setopt($curl, CURLOPT_POST, 1);
  //curl_setopt($curl, CURLOPT_POSTFIELDS, "page=".$i."&perPage=10&type=".$ranking_id);
  //curl_setopt($curl, CURLOPT_REFERER, 'https://h5.inmoviebox.com/');
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36");
 // curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:88.0) Gecko/20100101 Firefox/88.0");
  curl_setopt($curl, CURLOPT_HTTPHEADER , array(
    'Referer: https://h5.inmoviebox.com/',
    'Origin: https://h5.inmoviebox.com',
    'Host: h5.inmoviebox.com',
    'Connection: keep-alive',
    'X-Forwarded-For: http://localhost'
  ));
  
  
  $str = curl_exec($curl);
  curl_close($curl);
  // print $str;
  // exit;
  //var_dump(json_decode($str, true)); exit;
  
   $data = json_decode($str,true);
   return $data;
   
}

function curlgetmoviebox_platform($i,$platform,$month){
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
  curl_setopt($curl, CURLOPT_HEADER, false);
  curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
  
  //curl_setopt($curl, CURLOPT_URL, 'https://prmovies.world/test.php?page='.$i.'&perPage=12&platform='.$platform.'&month='.$month);
  
  curl_setopt($curl, CURLOPT_URL, "https://h5.inmoviebox.com/wefeed-h5-bff/web/class-month?page=".$i."&perPage=12&platform=".$platform."&month=".$month);
  
  // curl_setopt($curl, CURLOPT_POST, 1);
  // curl_setopt($curl, CURLOPT_POSTFIELDS, "page=".$i."&perPage=12&platform=".$platform."&month=".$month);
  // //curl_setopt($curl, CURLOPT_REFERER, 'https://h5.inmoviebox.com/');
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:88.0) Gecko/20100101 Firefox/88.0");
  curl_setopt($curl, CURLOPT_HTTPHEADER , array(
    'Referer: https://h5.inmoviebox.com/',
    'Origin: https://h5.inmoviebox.com/',
    'Accept: */*',
    'Host: h5.inmoviebox.com',
    'Connection: keep-alive',
    'user-agent:: com.community.oneroom/50020038 (Linux; U; Android 7.1.2; hi_IN; SM-N976N; Build/QP1A.190711.020; Cronet/136.0.7064.0)',
    'x-client-info: {"package_name":"com.community.oneroom","version_name":"3.0.01.0411.03","version_code":50020038,"os":"android","os_version":"7.1.2","install_ch":"ps","device_id":"ce2435d7e22e3fb3dc80710311df803a","install_store":"ps","gaid":"ddf9ce6c-fed8-4704-abb4-d79915482cc7","brand":"samsung","model":"SM-N976N","system_language":"hi","net":"NETWORK_WIFI","region":"IN","timezone":"Asia/Calcutta","sp_code":"40416","X-Play-Mode":"2"}'
    
  ));
  $str = curl_exec($curl);
  curl_close($curl);
  //print $str;
 // exit;
 // var_dump(json_decode($str, true)); exit;
  
   $data = json_decode($str,true);
   return $data;
   
}

function save_movie_box($items,$platform='',$month='',$block_id='',$i=''){
  foreach($items as $post) {
    if($block_id){
     // $post = $post['subjectList'];
     // $post['duration']=$post['durationSeconds'];
    }
   //   var_export($post);
    // exit;
    
    $query = \Drupal::database()->select('node__field_subjectid', 't');
    $query->fields('t', ['entity_id']);
    $query->condition('field_subjectid_value', $post['subjectId']);
    $result = $query->countQuery()->execute()->fetchField();
    $nid = $query->execute()->fetchField();
   //  var_export($result);
    // exit;
     if($nid){
    //  $node = \Drupal::entityTypeManager()->getStorage('node')->load($nid);
     // $node->field_detailpath->value = $post['detailPath'];
     //  $results[] = $node->save();
    }
   if($nid && $platform){
      $node = \Drupal::entityTypeManager()->getStorage('node')->load($nid);
      $node->field_platform->value = $platform;
      $node->field_month->value = $month;
      $node->field_description->value = $post['description'];
       $results[] = $node->save();
    }
   else if($nid && $block_id){
      
    //$alt_title = Drupal\block\Entity\Block::load('1')->field_tanking_list_id->value;
    
           block_save($nid,$block_id,$i);
           
    
        }
    else if($result<1){
    
    
    /////tags
    $field_tags =[];
    $genre = explode(",",$post['genre']);
    foreach($genre as $item) {
      if($item){
    $field_tags[] = tags_create($item,'','tags');
      }
    }
    
    //channel
    // $field_channel =[];
    // $channel = explode(",",$post['channel']);
    // foreach($channel as $item) {
    // if($item){
    // $field_channel[] = tags_create($item,'','channel');
    // }
    // }
    ////////
//     print "<pre>";
//     print $post['durationSeconds'];
//     print_r($post);
//     print "</pre>";
// exit;
    
      if($post['title'] && $post['subjectType']<=2){
        $node = \Drupal::entityTypeManager()->getStorage('node')->create([
          'type' => 'movie',
          'title' => $post['title'],
          'field_countryname' => $post['countryName'],	
          'field_cover' =>  json_encode($post['cover']),	
          'field_description' => $post['description'],
          'field_detailpath' => $post['detailPath'],	
          'field_duration' => $post['durationSeconds'],
          'field_genre' => $post['genre'],
          'field_genre_term' => $field_tags,
          'field_hasresource' => $post['hasResource'],
          'field_imdbratingvalue' => $post['imdbRatingValue'],
          'field_keywords' => $post['keywords'],
          'field_ops' => $post['ops'],
          'field_releasedate' => strtotime($post['releaseDate']),	
          'field_stafflist' => json_encode($post['staffList']),
          'field_subjectid' => $post['subjectId'],
          'field_subjecttype' => $post['subjectType'],
          'field_subtitles' => $post['subtitles'],
          'field_trailer' => $post['trailer'],
          'field_platform' => $platform,
          'field_month' => $month,
        ]);
        $results[] = $node->save();
        if($node->id() && $block_id){
          block_save($node->id(),$block_id,$i);
        }
      }
    }
    
     }
}

function block_save($nid,$block_id,$i){
  
  
 
  $block = \Drupal\block_content\Entity\BlockContent::load($block_id);
$text = $block->field_movie->getValue();
       
      $array2[]['target_id']= $nid;
      $output = (array_merge($text, $array2));
      // var_export($output);
      // exit;
      $block->field_movie = $output;
        $block->save();
}

function getmoviebox_detail_session($detailpath='',$subjectid='')
{
  
  $curl = curl_init();
  $url = 'https://fmoviesunblocked.net/wefeed-h5-bff/web/subject/detail?subjectId='.$subjectid;
  
 
 curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
//curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:88.0) Gecko/20100101 Firefox/88.0");
 curl_setopt($curl, CURLOPT_HTTPHEADER , array(
    'Referer: https://fmoviesunblocked.net/',
  'Origin: https://fmoviesunblocked.net',
  'Accept: */*',
  'Host: fmoviesunblocked.net',
  'Connection: keep-alive',
  'X-Forwarded-For: http://localhost'
  ));
$str = curl_exec($curl);
curl_close($curl);
//var_export($str);

// var_export($str);
//  exit;
  if($subjectid=='7672308553189055832'){
// print "<pre>";
//  print_r($str);
// exit;
  }
$str_new = json_decode($str);

 $season_id = '';
 if(@$str_new->data->resource->seasons[0]->se) $season_id = $str_new->data->resource->seasons[0]->se;

if($season_id==''){
  return true;
}


// print_r($str_new->data->seasons);
// exit;
$season = [];
foreach($str_new->data->resource->seasons as $key=>$value){
 // print_r($str_new[$value]->se);
  $season[$key]['se']=$value->se;
  $season[$key]['ep']=$value->maxEp;
  $season[$key]['allEp']=$value->allEp;
}
$season = json_encode($season);
// print "<pre>";
//  print_r($str_new);
// print "<pre>";
//  print_r($season);
//   exit;
 
  $movie['field_season'] = $season;


    return $movie;
}

function getmoviebox_detail_session_old($detailpath='',$subjectid='')
{
  
  $curl = curl_init();
 // $url = 'https://api6.aoneroom.com/wefeed-mobile-bff/subject-api/season-info?subjectId='.$subjectid;
 $url = 'https://h5.inmoviebox.com/movies/'.$detailpath.'?id='.$subjectid;
 curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
//curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:88.0) Gecko/20100101 Firefox/88.0");
curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:88.0) Gecko/20100101 Firefox/88.0");
curl_setopt($curl, CURLOPT_HTTPHEADER , array(
  'Referer: https://h5.inmoviebox.com/',
  'Origin: https://h5.inmoviebox.com/',
  'Accept: */*',
  'Host: h5.inmoviebox.com',
  'Connection: keep-alive',
  'X-Forwarded-For: http://localhost'
));
$str = curl_exec($curl);
curl_close($curl);
//var_export($str);

// var_export($str);
//  exit;
$dom = HtmlDomParser::str_get_html($str);
$str_new = $dom->findOne("#__NUXT_DATA__")->text();

$str_new = json_decode($str_new);
// print "<pre>";
//  print_r($str_new);
// exit;
 $season_id = '';
foreach($str_new as $value){
 if(@$value->seasons) $season_id = $value->seasons;
}
if($season_id==''){
  return true;
}

//print_r($str_new[22]);
$season = [];
foreach($str_new[$season_id] as $key=>$value){
 // print_r($str_new[$value]->se);
 //print $str_new[$str_new[$value]->allEp];
  $season[$key]['se']=$str_new[$str_new[$value]->se];
  $season[$key]['ep']=($str_new[$value]->allEp==7)?$str_new[$str_new[$value]->allEp]:$str_new[$str_new[$value]->maxEp];
  $season[$key]['allEp']=$str_new[$str_new[$value]->allEp];
}
 $season = json_encode($season);
// print "<pre>";
//  print_r($str_new);
// print "<pre>";
//  print_r($season);
//   exit;
 
  $movie['field_season'] = $season;


    return $movie;
}



function getmoviebox_detail($detailpath='',$subjectid='')
{
  // var_export($detailpath);
  // exit;
  //  $new_var = theme_get_setting('new_domain_name');
  //     $oldStr = theme_get_setting('old_domain_name');
  //     $oldStr = explode(",", $oldStr);
   
  // $url = str_replace($oldStr, $new_var, $url );
  $curl = curl_init();
  $url = 'https://h5.inmoviebox.com/movies/'.$detailpath.'?id='.$subjectid;
 // $url = 'https://h5.inmoviebox.com/wefeed-h5-bff/web/subject';
 // $url = 'https://h5.inmoviebox.com/movies/raised-by-wolves-eXCpRFL0GQ6?id=5748897688012083992';


 curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($curl, CURLOPT_HEADER, false);
//curl_setopt($curl, CURLOPT_HTTPPROXYTUNNEL, 0);
//curl_setopt($curl, CURLOPT_PROXY, '13.91.243.29:3128');
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($curl, CURLOPT_URL, $url);
// curl_setopt($curl, CURLOPT_POST, 1);
//   curl_setopt($curl, CURLOPT_POSTFIELDS, "subjectId=".$subjectid."");
  
//curl_setopt($curl, CURLOPT_REFERER, 'https://h5.inmoviebox.com/');
curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:88.0) Gecko/20100101 Firefox/88.0");
curl_setopt($curl, CURLOPT_HTTPHEADER , array(
  'Referer: https://h5.inmoviebox.com/',
  'Origin: https://h5.inmoviebox.com/',
  'Accept: */*',
  'Host: h5.inmoviebox.com',
  'Connection: keep-alive',
  'X-Forwarded-For: http://localhost'
));
$str = curl_exec($curl);
curl_close($curl);
//var_export($str);

// var_export($str);
//  exit;
$dom = HtmlDomParser::str_get_html($str);
$str_new = $dom->findOne("#__NUXT_DATA__")->text();

$str_new = json_decode($str_new);
// print "<pre>";
//  print_r($str_new);
//exit;
 $season_id = '';
foreach($str_new as $value){
 if(@$value->seasons) $season_id = $value->seasons;
}
if($season_id==''){
  return true;
}

//print_r($str_new[22]);
$season = [];
foreach($str_new[$season_id] as $key=>$value){
 // print_r($str_new[$value]->se);
  $season[$key]['se']=$str_new[$str_new[$value]->se];
  $season[$key]['ep']=($str_new[$value]->allEp==7)?$str_new[$str_new[$value]->allEp]:$str_new[$str_new[$value]->maxEp];
}
$season = json_encode($season);
// print "<pre>";
//  print_r($str_new);
// print "<pre>";
//  print_r($season);
//   exit;
 
  $movie['field_season'] = $season;
  $movie['field_description'] = $str_new[13];


    return $movie;
}





function tags_create($cat,$path,$vid){
  $cat = trim($cat);
$storage = \Drupal::entityTypeManager()
  ->getStorage('taxonomy_term');
$terms = $storage->loadByProperties([ 
  'name' => $cat,
  'vid' => $vid,
]);

if($terms == NULL) { //Create term and use
$created = _create_term($cat,$vid,$path);
if($created) {
//finding term by name
$storage = \Drupal::entityTypeManager()
  ->getStorage('taxonomy_term');
$newTerm = $storage->loadByProperties([ 
  'name' => $cat,
  'vid' => $vid,
]);
$newTerm = reset($newTerm);
return !empty($newTerm) ? $newTerm->id() : '';
}
}
$terms = reset($terms);
return !empty($terms) ? $terms->id() : '';
}


function _create_term($name,$taxonomy_type,$path) {

  if($path){
$term = Term::create([
'name' => $name,
'vid' => $taxonomy_type,
'path' => [
  'alias' => $path,
  'pathauto' => PathautoState::SKIP,
],
])->save();
  }else{
    $term = Term::create([
      'name' => $name,
      'vid' => $taxonomy_type,
      ])->save(); 
  }
return TRUE;
}
