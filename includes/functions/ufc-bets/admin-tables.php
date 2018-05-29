<?php
/*
 * Custom UFC betting plugin
 *
 * 3) add admin tables for pages
 */

// display events as WP table ==================================================
class ufc_events_display_table extends WP_List_Table {

  function __construct(){
      global $status, $page;

      //Set parent defaults
      parent::__construct( array(
          'singular'  => 'event',     //singular name of the listed records
          'plural'    => 'events',    //plural name of the listed records
          'ajax'      => false        //does this table support ajax?
      ) );

  }

  function column_default($item, $column_name) {
    return $item[$column_name];
  }

  function column_name($item) {
    // links going to /admin.php?page=[your_plugin_page][&other_params]
    // notice how we used $_REQUEST['page'], so action will be done on curren page
    // also notice how we use $this->_args['singular'] so in this example it will
    // be something like &person=2
    $actions = array(
        'edit' => sprintf('<a href="?page=%s&action=edit&id=%s">%s</a>', $_REQUEST['page'], $item['id'], 'Edit'),
        'delete' => sprintf('<a href="?page=%s&action=delete&id=%s">%s</a>', $_REQUEST['page'], $item['id'], 'Delete'),
    );
    return sprintf('%s %s',
        $item['name'],
        $this->row_actions($actions)
    );
  }

  function column_cb($item){
      return sprintf(
          '<input type="checkbox" name="%1$s[]" value="%2$s" />',
          /*$1%s*/ $this->_args['singular'],  //Let's simply repurpose the table's singular label ("movie")
          /*$2%s*/ $item['id']                //The value of the checkbox should be the record's id
      );
  }

  function get_columns(){
      $columns = array(
          'cb'        => '<input type="checkbox" />', //Render a checkbox instead of text
          'name'      => 'Name',
          'time'      => 'Time',
          'lock_time' => 'Lock Time'
      );
      return $columns;
  }

  function get_sortable_columns() {
      $sortable_columns = array(
          'name'      => array('name',false),     //true means it's already sorted
          'time'      => array('time',false),
          'lock_time' => array('lock_time',false)
      );
      return $sortable_columns;
  }

  function get_bulk_actions() {
      $actions = array(
          'delete'    => 'Delete'
      );
      return $actions;
  }

  function process_bulk_action() {
      global $wpdb;
      $ufc_events = $wpdb->prefix . 'ufcBet_events';
      $ufc_fights = $wpdb->prefix . 'ufcBet_fights';
      if ('delete' === $this->current_action()) {
          $ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : array();
          if (is_array($ids)) $ids = implode(',', $ids);
          if (!empty($ids)) {
              $wpdb->query("DELETE FROM $ufc_events WHERE id IN($ids)");
              $wpdb->query("DELETE FROM $ufc_fights WHERE event_id IN($ids)");
          }
      }
  }

  function prepare_items() {
      global $wpdb; //This is used only if making any database queries
      $ufc_events = $wpdb->prefix . 'ufcBet_events';

      $per_page = 50;

      $columns = $this->get_columns();
      $hidden = array();
      $sortable = $this->get_sortable_columns();

      $this->_column_headers = array($columns, $hidden, $sortable);

      $this->process_bulk_action();

      // will be used in pagination settings
      $total_items = $wpdb->get_var("SELECT COUNT(id) FROM $ufc_events");
      // prepare query params, as usual current page, order by and order direction
      $paged = isset($_REQUEST['paged']) ? max(0, intval($_REQUEST['paged']) - 1) : 0;
      $orderby = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array_keys($this->get_sortable_columns()))) ? $_REQUEST['orderby'] : 'name';
      $order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? $_REQUEST['order'] : 'asc';

      // notice that last argument is ARRAY_A, so we will retrieve array
      $this->items = $wpdb->get_results($wpdb->prepare("SELECT * FROM $ufc_events ORDER BY $orderby $order LIMIT %d OFFSET %d", $per_page, $paged), ARRAY_A);

      $this->set_pagination_args( array(
          'total_items' => $total_items,                  //WE have to calculate the total number of items
          'per_page'    => $per_page,                     //WE have to determine how many items to show on a page
          'total_pages' => ceil($total_items/$per_page)   //WE have to calculate the total number of pages
      ) );
  }

}

