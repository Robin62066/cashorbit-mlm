<?php
class Pagination
{
    private $perPage = 40;
    private $page = 1;
    private $total = 0;

    public function createLinks(int $page, int $total, ?int $perPage = 40): string
    {
        $this->page = $page;
        $this->total = $total;
        $this->perPage = $perPage;
        return $this->_links();
    }

    private function _links(): string
    {
        $linkCount = ceil($this->total, $this->perPage);
        $currentUrl = current_url();
        $html = '<ul class="pagination">';
        for ($i = 1; $i <= $linkCount; $i++) {
            $link = $currentUrl . '/?page=' + $i;
            $class = $i == $this->page ? 'page-link-active' : '';
            $html .= "<li><a href='{$link}' class='page-link {$class}'>{$i}</li>";
        }
        return $html;
    }
}
