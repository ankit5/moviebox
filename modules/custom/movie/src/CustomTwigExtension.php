<?php

namespace Drupal\movie;
use Drupal\views\Views;
use Drupal\Component\Utility\Html;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;


/**
 * Class AbstractExtension.
 *
 */
class CustomTwigExtension extends AbstractExtension {

  /**
   * Let Drupal know the name of your extension,Must be unique name, string.
   */
  public function getName() {
    return 'movie.customtwigextension';
  }

  /**
   * Get Order Detail .
   */

   public function orderDetail($order_id,$fieldname) {
 //return $order_id;
   
    $data = OrderController::orderDetail($order_id,1);

    return $data->$fieldname;
  }


   /**
   * Get absolute image url.
   */
  public function fileUri($url) {

    return str_replace("/sites/default/files/", "public://", $url);
  }

  /**
   * Return your custom twig function to Drupal.
   */
  public function getFunctions() {
    return [
      new TwigFunction('related', [$this, 'related']),
      new TwigFunction('custom_block', [$this, 'custom_block']),
      new TwigFunction('token_replace', [$this, 'token_replace']),
      new TwigFunction('twig_json_decode', [$this, 'twigJsonDecode']),
      new TwigFunction(
        'twig_json_decode_table',
         [$this, 'twigJsonDecodeTable'],
        ['is_safe' => ['html']]),
      new TwigFunction(
        'twig_json_decode_accessories',
         [$this, 'twigJsonDecodeAccessories'],
        ['is_safe' => ['html']]),
      new TwigFunction('file_uri', [$this, 'fileUri']),
      new TwigFunction('order_detail', [$this, 'orderDetail']),
      new TwigFunction('entity_load_uuid', [$this, 'entityLoadUuid']),
      new TwigFunction('city_value', [$this, 'cityValue']),
      new TwigFunction('node_load', [$this, 'node_load']),
      new TwigFunction('parse_url_remove', [$this, 'parse_url_remove']),
      new TwigFunction('slug', [$this, 'slug']),
      new TwigFunction('term_json', [$this, 'term_json']),
    ];
  }
  public function slug($string){
    
$slug = Html::getClass($string);
return $slug;
  }
  public function term_json($string,$string2){
    
    $arr = explode(",",$string);
    $arr2 = explode(",",$string2);
    $json = array_combine($arr,$arr2);
    
    return json_encode($json);
      }
  public function custom_block($id)
  {
  $custom_block = \Drupal::entityTypeManager()->getStorage('block_content')->load($id);
  $node = \Drupal::routeMatch()->getParameter('node');
  $token = \Drupal::token();
if ($node instanceof \Drupal\node\NodeInterface) {
  $token_data = array(  //assigns the current node data to the 'node' key in the $data array; the node key is recognized by the Token service
    'node' => $node,
  );
  $token_options = ['clear' => TRUE];  //part of the Token replacement service; A boolean flag indicating that tokens should be removed from the final text if no replacement value can be generated
  return $token->replace($custom_block->body->value, $token_data, $token_options);
  
}
 
return $token->replace($custom_block->body->value);
  }
/////////////////////////////////////////////////////////////////  
  public function token_replace($text)
  {
 // $custom_block = \Drupal::entityTypeManager()->getStorage('block_content')->load($id);
  $node = \Drupal::routeMatch()->getParameter('node');
  $token = \Drupal::token();
if ($node instanceof \Drupal\node\NodeInterface) {
  $token_data = array(  //assigns the current node data to the 'node' key in the $data array; the node key is recognized by the Token service
    'node' => $node,
  );
  $token_options = ['clear' => TRUE];  //part of the Token replacement service; A boolean flag indicating that tokens should be removed from the final text if no replacement value can be generated
  return $token->replace($text, $token_data, $token_options);
  
}
if (\Drupal::routeMatch()->getRouteName() == 'entity.taxonomy_term.canonical') {
  $term_id = \Drupal::routeMatch()->getRawParameter('taxonomy_term');
  $term = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($term_id);
  $token_data = array(  //assigns the current node data to the 'node' key in the $data array; the node key is recognized by the Token service
    'term' => $term,
  );
  $token_options = ['clear' => TRUE];  //part of the Token replacement service; A boolean flag indicating that tokens should be removed from the final text if no replacement value can be generated
  return $token->replace($text, $token_data, $token_options);

}
 ///////////////////////////////////////////////////////////////////
return $token->replace($text);
  }

