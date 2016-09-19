<header class="marks-heading">
    <div class="marks-heading-wrapper">
        <div class="marks-heading-bar">
            <?php if (isset($errors['2'])) : ?>
            <h2><?php echo _('Sorry, No marks found')?></h2>
            <?php if (isset($_GET['q']) & $lookup_type == "search") : // Only if this is for a search ?>
            <div class="marks continue-search no-results"><?php echo sprintf( _('Would you like to <a href="/marks/archive/search?q=%s">try searching your archive</a>?'), (isset($_GET['q'])) ? $_GET['q'] : ''); ?></div>
            <?php elseif ($lookup_type == 'tag') : ?>
            <div class="marks continue-search no-results"><?php echo sprintf( _('Would you like to <a href="/marks/archive/search?q=%s">try searching your archive</a>?'), $active_tag['tag_name']); ?></div>
            <?php endif; ?>
            <?php else : ?>
            <?php if (isset($lookup_type) && $lookup_type != "all") :

                // Variable Setup
                $heading = array();
                $search_term = (isset($_GET['q'])) ? $_GET['q'] : '';
                $label_name = (isset($active_label['label_name'])) ? $active_label['label_name'] : '';
                $tag_name = (isset($active_tag['tag_name'])) ? $active_tag['tag_name'] : '';

                // Page Details
                switch ($lookup_type) {
                    case 'label':
                        $heading['icon']    =   'icon-circle';
                        $heading['title']   =   sprintf(ngettext('Mark Labeled %s', 'Marks Labeled %s', $total), _($label_name));
                        break;
                    case 'archive':
                        if ( $search_term != '' ) { // Someone is searching their archives
                            $heading['icon']    =   'icon-heading_search';
                            $heading['title']   =   sprintf(ngettext('Archived Mark Found Containing "%s"', 'Archived Marks Found Containing "%s"', $total), $search_term);
                        } else {
                            $heading['icon']    =   'icon-heading_archive';
                            $heading['title']   =   ngettext('Mark Archived', 'Marks Archived', $total);
                        }
                        break;
                    case 'tag':
                        $heading['icon']    =   'icon-heading_tag';
                        $heading['title']   =   sprintf(ngettext('Mark Tagged %s', 'Marks Tagged %s', $total), $tag_name);
                        break;
                    case 'search':
                        $heading['icon']    =   'icon-heading_search';
                        $heading['title']   =   sprintf(ngettext('Mark Found Containing "%s"', 'Mrks Found Containing "%s"', $total), $search_term);
                        break;
                    default:
                        $heading['icon']    =   'icon-heading_time';
                        $heading['title']   =   ngettext('Mark', 'Marks', $total);
                        $default_title      =   true;
                }

                // If a lookup time frame
                // Work some magic

                if(stristr($lookup_type, 'last-')){
                    $heading['title'] = (isset($default_title) && $lookup_type != 'custom_date') ? sprintf(ngettext('mark created in the %s', 'marks created in the %s', $total), _(str_replace('-', ' ', $lookup_type))) : $heading['title'];
                } else {
                    $heading['title'] = (isset($default_title) && $lookup_type != 'custom_date') ? sprintf(ngettext('mark created %s', 'marks created %s', $total), _(str_replace('-', ' ', $lookup_type))) : $heading['title'];
                }

            ?>
            <h2><i class="<?php print $heading['icon']; ?>"></i> <?php print $heading['title']; ?></h2>
            <?php endif; ?>
        </div>
    </div>
</header>

<?php if (isset($active_label)) : ?>
<div id="lookup-<?php print $lookup_type; ?>" class="marks" data-label-class="label-<?php print $active_label['label_id']; ?>">
<?php else : ?>
<div id="lookup-<?php print $lookup_type; ?>" class="marks">
<?php endif; ?>
    <div class="marks_list">
        <?php if (isset($marks)) : ?>
            <?php foreach ($marks as $mark) : 
            if (isset($mark->mark_title)) $mark->title = $mark->mark_title; ?>
                <div id="mark-<?php print $mark->mark_id; ?>" class="mark label-<?php print $mark->label_id; ?>">
                    <h2 class="hideoutline"><a target="_blank" href="<?php print $mark->url; ?>"><?php print $mark->title; ?></a></h2>
                    <div class="mark-meta">
                        <span class="mark-date"><?php print $mark->nice_time; ?></span>
                        <span class="mark-sep">&#9679;</span>
                        <span class="mark-link"><a target="_blank" href="<?php print $mark->url; ?>"><?php print niceUrl($mark->url); ?></a></span>
                    </div>
                    <div class="archive-target">
                        <?php if ($lookup_type == "archive") : ?>
                            <a title="Unarchive Mark" class="action mark-archive" data-action="mark_restore" href="#" data-id="<?php print $mark->mark_id; ?>">
                                <i class="icon-unarchive"></i>
                            </a>
                        <?php else : ?>
                            <a title="Archive Mark" class="action mark-archive" data-action="mark_archive" href="#" data-id="<?php print $mark->mark_id; ?>">
                                <i class="icon-check"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                    <div class="mark-actions">
                        <a title="View Mark Info" class="action mark-info" href="#" data-nofade="true" data-action="show_mark_info" data-mark="mark-data-<?php print $mark->mark_id; ?>">
                            <i>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 36 8"><title>elipsis</title><circle cx="18" cy="4" r="4"/><circle cx="32" cy="4" r="4"/><circle cx="4" cy="4" r="4"/></svg>
                            </i>
                        </a>
                        <!--
                        <a target="_blank" title="Open Mark" class="mark-info" href="<?php print $mark->url; ?>" data-mark="mark-data-<?php print $mark->mark_id; ?>">
                            <i class="icon-goto_link"></i>
                        </a>
                        <?php if ($lookup_type == "archive") : ?>
                            <a title="Unarchive Mark" class="action mark-archive" data-action="mark_restore" href="#" data-id="<?php print $mark->mark_id; ?>">
                                <i class="icon-unarchive"></i>
                            </a>
                        <?php else : ?>
                            <a title="Archive Mark" class="action mark-archive" data-action="mark_archive" href="#" data-id="<?php print $mark->mark_id; ?>">
                                <i class="icon-check"></i>
                            </a>
                        <?php endif; ?>
                        -->
                    </div>
                    <div class="note-placeholder"></div>
                    <script id="mark-data-<?php print $mark->mark_id; ?>" type="application/json"><?php echo json_encode($mark); ?></script>
                </div>
            <?php endforeach; ?>
            <?php if (isset($_GET['q']) && $lookup_type == "search" || $lookup_type == "tag" ) : ?>
                <div class="marks continue-search with-results"><?php echo sprintf( _('Would you like to <a href="/marks/archive/search?q=%s">try searching your archive</a>?'), (isset($_GET['q'])) ? $_GET['q'] : $tag_name); ?></div>
            <?php endif; ?>
        <?php else : ?>
            <div id="mark-x" class="mark label-x"><h2><?php echo _('No Marks Found')?></h2></div>
        <?php endif; ?>
    </div>
</div>

<?php if (isset($pages)) : ?>
<script type="text/javascript">
window.unmark_total_pages = <?php print $pages . ";\n"; ?>
</script>
<?php endif; ?>

<?php endif; ?>
