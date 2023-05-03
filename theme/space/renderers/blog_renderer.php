<?php


require_once($CFG->dirroot . "/blog/renderer.php");

/**
 * Blog renderer
 */
class theme_space_core_blog_renderer extends core_blog_renderer {

  /**
   * Renders a blog entry
   *
   * @param blog_entry $entry
   * @return string The table HTML
   */
  public function render_blog_entry(blog_entry $entry) {

      global $CFG;

      $syscontext = context_system::instance();

      $stredit = get_string('edit');
      $strdelete = get_string('delete');

      // Header.
      $mainclass = 'blog blog-entry ';
      if ($entry->renderable->unassociatedentry) {
          $mainclass .= 'draft';
      } else {
          $mainclass .= $entry->publishstate;
      }
      $o = $this->output->container_start($mainclass, 'b' . $entry->id);
      $o .= $this->output->container_start('blog-entry-author');

      // User picture.
      $o .= $this->output->container_start('d-inline-flex align-items-center justify-content-between w-100');

      // Post by.
      $by = new stdClass();
      $fullname = fullname($entry->renderable->user, has_capability('moodle/site:viewfullnames', $syscontext));
      $userurlparams = array('id' => $entry->renderable->user->id, 'course' => $this->page->course->id);
      $by->name = html_writer::link(new moodle_url('/user/view.php', $userurlparams), $fullname);

      $postCreated = userdate($entry->created);

      $o .= $this->output->container_start('blog-entry-date-box d-inline-flex align-items-center justify-content-between');

        $o .= html_writer::start_div('blog-entry-date');
            $o .=  html_writer::start_tag('i', ['class' => 'far fa-calendar-alt mr-2']);
            $o .=  html_writer::end_tag('i');
            $o .=  $postCreated;
        $o .=  html_writer::end_div();

        // Last modification.
        if ($entry->created != $entry->lastmodified) {
            $o .= $this->output->container(get_string('modified').': '.userdate($entry->lastmodified) ,'blog-entry-date--mod badge badge-light ml-2');
        }
      $o .= $this->output->container_end(); // blog-entry-date-box end

      $o .= $this->output->user_picture($entry->renderable->user);
      $o .= $this->output->container_end(); // user picture container end
      $o .= $this->output->container_end(); // user picture and date container end

      // Title.
      $titlelink = html_writer::link(new moodle_url('/blog/index.php',
      array('entryid' => $entry->id)),
      format_string($entry->subject));
      $o .= $this->output->container_start('blog-entry-topic');
      $o .= $this->output->container($titlelink, 'subject');


      // Attachments.
      $attachmentsoutputs = array();
      if ($entry->renderable->attachments) {
          foreach ($entry->renderable->attachments as $attachment) {
              $o .= $this->render($attachment, false);
          }
      }

      $o .= $this->output->container_end();


      // Post content.
      $o .= $this->output->container_start('blog-entry-content-box');

      // Entry.
      $o .= $this->output->container_start('blog-entry');

      // Determine text for publish state.
      switch ($entry->publishstate) {
          case 'draft':
              $blogtype = get_string('publishtonoone', 'blog');
              break;
          case 'site':
              $blogtype = get_string('publishtosite', 'blog');
              break;
          case 'public':
              $blogtype = get_string('publishtoworld', 'blog');
              break;
          default:
              $blogtype = '';
              break;

      }

      // Body.
      if ( strpos($this->page->url, 'entryid') == true ) {
        $o .= format_text($entry->summary, $entry->summaryformat, array('overflowdiv' => true));
      } else {
        $o .= substr(format_string($entry->summary),0, 300) . '...';
      }

      if (!empty($entry->uniquehash)) {
          // Uniquehash is used as a link to an external blog.
          $url = clean_param($entry->uniquehash, PARAM_URL);
          if (!empty($url)) {
              $o .= $this->output->container_start('externalblog badge badge-secondary badge-link');
              $o .= html_writer::link($url, get_string('linktooriginalentry', 'blog'));
              $o .= $this->output->container_end();
          }
      }

      // Links to tags.
      $o .= $this->output->tag_list(core_tag_tag::get_item_tags('core', 'post', $entry->id));

      // Add associations.
      if (!empty($CFG->useblogassociations) && !empty($entry->renderable->blogassociations)) {

          // First find and show the associated course.
          $assocstr = '';
          $coursesarray = array();
          foreach ($entry->renderable->blogassociations as $assocrec) {
              if ($assocrec->contextlevel == CONTEXT_COURSE) {
                  $coursesarray[] = $this->output->action_icon($assocrec->url, $assocrec->icon, null, array(), true);
              }
          }
          if (!empty($coursesarray)) {
              $assocstr .= get_string('associated', 'blog', get_string('course')) . ': ' . implode(', ', $coursesarray);
          }

          // Now show mod association.
          $modulesarray = array();
          foreach ($entry->renderable->blogassociations as $assocrec) {
              if ($assocrec->contextlevel == CONTEXT_MODULE) {
                  $str = get_string('associated', 'blog', $assocrec->type) . ': ';
                  $str .= $this->output->action_icon($assocrec->url, $assocrec->icon, null, array(), true);
                  $modulesarray[] = $str;
              }
          }
          if (!empty($modulesarray)) {
              if (!empty($coursesarray)) {
                  $assocstr .= '<br/>';
              }
              $assocstr .= implode('<br/>', $modulesarray);
          }

          // Adding the asociations to the output.
          $o .= $this->output->container($assocstr, 'tags');
      }

      if ($entry->renderable->unassociatedentry) {
          $o .= $this->output->container(get_string('associationunviewable', 'blog'), 'noticebox');
      }

      // Commands.
      $o .= $this->output->container_start('blog-entry-footer');
      if ($entry->renderable->usercanedit) {

          // External blog entries should not be edited.
          if (empty($entry->uniquehash)) {
              $o .= html_writer::link(new moodle_url('/blog/edit.php',
                                                      array('action' => 'edit', 'entryid' => $entry->id)),
                                                      $stredit, array('class'=>'badge badge-link badge-secondary badge-icon badge-icon--edit'));
          }
          $o .= html_writer::link(new moodle_url('/blog/edit.php',
                                                  array('action' => 'delete', 'entryid' => $entry->id)),
                                                  $strdelete, array('class'=>'badge badge-link badge-danger badge-icon badge-icon--delete'));
      }

      $entryurl = new moodle_url('/blog/index.php', array('entryid' => $entry->id));
      $o .= html_writer::link($entryurl, get_string('permalink', 'blog'), array('class'=>'badge badge-link badge-light badge-icon badge-icon--permalink'));

      $o .= $this->output->container_end();

      // Adding external blog link.
      if (!empty($entry->renderable->externalblogtext)) {
        $o .= $this->output->container($entry->renderable->externalblogtext, 'externalblog badge badge-light badge-link mt-1');
      }


      // Determine text for publish state.
      switch ($entry->publishstate) {
        case 'draft':
            $blogtype = get_string('publishtonoone', 'blog');
            break;
        case 'site':
            $blogtype = get_string('publishtosite', 'blog');
            break;
        case 'public':
            $blogtype = get_string('publishtoworld', 'blog');
            break;
        default:
            $blogtype = '';
            break;

    }
    $o .= $this->output->container($blogtype, 'audience badge badge-light mt-2');

      // Comments.
      if ( strpos($this->page->url, 'entryid') == true ) {
        if (!empty($entry->renderable->comment)) {
            $o .= $entry->renderable->comment->output(true);
        }
      }

      $o .= $this->output->container_end();

      // Closing maincontent div.
      $o .= $this->output->container('', 'side options');
      $o .= $this->output->container_end();

      $o .= $this->output->container_end();

      return $o;
  }

  /**
   * Renders an entry attachment
   *
   * Print link for non-images and returns images as HTML
   *
   * @param blog_entry_attachment $attachment
   * @return string List of attachments depending on the $return input
   */
  public function render_blog_entry_attachment(blog_entry_attachment $attachment) {

      $syscontext = context_system::instance();

      // Image attachments don't get printed as links.
      if (file_mimetype_in_typegroup($attachment->file->get_mimetype(), 'web_image')) {
          $attrs = array('src' => $attachment->url, 'alt' => '');
          $o = html_writer::empty_tag('img', $attrs);
          $class = 'blog-entry-cover';
      } else {
          $image = $this->output->pix_icon(file_file_icon($attachment->file),
                                           $attachment->filename,
                                           'moodle',
                                           array('class' => 'icon'));
          $o = html_writer::link($attachment->url, $image);
          $o .= format_text(html_writer::link($attachment->url, $attachment->filename),
                            FORMAT_HTML,
                            array('context' => $syscontext));
          $class = 'attachments';
      }

      return $this->output->container($o, $class);
  }
}

