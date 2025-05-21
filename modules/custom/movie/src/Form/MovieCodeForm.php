<?php

namespace Drupal\movie\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class ReplaceLanguageCodeForm.
 *
 * @package Drupal\movie\Form
 */
class MovieCodeForm extends FormBase {


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'movie_code_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['actions'] = [
      '#type' => 'actions',
    ];
    
    $form['pageno'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Page Number'),
      '#maxlength' => 20,
      '#default_value' =>  '1',
      '#description' =>'(pageno = 1 : 1*24)Movie load , if platform field fill (pageno = 1 : 1*12) Movie load'
    ];

    $form['pageno_offset'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Page Number Offset'),
      '#maxlength' => 20,
      '#default_value' =>  '0',
    ];
    $form['api'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Api Url'),
      '#maxlength' => 100,
      '#default_value' =>  'https://h5.inmoviebox.com/wefeed-h5-bff/web/filter',
    ];
    $form['post'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Post Data'),
      '#maxlength' => 100,
      '#default_value' =>  '&channelId=1&perPage=24&sort=Latest',
    ];
    $form['channel_id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Movie type'),
      '#maxlength' => 20,
      '#default_value' =>  '1',
    ];

    $form['platform'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Plat form'),
      '#maxlength' => 20,
      '#default_value' =>  '',
      '#attributes' => array('placeholder' => t('Netflix'),)
    ];

    $form['month'] = [
      '#type' => 'textfield',
      '#title' => $this->t('month'),
      '#maxlength' => 20,
      '#default_value' =>  '',
      '#attributes' => array('placeholder' => t('202502'),)
    ];

    $form['rankingid'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Tranding Type Id'),
      '#maxlength' => 20,
      '#default_value' =>  '',
    ];
    $form['blockid'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Block Id'),
      '#maxlength' => 20,
      '#default_value' =>  '',
    ];

    $form['actions']['submit1'] = [
      '#type' => 'submit',
      '#value' => $this->t('Get Movies List'),
      "#weight" => 1,
      '#submit' => array([$this, 'submitFormOne'])
    ];

    $form['actions']['submit_ranking'] = [
      '#type' => 'submit',
      '#value' => $this->t('Tranding Type List Load'),
      "#weight" => 1,
      '#submit' => array([$this, 'submitFormRanking'])
    ];

    // $form['actions']['submit2'] = [
    //   '#type' => 'submit',
    //   '#value' => $this->t('Load Tv Movies List'),
    //   "#weight" => 2,
    //   '#button_type' => 'primary',
    //   '#submit' => array([$this, 'submitFormTwo'])
    // ];
    // $form['movieno'] = [
    //   '#type' => 'textfield',
    //   '#title' => $this->t('Load Movie Numbers'),
    //   '#maxlength' => 20,
    //   '#default_value' =>  '',
    // ];
    // $form['actions']['submit3'] = [
    //   '#type' => 'submit',
    //   '#value' => $this->t('Save Movies Image'),
    //   "#weight" => 2,
    //   '#button_type' => 'primary',
    //   '#submit' => array([$this, 'submitFormThree'])
    // ];
    // $form['imageno'] = [
    //   '#type' => 'textfield',
    //   '#title' => $this->t('Save Movie Numbers'),
    //   '#maxlength' => 20,
    //   '#default_value' =>  '',
    // ];
    

    return $form;
  }
  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) { 

  }
/**
   * {@inheritdoc}
   */
  public function submitFormRanking(array &$form, FormStateInterface $form_state) { 

    $batch = [
      'title' => t('Replacing Language Code...'),
      'operations' => [],
      'finished' => '\Drupal\movie\ReplaceLanguageCode::replaceLangcodeFinishedCallback',
    ];
    $field = $form_state->getValues();
 
    $i = ($field['pageno_offset']>0)?$field['pageno_offset']+1:1;
    $total = $field['pageno'];
    // print $i."=".$total;
    // exit;
   
 while ($i <= $total) {
  $params = [$i,'','', $field['rankingid'], $field['blockid'],''];
    $batch['operations'][] = ['\Drupal\movie\ReplaceLanguageCode::getmoviebox', $params];
 
  // echo $i;
   $i++;
   
 }
 batch_set($batch);
 
   
}

