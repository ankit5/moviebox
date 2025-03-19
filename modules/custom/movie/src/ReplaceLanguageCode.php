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
    if($node->field_season->value=='')
    {
      $load =1;
    } 
    if($load==''){ return true; }
//  print $node->field_url->value;
// var_export($node->field_subjectid->value);
//  exit;
   $message2 = getmoviebox_detail($node->field_detailpath->value,$node->field_subjectid->value);
 
/*print_r($message2['field_trailer']);
 
   exit;*/
  
    $results = array();

   //////////////////////////////////////////////
  
   if ($message2['field_season']) {
    $node->field_season->value = $message2['field_season'];
    }
    
   
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

 public static function getmoviebox($i,$platform,$month,$ranking_id,$block_id, &$context)
{
 
  //https://h5.inmoviebox.com/wefeed-h5-bff/web/subject/play?subjectId=7415754612038583632&se=1&ep=1
 $message = 'Replacing langcode(und to de)...';
    $results = array();
  // 
  if($platform!=''){
    $data = curlgetmoviebox_platform($i,$platform,$month);
    $items = $data['data']['subjects'];
  
      save_movie_box($items,$platform,$month); 
     
      
       }elseif($ranking_id && $block_id){
        $data = curlgetmoviebox_ranking($i,$ranking_id);
        $items = $data['data']['subjectList'];
        // var_export($items);
        //   exit;
        save_movie_box($items,'','',$block_id);
           }else{
    $data = curlgetmoviebox($i);
    $items = $data['data']['items'];
    // var_export($data['data']);
    //   exit;
    save_movie_box($items);
       }
}
}

function curlgetmoviebox($i){
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
  curl_setopt($curl, CURLOPT_HEADER, false);
  curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
  
  curl_setopt($curl, CURLOPT_URL, 'https://h5.inmoviebox.com/wefeed-h5-bff/web/filter');
  //curl_setopt($curl, CURLOPT_URL, 'https://h5.inmoviebox.com/wefeed-h5-bff/web/class-month');
  
  curl_setopt($curl, CURLOPT_POST, 1);
  curl_setopt($curl, CURLOPT_POSTFIELDS, "channelId=1&page=".$i."&perPage=24&sort=Latest&country=Saudi Arabia");
  //curl_setopt($curl, CURLOPT_POSTFIELDS, "page=".$i."&perPage=24&platform=Netflix");
  curl_setopt($curl, CURLOPT_REFERER, 'https://h5.inmoviebox.com/');
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:88.0) Gecko/20100101 Firefox/88.0");
  $str = curl_exec($curl);
  curl_close($curl);
  //print $str;
  //exit;
  //var_dump(json_decode($str, true)); exit;
  
   $data = json_decode($str,true);
   return $data;
   
}

function curlgetmoviebox_ranking($i,$ranking_id){
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
  curl_setopt($curl, CURLOPT_HEADER, false);
  curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
  
  
  curl_setopt($curl, CURLOPT_URL, 'https://h5.inmoviebox.com/wefeed-h5-bff/web/ranking-list/content');
  
  curl_setopt($curl, CURLOPT_POST, 1);
  curl_setopt($curl, CURLOPT_POSTFIELDS, "page=".$i."&perPage=0&id=".$ranking_id);
  curl_setopt($curl, CURLOPT_REFERER, 'https://h5.inmoviebox.com/');
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:88.0) Gecko/20100101 Firefox/88.0");
  $str = curl_exec($curl);
  curl_close($curl);
  //print $str;
  //exit;
  //var_dump(json_decode($str, true)); exit;
  
   $data = json_decode($str,true);
   return $data;
   
}

