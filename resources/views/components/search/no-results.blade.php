 @if (empty($query) ||
         (count($results['products']) == 0 &&
             count($results['users']) == 0 &&
             count($results['categories']) == 0 &&
             count($results['branches']) == 0))
     <p class="no-results-text">Aucun résultat trouvé.</p>
 @endif
 {{ $slot }}