/**
   * {@inheritdoc}
   */
  public function submitFormOne(array &$form, FormStateInterface $form_state) {
   // print_r('1');
  
    $batch = [
      'title' => t('Replacing Language Code...'),
      'operations' => [],
      'finished' => '\Drupal\movie\ReplaceLanguageCode::replaceLangcodeFinishedCallback',
    ];
    $field = $form_state->getValues();
    // print $field['channel_id'];
    // exit;
    $i = ($field['pageno_offset']>0)?$field['pageno_offset']+1:1;
    $total = $field['pageno'];
    // print $i."=".$total;
    // exit;
   
 while ($i <= $total) {
  $params = [$i, $field['platform'], $field['month'],'','', $field['channel_id'],$field['api'],$field['post']];
    $batch['operations'][] = ['\Drupal\movie\ReplaceLanguageCode::getmoviebox', $params];
 
  // echo $i;
   $i++;
   
 }
 batch_set($batch);
  }
  /**
   * {@inheritdoc}
   */
  public function submitFormTwo(array &$form, FormStateInterface $form_state) {
    //print_r('2');
    $field = $form_state->getValues();
    $query = \Drupal::entityTypeManager()->getStorage('node')->getQuery();
    $query->accessCheck(TRUE);
   $query->condition('type', 'movie', '=');
   $query->condition('field_subjecttype', '2', '=');
  $query->sort('field_releasedate', 'DESC');
  //$count = $query->count()->execute();
  // print $count;
  // exit; 
  $i = ($field['pageno_offset']>0)?$field['pageno_offset']:0;
  $nids = $query->range($i,$field['movieno'])->execute();
// print_r($nids);
//   exit; 
  $batch = [
    'title' => t('Replacing Language Code...'),
    'operations' => [],
    'finished' => '\Drupal\movie\ReplaceLanguageCode::replaceLangcodeFinishedCallback',
  ];

   foreach($nids as $nid) {
     $batch['operations'][] = ['\Drupal\movie\ReplaceLanguageCode::replaceLangcode', [$nid]];
   }

  batch_set($batch);
  }
  /**
   * {@inheritdoc}
   */
  public function submitFormThree(array &$form, FormStateInterface $form_state) {
    //print_r('2');
    $field = $form_state->getValues();
    $query = \Drupal::entityTypeManager()->getStorage('node')->getQuery();
   $query->condition('type', 'movie', '=');
  $query->sort('created', 'DESC');
  $nids = $query->range(0,$field['imageno'])->execute();

  $batch = [
    'title' => t('Replacing Language Code...'),
    'operations' => [],
    'finished' => '\Drupal\movie\ReplaceLanguageCode::replaceLangcodeFinishedCallback',
  ];

   foreach($nids as $nid) {
     $batch['operations'][] = ['\Drupal\movie\ReplaceLanguageCode::replaceLangcode2', [$nid]];
   }

  batch_set($batch);
  }
  /**
   * {@inheritdoc}
   */
  public function submitFormTwo_old(array &$form, FormStateInterface $form_state) {
    print_r('2');
    exit;
    // $url = 'https://www.watch-movies.com.pk/sarfira-2024-hindi-full-movie-watch-online-hd-print-free-download/';
    // $curl = curl_init();
    // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    // curl_setopt($curl, CURLOPT_HEADER, false);
    // curl_setopt($curl, CURLOPT_HTTPPROXYTUNNEL, 0);
    // //curl_setopt($curl, CURLOPT_PROXY, '13.91.243.29:3128');
    // curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    // curl_setopt($curl, CURLOPT_URL, $url);
    // curl_setopt($curl, CURLOPT_REFERER, 'https://www.watch-movies.com.pk/');
    // curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    // curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:88.0) Gecko/20100101 Firefox/88.0");
    // $str = curl_exec($curl);
    // curl_close($curl);
    // print $str;
    // exit;
  /* $query = \Drupal::entityTypeManager()->getStorage('node')->getQuery();
   $query->condition('type', 'movie', '=');
  $query->notExists('field_year');*/
 
  $terms =\Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree('genre');
 


// $query = \Drupal::entityTypeManager()->getStorage('node')->getQuery();
//    $query->condition('type', 'movie', '=');
//   //  $query->condition('field_url', '%/series%', 'LIKE');
//   //  $query->notExists('field_year');
//   $query->sort('created', 'DESC');
//  //  $nids = $query->range(16518,1000)->execute();
//   $nids = $query->range(16480,1000)->execute();

   $batch = [
     'title' => t('Replacing Language Code...'),
     'operations' => [],
     'finished' => '\Drupal\movie\ReplaceLanguageCode::replaceLangcodeFinishedCallback',
   ];

//    $i = 174;
// while ($i > 0) {
//    $batch['operations'][] = ['\Drupal\movie\ReplaceLanguageCode::getmovie2', [$i]];

//  // echo $i;
//   $i--;
  
// }

$i=1;
foreach ($terms as $term) {
  if($i>38){
  $batch['operations'][] = ['\Drupal\movie\ReplaceLanguageCode::termcode', [$term->tid]];
  }
  $i++;
 }  

  //  foreach($nids as $nid) {
  //    $batch['operations'][] = ['\Drupal\movie\ReplaceLanguageCode::replaceLangcode2', [$nid]];
  //  }

   batch_set($batch);
  }

}