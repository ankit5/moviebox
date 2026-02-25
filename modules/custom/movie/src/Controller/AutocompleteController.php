<?php
namespace Drupal\movie\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Defines a route controller for entity autocomplete form elements.
 */
class AutocompleteController extends ControllerBase {

  /**
   * Handler for autocomplete request.
   */
  public function handleAutocomplete(Request $request, $postype) {
    $results = [];

    // Get the typed string from the URL, if it exists.
    if ($input = $request->query->get('q') && $postype=='api') {
      // @todo: Apply logic for generating results based on typed_string and other
      // arguments passed.
      $results[0] = [
          'value' => 'https://h5-api.aoneroom.com/wefeed-h5api-bff/subject/filter',
          'label' => 'https://h5-api.aoneroom.com/wefeed-h5api-bff/subject/filter',
        ];
         $results[1] = [
          'value' => 'https://h5-api.aoneroom.com/wefeed-h5api-bff/subject/search',
          'label' => 'https://h5-api.aoneroom.com/wefeed-h5api-bff/subject/search',
        ];
    }
     if ($input = $request->query->get('q') && $postype=='api_data') {
      // @todo: Apply logic for generating results based on typed_string and other
      // arguments passed.
      $results[0] = [
          'value' => '&channelId=1&perPage=28&sort=Latest',
          'label' => '&channelId=1&perPage=28&sort=Latest',
        ];
         $results[1] = [
          'value' => '&channelId=1&country=India&perPage=28&sort=Latest',
          'label' => '&channelId=1&country=India&perPage=28&sort=Latest',
        ];
        $results[2] = [
          'value' => '&channelId=1&classify=Hindi dub&perPage=28&sort=Latest',
          'label' => '&channelId=1&classify=Hindi dub&perPage=28&sort=Latest',
        ];
        $results[3] = [
          'value' => '&keyword=You&perPage=28',
          'label' => '&keyword=You&perPage=28',
        ];
    }

    return new JsonResponse($results);
  }

}
