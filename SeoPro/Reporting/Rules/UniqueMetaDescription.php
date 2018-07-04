<?php

namespace Statamic\Addons\SeoPro\Reporting\Rules;

use Statamic\Addons\SeoPro\Reporting\Rule;

class UniqueMetaDescription extends Rule
{
    use Concerns\FailsWhenPagesDontPass;

    /**
     * Number of pages with this page's meta description.
     */
    protected $count;

    public function siteDescription()
    {
        return 'Each page should have a unique meta description.';
    }

    public function pageDescription()
    {
        return 'The meta description should be unique.';
    }

    public function siteFailingComment()
    {
        return sprintf('%s pages with duplicate meta descriptions.', $this->failures);
    }

    public function pageFailingComment()
    {
        return sprintf('%s pages with "%s" as the meta description.', $this->count, $this->page->get('description'));
    }

    public function processPage()
    {
        $this->count = $this->page->report()->pages()->filter(function ($page) {
            return $page->get('description') === $this->page->get('description');
        })->count();
    }

    public function savePage()
    {
        return $this->count;
    }

    public function loadPage($data)
    {
        $this->count = $data;
    }

    public function pageStatus()
    {
        return $this->count === 1 ? 'pass' : 'fail';
    }
}
