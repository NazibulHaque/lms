@php
    if (empty($authUser) and auth()->check()) {
        $authUser = auth()->user();
    }

    $navBtnUrl = null;
    $navBtnText = null;

    if(request()->is('forums*')) {
        $navBtnUrl = '/forums/create-topic';
        $navBtnText = trans('update.create_new_topic');
    } else {
        $navbarButton = getNavbarButton(!empty($authUser) ? $authUser->role_id : null, empty($authUser));

        if (!empty($navbarButton)) {
            $navBtnUrl = $navbarButton->url;
            $navBtnText = $navbarButton->title;
        }
    }
@endphp

<div id="navbarVacuum"></div>
<nav id="navbar" class="pro-nav">
  <div class="{{ (!empty($isPanel) and $isPanel) ? 'container-fluid' : 'container'}}">
    <div class="pro-nav__bar">
      {{-- Brand / Logo --}}
      <a class="pro-nav__brand" href="/">
        @if(!empty($generalSettings['logo']))
          <img src="{{ $generalSettings['logo'] }}" alt="site logo" class="pro-nav__logo">
        @else
          <span class="pro-nav__brand-text">Brand</span>
        @endif
      </a>

      {{-- Desktop menu --}}
      <div class="pro-nav__menu" id="navbarContent">
        <ul class="pro-nav__list">
          {{-- Categories (with dropdown) --}}
          @if(!empty($categories) and count($categories))
            <li class="pro-nav__item pro-nav__dropdown">
              {{-- <button class="pro-nav__link pro-nav__dropdown-toggle" type="button" data-dropdown="categories">
                <svg width="18" height="18" viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                {{ trans('categories.categories') }}
              </button> --}}

              <div class="pro-nav__dropdown-menu" data-menu="categories">
                <ul class="pro-nav__dropdown-list">
                  @foreach($categories as $category)
                    <li class="pro-nav__dropdown-item {{ (!empty($category->subCategories) and count($category->subCategories)) ? 'has-sub' : '' }}">
                      <a href="{{ $category->getUrl() }}" class="pro-nav__dropdown-link">
                        <span class="pro-nav__dropdown-label">
                          @if(!empty($category->icon))
                            <img src="{{ $category->icon }}" class="pro-nav__dropdown-icon" alt="{{ $category->title }} icon">
                          @endif
                          {{ $category->title }}
                        </span>
                        @if(!empty($category->subCategories) and count($category->subCategories))
                          <span class="pro-nav__chev">›</span>
                        @endif
                      </a>

                      @if(!empty($category->subCategories) and count($category->subCategories))
                        <ul class="pro-nav__sub">
                          @foreach($category->subCategories as $subCategory)
                            <li>
                              <a href="{{ $subCategory->getUrl() }}" class="pro-nav__sub-link">
                                @if(!empty($subCategory->icon))
                                  <img src="{{ $subCategory->icon }}" class="pro-nav__dropdown-icon" alt="{{ $subCategory->title }} icon">
                                @endif
                                {{ $subCategory->title }}
                              </a>
                            </li>
                          @endforeach
                        </ul>
                      @endif
                    </li>
                  @endforeach
                </ul>
              </div>
            </li>
          @endif

          {{-- Static/managed pages --}}
          @if(!empty($navbarPages) and count($navbarPages))
            @foreach($navbarPages as $navbarPage)
              <li class="pro-nav__item">
                <a class="pro-nav__link" href="{{ $navbarPage['link'] }}">{{ $navbarPage['title'] }}</a>
              </li>
            @endforeach
          @endif
        </ul>
      </div>

      {{-- Right actions --}}
      <div class="pro-nav__actions">
        <button class="pro-nav__icon" type="button" aria-label="Search">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        </button>

        @if(!empty($navBtnUrl))
          <a href="{{ $navBtnUrl }}" class="pro-nav__cta">{{ $navBtnText }}</a>
        @endif

        @if(!empty($isPanel) && $authUser->checkAccessToAIContentFeature())
          <a class="pro-nav__ai d-none d-lg-flex js-show-ai-content-drawer">
            <span class="pro-nav__ai-dot"></span>
            <span class="pro-nav__ai-text">{{ trans('update.ai_content') }}</span>
          </a>
        @endif

        <button class="pro-nav__hamburger" type="button" aria-label="Open menu" id="navbarToggle">
          <span></span><span></span><span></span>
        </button>
      </div>
    </div>
  </div>
</nav>


@push('scripts_bottom')
    <script src="/assets/default/js/parts/navbar.min.js"></script>
@endpush
