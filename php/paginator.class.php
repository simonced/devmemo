<?php
/*
  Simple pagination class
  calculates number of necessary pages 
  and generate navigation buttons (bootstrap classes) for smarty provided
*/

class Paginator {

	static $defaultRecordsPerPage = 10; // default items count per page

	private static $paging = array(); 		// records per pages per user interface
	private static $recordsCount = array(); // records available per user interface (total of all records)


	/**
	 * Used to retreive the MySQL limit and offset numbers given a specific page
	 *
	 * @param string $name_ page name
	 * @param int $page_ page to calculate the limit offset numbers (default 1)
	 * @return arrat(start, size)
	 */
	static function getLimitFor($name_, $page_ = 1) {

		// size settings
		if(isset(self::$paging[$name_])) {
			$size = self::$paging[$name_];
		}
		else {
			$size = self::$defaultRecordsPerPage;
		}

		// start offset
		$start = $size * ($page_ - 1);

		return array($start, $size);
	}


	/**
	 * set paging settings for a specific user interface
	 *
	 * @param string $name_ user interface name
	 * @param int $records_per_page_ numbers of records to display per page for that interface
	 */
	static function setPagingFor($name_, $records_per_page_) {
		self::$paging[$name_] = $records_per_page_;
	}


	/**
	 * retreive a specific user interface pagination settings
	 * 
	 * @param  int $name_ interface name
	 * @return int numbers of records per page for that interface
	 */
	static function getPagingFor($name_) {
		return self::$paging[$name_];
	}


	/**
	 * To use right after the MySQL query involving retreiving data
     * original query should contain SELECT SQL_CALC_FOUND_ROWS
     * with PHP, important to free the mysql result set before calling that method
	 *
	 * @param string $name_ user interface name
	 * @param int $static_count_ total number of records (optional)
	 * @return int (total of records available for the previous query)
	 */
	static function  setRecordsCountFor($name_, $static_count_=null) {
		if($static_count_!==null) {
			self::$recordsCount[$name_] = $static_count_;
		}
		else {
			// DB reading (TODO this could be set better maybe...)
			global $conn; // MySQLi connexion

			$sql = "SELECT FOUND_ROWS()";
			$rs = $conn->query($sql);
			$count = $rs->fetch_row();

			// save in memory for later use
			self::$recordsCount[$name_] = $count[0];
		}

        // total number of records
		return $count[0];
	}


	/**
	 * counts pages needed for given interface
	 *
	 * @param string $name_ user interface name
	 * @return int number of pages needed
	 */
	static function getPagesCountFor($name_) {
		$pages = 0;
		if(isset(self::$recordsCount[$name_]) && isset(self::$paging[$name_])) {
			$pages = ceil(self::$recordsCount[$name_] / self::$paging[$name_]);
		}

		return $pages;
	}


	/**
	 * set currently selected page for given interface
     * info saved in session in order to handle pages navigation
	 *
	 * @param string $name_
	 * @param int $page_
	 */
	static function setCurrentPageFor($name_, $page_) {
		if(!isset($_SESSION['paginator'])) {
			$_SESSION['paginator'] = array();
		}

		$_SESSION['paginator'][$name_] = $page_;
	}


	/**
	 * current page for given interface
	 *
	 * @param string $name_ interface name
	 * @return int
	 */
	static function getCurrentPageFor($name_) {
		$page = 1;

		if(isset($_SESSION['paginator'][$name_])) {
			$page = $_SESSION['paginator'][$name_];
		}

		return $page;
	}
}


/**
 * generating pagination links (usually at bottom of the page)
 * sample usage: {insert paginator page_name="admin-comments" format="xxx[PAGE]"}
 * format : [PAGE] will be replaced by correct page numbers

 * @param array $params_ (page_name => string, format => string)
 * @return string HTML
 */
function smarty_insert_paginator($params_) {
	$page_name = $params_['page_name'];
	$page_count = Paginator::getPagesCountFor($page_name);

	if($page_count<=1) {
		// no pagination needed
		return "";
	}

	// link format

	$navigation = "
<div class='navigation-paginator navigation-$page_name-container'>
<nav aria-label='Page navigation navigation'>
  <ul class='pagination'>
";

	for($page=1; $page<=$page_count; ++$page) {
		$class = "page-item";
		if($page == Paginator::getCurrentPageFor($page_name)) {
			$class .= " active";
		}

		if(isset($params_['format'])) {
			$link = str_replace('[PAGE]', $page, $params_['format']);
		}
		else {
			$link = "#page/".$page;
		}

		$navigation .= "<li class='$class'><a href='$link' class='page-link'>$page</a></li>";
	}

	$navigation .= "
  </ul>
</nav>
</div>
";

	return $navigation;
}

/**
 * previous page button
 *
 * usage sample:
 * {insert paginatorPrev page_name="search" format="?page=[PAGE]" class="btn btn-primary" text="&laquo;"}
 *
 * @param array $params_
 * @return string HTML
 */
function smarty_insert_paginatorPrev($params_) {
	// params
	$page_name = $params_['page_name']; // mandatory!
	$format = $params_['format'] ?: '#page/[PAGE]';   // default link format
	$class = $params_['class'] ?: 'btn btn-primary';  // default class for button
	$text = $params_['text'] ?: 'Prev'; // default is Prev

	$page_current = Paginator::getCurrentPageFor($page_name);

	$html = '';
	
	if($page_current>1) {
		$page = $page_current - 1;
		$link = str_replace('[PAGE]', $page, $format);
		$html = "<a class='$class' href='$link'>$text</a>";
	}

	return $html;
}


// same as above but for next page
function smarty_insert_paginatorNext($params_) {
	// params
	$page_name = $params_['page_name']; // mandatory!
	$format = $params_['format'] ?: '#page/[PAGE]';
	$class = $params_['class'] ?: 'btn btn-primary';
	$text = $params_['text'] ?: 'Next';

	$page_current = Paginator::getCurrentPageFor($page_name);
	$page_count = Paginator::getPagesCountFor($page_name);

	$html = '';
	
	if($page_current<$page_count) {
		$page = $page_current + 1;
		$link = str_replace('[PAGE]', $page, $format);
		$html = "<a class='$class' href='$link'>$text</a>";
	}

	return $html;
}