 public function related($title='',$quality='',$id='',$tag='')
{
   $view = Views::getView('related'); 
$view->setDisplay('block_1');
$title = @explode(' ', trim($title))[0]." ".explode(' ', trim($title))[1];
$view->setExposedInput([
  'title' => $title,
 // 'field_quality_value' => $quality,
  'nid' => $id,
  'field_tags_target_id' => $tag
]);
$view->execute();
@$rendered = \Drupal::service('renderer')->render($view->render());

return $rendered;
}
  /**
 * Get City name by value function.
 */
public function cityValue($title) {

 // Run query on external database.
  $m_type = 'city';
   $database = \Drupal::database();
$results = $database->query("SELECT node__field_message.field_message_value AS node__field_message_field_message_value, node_field_data.title AS node_field_data_title, node_field_data.nid AS nid
FROM
{node_field_data} node_field_data
LEFT JOIN {node__field_message_type} node__field_message_type_value_0 ON node_field_data.nid = node__field_message_type_value_0.entity_id AND node__field_message_type_value_0.field_message_type_value = '$m_type'
LEFT JOIN {node__field_message} node__field_message ON node_field_data.nid = node__field_message.entity_id AND node__field_message.deleted = '0'
WHERE (node_field_data.status = '1') AND (node_field_data.type IN ('messages')) AND (node_field_data.title ILIKE '$title') AND (node__field_message_type_value_0.field_message_type_value = '$m_type')
ORDER BY node__field_message_field_message_value ASC NULLS FIRST
LIMIT 1 OFFSET 0")->fetchObject();
   if (isset($results->nid)) {
      return $results->node__field_message_field_message_value;
    }
    return '';
}

  /**
   * Return your custom twig filter to Drupal.
   */
  public function getFilters() {
    return [
      new TwigFilter(
        'twig_json_decodenew',
         [$this, 'twigJsonDecodenew']),
    ];
  }

  /**
   * Returns $_GET query parameter.
   *
   * @param string $name
   *   Name of the query parameter.
   *
   * @return string
   *   Value of the query parameter name
   */
  public function getUrlParam($name) {

    return \Drupal::request()->query->get($name);
  }

  /**
   * Entity load by uuid.
   */
  public function entityLoadUuid($uuid, $fieldname = '') {

     $database = \Drupal::database();
$results = $database->query("SELECT  *
FROM
{node_field_data} node_field_data
INNER JOIN {node} node ON node_field_data.nid = node.nid
WHERE (node.uuid ILIKE '$uuid')
ORDER BY node_field_data.created DESC NULLS LAST
LIMIT 1 OFFSET 0")->fetchObject();
   if (isset($results->$fieldname)) {
      return $results->$fieldname;
    }
    return '';
  }

  /**
   * Entity load by nid.
   */
  public function node_load($nid) {

     $node = \Drupal\node\Entity\Node::load($myLastElement['target_id']);

    return $node;
  }

public function parse_url_remove($url){
  if(!$url)return '';
  $url_arr = parse_url($url);

$query = $url_arr['query'];
$url = str_replace(array($query,'?'), '', $url);
return $url;
}



  /**
   * Return data in json decode.
   */
  public function twigJsonDecode($json, $key = '', $array = '') {
  /* $json = '"embed_url": "<iframe src=https://cdnplay.pro/watch?v=1QFC7EIS frameborder=0 scrolling=no webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>",
    "type": "dtshcode"';*/
   $json = html_entity_decode(($json));
//return $json;

    
    $json = json_decode($json,TRUE);
   // return $json['embed_url'];
   /* print "asdw";
    print_r($json);
    exit();*/
   // return $json;
    if ($array != '') {
      return $json[$key][$array];
    }
    if (isset($json[$key])) {
      return $json[$key];
    }
    if($array == '' && $key=='') {
      return $json;
    }
    else {
      return '';
    }
  }

  /**
   * Return data in html.
   */
  public function twigJsonDecodeAccessories($json, $key = '', $array = '') {
    $json = html_entity_decode(($json));
    $json = json_decode($json, TRUE);

    $html = '';

    if (isset($json[0]['name'])) {
      $html = '<table class="table">
    <thead>
      <tr>
        <th>Name</th>
        <th>Price</th>
        <th>Quantity</th>
        <th>Image</th>
      </tr>
    </thead>
    <tbody>';

      foreach ($json as $key => $value) {

        $html .= '<tr>
        <td>' . $value['name'] . '</td>
        <td>' . $value['price'] . '</td>
        <td>' . $value['quantity'] . '</td>
        <td><img width="40" src="' . $value['image_url'][0] . '" /></td>
      </tr>';

      }
      $html .= '</tbody>
  </table>';
    }
    else {
      $html = 'NO Accessories';
    }
    return $html;

  }

  /**
   * Return json data in table.
   */
  public function twigJsonDecodeTable($json) {

    $json = html_entity_decode(($json));
    $json = json_decode($json, TRUE);
    $html = '';
    foreach ($json as $key => $value) {
      $html .= '<div class="jsonkeyvalue"><div class="jsonkey"> ' . $key . ' </div><div class="jsonvalue"> ' . $value . ' </div></div>';

    }
    return $html;
  }

  /**
   * Replaces available values to entered tokens.
   *
   * @param string $text
   *   replaceable tokens with/without entered HTML text.
   *
   * @return string
   *   replaced token values with/without entered HTML text
   */
  public function replaceTokens($text) {
    return \Drupal::token()->replace($text);
  }

  /**
   * Return json data in json decode.
   */
  public function twigJsonDecodenew($json) {
   // return 'ok';
  
    return json_decode($json, TRUE);
  }

}
