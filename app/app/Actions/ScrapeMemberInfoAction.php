<?php

namespace App\Actions;

use App\Enums\CountryEnum;
use App\Member;

class ScrapeMemberInfoAction extends Action
{
    private $scrapeAction;

    public function __construct(ScrapeAction $scrapeAction)
    {
        $this->scrapeAction = $scrapeAction;
    }

    public function execute(Member $member): void
    {
        $data = $this->scrapeAction->execute('member_info', [
            'web_id' => $member->web_id,
        ]);

        $this->log('Importing member info', $data);

        $data['country'] = CountryEnum::make($data['country']);

        $member->update($data);
    }
}
