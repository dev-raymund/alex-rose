<?php
/**
 * Template Name: Design Your Jacket
 * Description: Custom page template for designing custom jackets
 * Template Post Type: page
 */

get_header(); ?>

<div class="flex flex-col min-h-screen">
  <!-- Header -->
  <header class="fixed top-0 left-0 right-0 z-50" style="background-color: rgb(17, 17, 17); height: 68px; border-bottom: 1px solid rgba(255, 255, 255, 0.07);">
    <div class="h-full px-8 md:px-14 flex items-center gap-8 py-2">
      <a style="margin-top: 6px;" href="<?php echo home_url(); ?>" class="shrink-0 mr-auto">
        <img alt="<?php bloginfo('name'); ?>" class="h-12 w-auto object-contain" src="<?php echo get_theme_file_uri('assets/images/logo.png'); ?>">
      </a>
      <nav class="hidden md:flex items-center gap-7">
        <a href="<?php echo home_url('/how-it-works'); ?>" class="text-[11px] uppercase tracking-luxury text-white/65 hover:text-white transition-colors duration-200 whitespace-nowrap">How It Works</a>
        <a href="<?php echo home_url('/design'); ?>" class="text-[11px] uppercase tracking-luxury text-white/65 hover:text-white transition-colors duration-200 whitespace-nowrap">Design Your Jacket</a>
        <a href="<?php echo home_url('/schedule-a-call'); ?>" class="text-[11px] uppercase tracking-luxury text-white/65 hover:text-white transition-colors duration-200 whitespace-nowrap">Schedule a Call</a>
      </nav>
      <a href="<?php echo home_url('/cart'); ?>" class="relative w-9 h-9 flex items-center justify-center shrink-0 group">
        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shopping-bag text-white/65 group-hover:text-white transition-colors duration-200" aria-hidden="true"><path d="M16 10a4 4 0 0 1-8 0"></path><path d="M3.103 6.034h17.794"></path><path d="M3.4 5.467a2 2 0 0 0-.4 1.2V20a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6.667a2 2 0 0 0-.4-1.2l-2-2.667A2 2 0 0 0 17 2H7a2 2 0 0 0-1.6.8z"></path></svg>
      </a>
      <button type="button" aria-label="Open menu" class="w-10 h-10 flex items-center justify-center -mr-2 shrink-0">
        <div class="flex flex-col justify-center gap-[5px] w-5 h-4" aria-hidden="true">
          <span class="block h-px" style="background-color: white; transform: none;"></span>
          <span class="block h-px" style="background-color: white; opacity: 1;"></span>
          <span class="block h-px" style="background-color: white; transform: none;"></span>
        </div>
      </button>
    </div>
  </header>

  <div class="flex" style="min-height: calc(-64px + 100vh); padding-top: 64px;">
    <!-- Left Side - Image Preview -->
    <div class="hidden lg:block lg:w-[40%] shrink-0 relative" style="position: sticky; top: 64px; height: calc(-64px + 100vh); align-self: flex-start;">
      <img alt="Jacket preview" class="absolute inset-0 w-full h-full object-cover object-top" style="opacity: 1; transform: none;" src="<?php echo get_theme_file_uri('assets/images/collection-hero-blazer.jpg'); ?>">
      <div class="absolute inset-0" style="background: linear-gradient(to top, rgba(0, 0, 0, 0.55) 0%, rgba(0, 0, 0, 0.1) 50%, transparent 100%);"></div>
      <div class="absolute top-7 left-7">
        <p class="text-[7px] uppercase tracking-luxury" style="color: rgba(255, 255, 255, 0.35);">Alex Rose · Fine Tailoring</p>
      </div>
      <div class="absolute bottom-7 left-7" style="opacity: 1; transform: none;">
        <p class="text-[7px] uppercase tracking-luxury mb-1" style="color: rgba(200, 169, 106, 0.8);">Selected Cloth</p>
        <p class="text-white" style="font-family: &quot;Verdana Pro&quot;, Verdana, Geneva, sans-serif; font-size: 1.1rem; font-weight: 300; letter-spacing: -0.01em;">English Blazer Collection</p>
      </div>
    </div>

    <!-- Right Side - Design Form -->
    <div class="flex-1 flex flex-col" style="background-color: rgb(245, 245, 245); border-left: 1px solid rgb(229, 229, 229);">

      <!-- Tabs -->
      <div class="flex shrink-0" style="border-bottom: 1px solid rgb(229, 229, 229); background-color: rgb(255, 255, 255);">
        <button type="button" class="flex-1 py-4 text-[9px] uppercase tracking-luxury transition-colors duration-150 relative" style="color: rgb(13, 13, 13); cursor: default;">Design<div class="absolute bottom-0 left-0 right-0 h-[1.5px]" style="background-color: rgb(13, 13, 13);"></div></button>
        <button type="button" class="flex-1 py-4 text-[9px] uppercase tracking-luxury transition-colors duration-150 relative" style="color: rgb(204, 204, 204); cursor: default;">Preview</button>
        <button type="button" class="flex-1 py-4 text-[9px] uppercase tracking-luxury transition-colors duration-150 relative" style="color: rgb(204, 204, 204); cursor: default;">Reserve</button>
        <button type="button" class="flex-1 py-4 text-[9px] uppercase tracking-luxury transition-colors duration-150 relative" style="color: rgb(204, 204, 204); cursor: default;">Measurements</button>
        <button type="button" class="flex-1 py-4 text-[9px] uppercase tracking-luxury transition-colors duration-150 relative" style="color: rgb(204, 204, 204); cursor: default;">Consultation</button>
      </div>

      <!-- Content Area -->
      <div class="flex-1 overflow-y-auto">
        <!-- Header -->
        <div class="px-8 py-8 bg-white" style="border-bottom: 1px solid rgb(229, 229, 229);">
          <p class="text-[9px] uppercase tracking-luxury mb-2" style="color: rgb(200, 169, 106);">Made-to-Measure Jacket</p>
          <h1 style="font-family: &quot;Verdana Pro&quot;, Verdana, Geneva, sans-serif; font-size: clamp(1.6rem, 2.5vw, 2.2rem); font-weight: 400; color: rgb(13, 13, 13); line-height: 1.2; letter-spacing: -0.02em;">Design Your Jacket.</h1>
          <p class="text-[12px] mt-2" style="color: rgb(153, 153, 153);">Your progress is saved automatically.</p>
        </div>

        <!-- Occasion Section -->
        <div style="border-bottom: 1px solid rgb(229, 229, 229);">
          <button type="button" class="w-full flex items-center gap-4 px-6 py-5 text-left transition-colors duration-150 hover:bg-white" style="background-color: rgb(255, 255, 255);">
            <div class="w-6 h-6 rounded-full flex items-center justify-center shrink-0 transition-all duration-200" style="background-color: rgb(200, 169, 106); border: 1.5px solid rgb(200, 169, 106);">
              <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check" aria-hidden="true"><path d="M20 6 9 17l-5-5"></path></svg>
            </div>
            <div class="flex-1">
              <p class="text-[11px] uppercase tracking-luxury" style="color: rgb(13, 13, 13);">Occasion</p>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-down" aria-hidden="true" style="color: rgb(187, 187, 187); transform: rotate(180deg); transition: transform 0.25s;"><path d="m6 9 6 6 6-6"></path></svg>
          </button>
          <div style="overflow: hidden; background-color: rgb(255, 255, 255); height: auto; opacity: 1;">
            <div class="px-6 pb-6 pt-1">
              <div class="grid grid-cols-2 gap-2.5 mt-2">
                <button type="button" class="relative overflow-hidden text-left w-full transition-all duration-200" style="border: 1.5px solid rgb(229, 229, 229); height: 80px; outline: none;">
                  <img alt="Business & Smart Casual" class="absolute inset-0 w-full h-full object-cover object-top" style="opacity: 0.22; transition: opacity 0.2s;" src="<?php echo get_theme_file_uri('assets/images/lifestyle-4.jpg'); ?>">
                  <div class="absolute inset-0" style="background: linear-gradient(to right, rgba(255, 255, 255, 0.94) 0%, rgba(255, 255, 255, 0.65) 100%);"></div>
                  <div class="relative px-4 h-full flex flex-col justify-center">
                    <p class="text-[11px] uppercase tracking-luxury" style="color: rgb(85, 85, 85);">Business & Smart Casual</p>
                    <p class="text-[9px] mt-0.5" style="color: rgb(170, 170, 170);">Office · Meetings · Travel</p>
                  </div>
                </button>
                <button type="button" class="relative overflow-hidden text-left w-full transition-all duration-200" style="border: 1.5px solid rgb(200, 169, 106); height: 80px; outline: none;">
                  <img alt="Evening & Statement" class="absolute inset-0 w-full h-full object-cover object-top" style="opacity: 0.5; transition: opacity 0.2s;" src="<?php echo get_theme_file_uri('assets/images/lifestyle-6.jpg'); ?>">
                  <div class="absolute inset-0" style="background: linear-gradient(to right, rgba(255, 255, 255, 0.94) 0%, rgba(255, 255, 255, 0.65) 100%);"></div>
                  <div class="absolute top-2.5 right-2.5 w-4 h-4 rounded-full flex items-center justify-center" style="background-color: rgb(200, 169, 106);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="8" height="8" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check" aria-hidden="true"><path d="M20 6 9 17l-5-5"></path></svg>
                  </div>
                  <div class="relative px-4 h-full flex flex-col justify-center">
                    <p class="text-[11px] uppercase tracking-luxury" style="color: rgb(13, 13, 13);">Evening & Statement</p>
                    <p class="text-[9px] mt-0.5" style="color: rgb(170, 170, 170);">Events · Occasions · Celebrations</p>
                  </div>
                </button>
                <button type="button" class="relative overflow-hidden text-left w-full transition-all duration-200" style="border: 1.5px solid rgb(229, 229, 229); height: 80px; outline: none;">
                  <img alt="Country & Heritage" class="absolute inset-0 w-full h-full object-cover object-top" style="opacity: 0.22; transition: opacity 0.2s;" src="<?php echo get_theme_file_uri('assets/images/lifestyle-9.jpg'); ?>">
                  <div class="absolute inset-0" style="background: linear-gradient(to right, rgba(255, 255, 255, 0.94) 0%, rgba(255, 255, 255, 0.65) 100%);"></div>
                  <div class="relative px-4 h-full flex flex-col justify-center">
                    <p class="text-[11px] uppercase tracking-luxury" style="color: rgb(85, 85, 85);">Country & Heritage</p>
                    <p class="text-[9px] mt-0.5" style="color: rgb(170, 170, 170);">Tweed · Heritage · Outdoors</p>
                  </div>
                </button>
                <button type="button" class="relative overflow-hidden text-left w-full transition-all duration-200" style="border: 1.5px solid rgb(229, 229, 229); height: 80px; outline: none;">
                  <img alt="Seasonal" class="absolute inset-0 w-full h-full object-cover object-top" style="opacity: 0.22; transition: opacity 0.2s;" src="<?php echo get_theme_file_uri('assets/images/lifestyle-5.jpg'); ?>">
                  <div class="absolute inset-0" style="background: linear-gradient(to right, rgba(255, 255, 255, 0.94) 0%, rgba(255, 255, 255, 0.65) 100%);"></div>
                  <div class="relative px-4 h-full flex flex-col justify-center">
                    <p class="text-[11px] uppercase tracking-luxury" style="color: rgb(85, 85, 85);">Seasonal</p>
                    <p class="text-[9px] mt-0.5" style="color: rgb(170, 170, 170);">Linen · Summer · Garden Parties</p>
                  </div>
                </button>
              </div>
              <button type="button" class="mt-5 flex items-center justify-center gap-2 w-full py-3 text-[10px] uppercase tracking-luxury text-white transition-all duration-150" style="background-color: rgb(13, 13, 13);">Continue <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right" aria-hidden="true"><path d="m9 18 6-6-6-6"></path></svg></button>
            </div>
          </div>
        </div>

        <!-- Cloth Collection Section -->
        <div style="border-bottom: 1px solid rgb(229, 229, 229);">
          <button type="button" class="w-full flex items-center gap-4 px-6 py-5 text-left transition-colors duration-150 hover:bg-white" style="background-color: transparent;">
            <div class="w-6 h-6 rounded-full flex items-center justify-center shrink-0 transition-all duration-200" style="background-color: rgb(200, 169, 106); border: 1.5px solid rgb(200, 169, 106);">
              <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check" aria-hidden="true"><path d="M20 6 9 17l-5-5"></path></svg>
            </div>
            <div class="flex-1">
              <p class="text-[11px] uppercase tracking-luxury" style="color: rgb(13, 13, 13);">Cloth Collection</p>
              <p class="text-[10px] mt-0.5" style="color: rgb(200, 169, 106);">English Blazer Collection</p>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-down" aria-hidden="true" style="color: rgb(187, 187, 187); transform: rotate(0deg); transition: transform 0.25s;"><path d="m6 9 6 6 6-6"></path></svg>
          </button>
        </div>

        <!-- Style Details Section -->
        <div style="border-bottom: 1px solid rgb(229, 229, 229);">
          <button type="button" class="w-full flex items-center gap-4 px-6 py-5 text-left transition-colors duration-150 hover:bg-white" style="background-color: transparent;">
            <div class="w-6 h-6 rounded-full flex items-center justify-center shrink-0 transition-all duration-200" style="background-color: rgb(200, 169, 106); border: 1.5px solid rgb(200, 169, 106);">
              <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check" aria-hidden="true"><path d="M20 6 9 17l-5-5"></path></svg>
            </div>
            <div class="flex-1">
              <p class="text-[11px] uppercase tracking-luxury" style="color: rgb(13, 13, 13);">Style Details</p>
              <p class="text-[10px] mt-0.5" style="color: rgb(200, 169, 106);">Peak Lapel · Single Breasted</p>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-down" aria-hidden="true" style="color: rgb(187, 187, 187); transform: rotate(0deg); transition: transform 0.25s;"><path d="m6 9 6 6 6-6"></path></svg>
          </button>
        </div>

        <!-- Fit Section -->
        <div style="border-bottom: 1px solid rgb(229, 229, 229);">
          <button type="button" class="w-full flex items-center gap-4 px-6 py-5 text-left transition-colors duration-150 hover:bg-white" style="background-color: transparent;">
            <div class="w-6 h-6 rounded-full flex items-center justify-center shrink-0 transition-all duration-200" style="background-color: rgb(200, 169, 106); border: 1.5px solid rgb(200, 169, 106);">
              <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check" aria-hidden="true"><path d="M20 6 9 17l-5-5"></path></svg>
            </div>
            <div class="flex-1">
              <p class="text-[11px] uppercase tracking-luxury" style="color: rgb(13, 13, 13);">Fit</p>
              <p class="text-[10px] mt-0.5" style="color: rgb(200, 169, 106);">Classic</p>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-down" aria-hidden="true" style="color: rgb(187, 187, 187); transform: rotate(0deg); transition: transform 0.25s;"><path d="m6 9 6 6 6-6"></path></svg>
          </button>
        </div>

        <!-- Mobile CTA -->
        <div class="lg:hidden sticky bottom-0 left-0 right-0 z-10 flex items-center justify-between px-5 py-3.5 bg-white" style="border-top: 1px solid rgb(229, 229, 229);">
          <div>
            <p class="text-[8px] uppercase tracking-luxury" style="color: rgb(170, 170, 170);">Starting From</p>
            <p style="font-size: 1.2rem; font-family: &quot;Verdana Pro&quot;, Verdana, Geneva, sans-serif; font-weight: 400; color: rgb(13, 13, 13); line-height: 1;">£595</p>
          </div>
          <button type="button" class="flex items-center gap-2 text-white text-[9px] uppercase tracking-luxury px-5 py-2.5 transition-all duration-150" style="background-color: rgb(13, 13, 13);">Preview <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right" aria-hidden="true"><path d="m9 18 6-6-6-6"></path></svg></button>
        </div>

        <!-- Desktop CTA -->
        <div class="hidden lg:flex items-center justify-between px-6 py-3.5 shrink-0" style="border-top: 1px solid rgb(229, 229, 229); background-color: rgb(255, 255, 255); opacity: 1; transform: none;">
          <div class="flex items-center gap-3">
            <img alt="" class="w-9 h-9 object-cover shrink-0" src="<?php echo get_theme_file_uri('assets/images/cloth-english-blazer.jpg'); ?>">
            <div>
              <p class="text-[8px] uppercase tracking-luxury" style="color: rgb(187, 187, 187);">Starting From</p>
              <p style="font-family: &quot;Verdana Pro&quot;, Verdana, Geneva, sans-serif; font-size: 1.1rem; font-weight: 400; color: rgb(13, 13, 13); line-height: 1.1;">£595</p>
            </div>
          </div>
          <button type="button" class="flex items-center gap-2 text-white text-[9px] uppercase tracking-luxury px-6 py-2.5 transition-all duration-150" style="background-color: rgb(13, 13, 13);">Preview Your Jacket<svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right" aria-hidden="true"><path d="m9 18 6-6-6-6"></path></svg></button>
        </div>
      </div>
    </div>
  </div>
</div>

<?php get_footer(); ?>
