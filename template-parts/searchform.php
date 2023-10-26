<?php
$search_placeholder = sprintf(__('Search in %s...', 'folio-showroom'), get_bloginfo('name'));
?>
<form role="search" method="get" class="search-form mb-5" action="<?= esc_url(home_url('/')); ?>">
  <label class="visually-hidden"><?php _e('Search for:', 'folio-showroom'); ?></label>
  <div class="input-group">
    <input type="search" name="s" value="<?= get_search_query(); ?>" class="search-field form-control ps-1" placeholder="<?php esc_attr_e($search_placeholder); ?>" required>
    <div class="input-group-append pe-1">
      <button type="submit" class="search-submit btn-icon h-100" title="<?php esc_attr_e('Search', 'folio-showroom'); ?>">
        <?php echo get_template_part('template-parts/svg/icon', 'search') ?>
      </button>
    </div>
  </div>
</form>