// display bets as WP table ====================================================
class ufc_bets_display_table extends WP_List_Table {

  function __construct(){
      global $status, $page;

      //Set parent defaults
      parent::__construct( array(
          'singular'  => 'bet',     //singular name of the listed records
          'plural'    => 'bets',    //plural name of the listed records
          'ajax'      => false        //does this table support ajax?
      ) );

  }

  function column_default($item, $column_name) {
    return $item[$column_name];
  }

  function column_name($item) {
    // links going to /admin.php?page=[your_plugin_page][&other_params]
    // notice how we used $_REQUEST['page'], so action will be done on curren page
    // also notice how we use $this->_args['singular'] so in this example it will
    // be something like &person=2
    $actions = array(
        'edit' => sprintf('<a href="?page=%s&action=edit&id=%s">%s</a>', $_REQUEST['page'], $item['id'], 'Edit'),
        'delete' => sprintf('<a href="?page=%s&action=delete&id=%s">%s</a>', $_REQUEST['page'], $item['id'], 'Delete'),
    );
    return sprintf('%s %s',
        $item['name'],
        $this->row_actions($actions)
    );
  }

  function column_cb($item){
      return sprintf(
          '<input type="checkbox" name="%1$s[]" value="%2$s" />',
          /*$1%s*/ $this->_args['singular'],  //Let's simply repurpose the table's singular label ("movie")
          /*$2%s*/ $item['id']              //The value of the checkbox should be the record's id
      );
  }

  function get_columns(){
      $columns = array(
          'cb'                   => '<input type="checkbox" />', //Render a checkbox instead of text
          'id'                   => 'ID',
          'username'             => 'Username',
          'fighter_selected'     => 'Fighter Selected',
          'ufc_fight_id'         => 'UFC Fight ID',
          'fight_id'             => 'Fight ID',
          'is_correct'           => 'Is Correct'
      );
      return $columns;
  }

  function get_sortable_columns() {
      $sortable_columns = array(
          'id'        => array('id',false),     //true means it's already sorted
          'username'  => array('username',false)
      );
      return $sortable_columns;
  }

  function get_bulk_actions() {
      $actions = array(
          'delete'    => 'Delete'
      );
      return $actions;
  }

  function process_bulk_action() {
      global $wpdb;
      $ufc_betts = $wpdb->prefix . 'ufcBet_bets';
      if ('delete' === $this->current_action()) {
          $ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : array();
          if (is_array($ids)) $ids = implode(',', $ids);
          if (!empty($ids)) {
              $wpdb->query("DELETE FROM $ufc_bets WHERE id IN($ids)");
          }
      }
  }

  function prepare_items() {
      global $wpdb; //This is used only if making any database queries
      $ufc_bets = $wpdb->prefix . 'ufcBet_bets';

      $per_page = 10;

      $columns = $this->get_columns();
      $hidden = array();
      $sortable = $this->get_sortable_columns();

      $this->_column_headers = array($columns, $hidden, $sortable);

      $this->process_bulk_action();

      // will be used in pagination settings
      $total_items = $wpdb->get_var("SELECT COUNT(id) FROM $ufc_bets");
      // prepare query params, as usual current page, order by and order direction
      $paged = isset($_REQUEST['paged']) ? max(0, intval($_REQUEST['paged']) - 1) : 0;
      $orderby = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array_keys($this->get_sortable_columns()))) ? $_REQUEST['orderby'] : 'id';
      $order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? $_REQUEST['order'] : 'asc';

      // notice that last argument is ARRAY_A, so we will retrieve array
      $this->items = $wpdb->get_results($wpdb->prepare("SELECT * FROM $ufc_bets ORDER BY $orderby $order LIMIT %d OFFSET %d", $per_page, $paged), ARRAY_A);

      $this->set_pagination_args( array(
          'total_items' => $total_items,                  //WE have to calculate the total number of items
          'per_page'    => $per_page,                     //WE have to determine how many items to show on a page
          'total_pages' => ceil($total_items/$per_page)   //WE have to calculate the total number of pages
      ) );
  }

}

?>
