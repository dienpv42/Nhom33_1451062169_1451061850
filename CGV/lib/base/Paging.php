<?php

class Paging {
	public static $naviTemplate = '<div class="box_pager">
			<ul>
				{PREV_ITEM}
				{NAV_ITEM}
				{NEXT_ITEM}
			</ul>
		</div>';
	
	public static $prevItem = '<li class="prev"><a href="{LINK}">次へ</a></li>';
	public static $nextItem = '<li class="next"><a href="{LINK}">前へ</a></li>';
	public static $item = '<li><a href="{LINK}">{PAGE}</a></li>';
	
	public static function make($totalItems = 0, $page = 1, $otherParams = "", $numPerpage = LIMIT) {
		$pageRange = 2;
		$pagingDiv = self::$naviTemplate;
		$totalPage = ceil(($totalItems/$numPerpage));
		if ($totalPage == 1) {
			return '';
		}
		$pageStr = 'page=';
		$link = '?';
		$query = $_SERVER['QUERY_STRING'];
		if (!empty($query)) {
			$queryArr = explode('&', $query);
			self::removePage($queryArr);
			if (!empty($queryArr))
				$link = '?' . implode('&', $queryArr) . '&';
		}
		$prevItem = $nextItem = '';
		$navItem = array();
		$firstPage = $page - $pageRange;
		$lastPage = $page + $pageRange;
		
		if ($firstPage < 1) {
			$firstPage = 1;
		}
		if ($lastPage > $totalPage) {
			$lastPage = $totalPage;
		}
		
		$prevItem = str_replace('{LINK}', $link.$pageStr.($page-1), self::$prevItem);
		$nextItem = str_replace('{LINK}', $link.$pageStr.($page+1), self::$nextItem);
		
		for ($currPage = $firstPage;$currPage <= $lastPage;$currPage++) {
			if ($currPage == $page) {
				$navItem[] = "<li><a class='active'>{$currPage}</a></li>";
			} else {
				$cLink = $link.$pageStr.$currPage;
				$item = str_replace('{LINK}', $cLink, self::$item);
				$item = str_replace('{PAGE}', $currPage, $item);
				
				$navItem[] = $item;
			}
		}
		
		if ($page == 1) {
			$prevItem = '';
		} else if ($page == $totalPage) {
			$nextItem = '';
		}
		
		$pagingDiv = str_replace('{PREV_ITEM}', $prevItem, $pagingDiv);
		$pagingDiv = str_replace('{NEXT_ITEM}', $nextItem, $pagingDiv);
		$pagingDiv = str_replace('{NAV_ITEM}', implode('', $navItem), $pagingDiv);
		
		return $pagingDiv;
	}
	
	public static function removePage(&$query) {
		foreach ($query as $key => $value) {
			if (strstr($value, 'page')) {
				unset($query[$key]);
			}
		}
	}
	
}