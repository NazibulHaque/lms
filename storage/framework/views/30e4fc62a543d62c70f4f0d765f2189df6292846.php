<?php
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
?>

<div id="navbarVacuum"></div>
<nav id="navbar" class="pro-nav">
  <div class="<?php echo e((!empty($isPanel) and $isPanel) ? 'container-fluid' : 'container'); ?>">
    <div class="pro-nav__bar">
      
      <a class="pro-nav__brand" href="/">
        <?php if(!empty($generalSettings['logo'])): ?>
          <img src="<?php echo e($generalSettings['logo']); ?>" alt="site logo" class="pro-nav__logo">
        <?php else: ?>
          <span class="pro-nav__brand-text">Brand</span>
        <?php endif; ?>
      </a>

      
      <div class="pro-nav__menu" id="navbarContent">
        <ul class="pro-nav__list">
          
          <?php if(!empty($categories) and count($categories)): ?>
            <li class="pro-nav__item pro-nav__dropdown">
              

              <div class="pro-nav__dropdown-menu" data-menu="categories">
                <ul class="pro-nav__dropdown-list">
                  <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="pro-nav__dropdown-item <?php echo e((!empty($category->subCategories) and count($category->subCategories)) ? 'has-sub' : ''); ?>">
                      <a href="<?php echo e($category->getUrl()); ?>" class="pro-nav__dropdown-link">
                        <span class="pro-nav__dropdown-label">
                          <?php if(!empty($category->icon)): ?>
                            <img src="<?php echo e($category->icon); ?>" class="pro-nav__dropdown-icon" alt="<?php echo e($category->title); ?> icon">
                          <?php endif; ?>
                          <?php echo e($category->title); ?>

                        </span>
                        <?php if(!empty($category->subCategories) and count($category->subCategories)): ?>
                          <span class="pro-nav__chev">›</span>
                        <?php endif; ?>
                      </a>

                      <?php if(!empty($category->subCategories) and count($category->subCategories)): ?>
                        <ul class="pro-nav__sub">
                          <?php $__currentLoopData = $category->subCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subCategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li>
                              <a href="<?php echo e($subCategory->getUrl()); ?>" class="pro-nav__sub-link">
                                <?php if(!empty($subCategory->icon)): ?>
                                  <img src="<?php echo e($subCategory->icon); ?>" class="pro-nav__dropdown-icon" alt="<?php echo e($subCategory->title); ?> icon">
                                <?php endif; ?>
                                <?php echo e($subCategory->title); ?>

                              </a>
                            </li>
                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                      <?php endif; ?>
                    </li>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
              </div>
            </li>
          <?php endif; ?>

          
          <?php if(!empty($navbarPages) and count($navbarPages)): ?>
            <?php $__currentLoopData = $navbarPages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $navbarPage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <li class="pro-nav__item">
                <a class="pro-nav__link" href="<?php echo e($navbarPage['link']); ?>"><?php echo e($navbarPage['title']); ?></a>
              </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          <?php endif; ?>
        </ul>
      </div>

      
      <div class="pro-nav__actions">
        <button class="pro-nav__icon" type="button" aria-label="Search">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        </button>

        <?php if(!empty($navBtnUrl)): ?>
          <a href="<?php echo e($navBtnUrl); ?>" class="pro-nav__cta"><?php echo e($navBtnText); ?></a>
        <?php endif; ?>

        <?php if(!empty($isPanel) && $authUser->checkAccessToAIContentFeature()): ?>
          <a class="pro-nav__ai d-none d-lg-flex js-show-ai-content-drawer">
            <span class="pro-nav__ai-dot"></span>
            <span class="pro-nav__ai-text"><?php echo e(trans('update.ai_content')); ?></span>
          </a>
        <?php endif; ?>

        <button class="pro-nav__hamburger" type="button" aria-label="Open menu" id="navbarToggle">
          <span></span><span></span><span></span>
        </button>
      </div>
    </div>
  </div>
</nav>


<?php $__env->startPush('scripts_bottom'); ?>
    <script src="/assets/default/js/parts/navbar.min.js"></script>
<?php $__env->stopPush(); ?>
<?php /**PATH D:\Matjar-online\lms\resources\views/web/default/includes/navbar.blade.php ENDPATH**/ ?>