function curlgetmoviebox_platform($i,$platform,$month){
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
  curl_setopt($curl, CURLOPT_HEADER, false);
  curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
  
  
  curl_setopt($curl, CURLOPT_URL, 'https://h5.inmoviebox.com/wefeed-h5-bff/web/class-month');
  
  curl_setopt($curl, CURLOPT_POST, 1);
  curl_setopt($curl, CURLOPT_POSTFIELDS, "page=".$i."&perPage=12&platform=".$platform."&month=".$month);
  curl_setopt($curl, CURLOPT_REFERER, 'https://h5.inmoviebox.com/');
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:88.0) Gecko/20100101 Firefox/88.0");
  $str = curl_exec($curl);
  curl_close($curl);
  //print $str;
  //exit;
  //var_dump(json_decode($str, true)); exit;
  
   $data = json_decode($str,true);
   return $data;
   
}

function save_movie_box($items,$platform='',$month='',$block_id=''){
  foreach($items as $post) {
    //   var_export($post['cover']);
    //  exit;
    
    $query = \Drupal::database()->select('node__field_subjectid', 't');
    $query->fields('t', ['entity_id']);
    $query->condition('field_subjectid_value', $post['subjectId']);
    $result = $query->countQuery()->execute()->fetchField();
    $nid = $query->execute()->fetchField();
    // var_export($result);
    // exit;
    if($nid && $platform){
      $node = \Drupal::entityTypeManager()->getStorage('node')->load($nid);
      $node->field_platform->value = $platform;
      $node->field_month->value = $month;
      $node->field_description->value = $post['description'];
       $results[] = $node->save();
    }elseif($nid && $block_id){
     
//$alt_title = Drupal\block\Entity\Block::load('1')->field_tanking_list_id->value;

       block_save($nid,$block_id);
       

    }elseif($result<1){
    
    
    /////tags
    $field_tags =[];
    $genre = explode(",",$post['genre']);
    foreach($genre as $item) {
      if($item){
    $field_tags[] = tags_create($item,'','tags');
      }
    }
    
    //channel
    $field_channel =[];
    $channel = explode(",",$post['channel']);
    foreach($channel as $item) {
    if($item){
    $field_channel[] = tags_create($item,'','channel');
    }
    }
    ////////
    
      if($post['title']!=''){
        $node = \Drupal::entityTypeManager()->getStorage('node')->create([
          'type' => 'movie',
          'title' => $post['title'],
          'field_channel' => $post['channel'],
          'field_channel_term' => $field_channel,
          'field_countryname' => $post['countryName'],	
          'field_cover' =>  json_encode($post['cover']),	
          'field_description' => $post['description'],
          'field_detailpath' => $post['detailPath'],	
          'field_duration' => $post['duration'],
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
          block_save($node->id(),$block_id);
        }
      }
    }
    
     }
}

function block_save($nid,$block_id){
  $block = \Drupal\block_content\Entity\BlockContent::load($block_id);
$text = $block->field_movie->getValue();
       
      $array2[]['target_id']= $nid;
      $output = array_merge($text, $array2);
      // var_export($output);
      // exit;
      $block->field_movie = $output;
       $results[] = $block->save();
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
 // $url = 'https://h5.inmoviebox.com/movies/raised-by-wolves-eXCpRFL0GQ6?id=5748897688012083992';
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($curl, CURLOPT_HEADER, false);
//curl_setopt($curl, CURLOPT_HTTPPROXYTUNNEL, 0);
//curl_setopt($curl, CURLOPT_PROXY, '13.91.243.29:3128');
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_REFERER, 'https://h5.inmoviebox.com/');
curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:88.0) Gecko/20100101 Firefox/88.0");
$str = curl_exec($curl);
curl_close($curl);
// var_export($str);
// exit;
$str = substr($str, strpos($str, '"se":') + 5);
$str = substr($str, 0, strpos($str, ',['));

$str = explode("},",$str);
// var_export($str);
//  exit;
 $str_new = [];
foreach($str as $key=> $value){
  if($key>0){
    $value2 = $value;
   // print_r($value2);
    $str_new[$key] = strstr($value2, ',{"', true);
    if(!$str_new[$key]){
      $str_new[$key] = substr($value2, 0, strpos($value2, ',"'));
    }
 // $str_new[] = substr($value2, 0, strpos($value2, ',"'));
 $value2 = '';
  }
}
$str_new = json_encode($str_new);
// var_export($str_new);
//   exit;
 
  $movie['field_season'] = $str_new;


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
