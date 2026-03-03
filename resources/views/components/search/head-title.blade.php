  <div class="head-title">
      <div class="left">
          <h1>Résultats de recherche</h1>
          <ul class="breadcrumb">
              <li>
                  <a href="#">Recherche</a>
              </li>
              <li><i class='bx bx-chevron-right'></i></li>
              <li>
                  <a class="active" href="#">Résultats</a>
              </li>
          </ul>
      </div>
  </div>

  @if ($query)
      <p class="search-query-text" style="margin-bottom: 20px;">Résultats pour
          :<strong>"{{ $query }}"</strong></p>
  @endif
  {{ $slot }}
